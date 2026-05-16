<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\M_DataKaryawan;
use App\Models\PayrollModel;
use App\Models\M_Sharing;
use App\Models\KasbonModel;

class PayrollService
{
    public function generate($karyawanId, $cutoffStart, $cutoffEnd, $periode)
    {
        $cutoffStart = Carbon::parse($cutoffStart);
        $cutoffEnd   = Carbon::parse($cutoffEnd);

        $karyawan = M_DataKaryawan::findOrFail($karyawanId);
        $entitasName = session('selected_entitas');
        $entitas = M_Entitas::where('nama', $entitasName)->first();
        $entitasId = $entitas->id ?? null;
        $gajiPokok = $this->num($karyawan->gaji_pokok);
        $tunjanganJabatan = $this->num($karyawan->tunjangan_jabatan);

        // =========================
        // REKAP PRESENSI
        // =========================
        $rekap = $this->rekapPresensi($karyawanId, $cutoffStart, $cutoffEnd);

        // =========================
        // LEMBUR
        // =========================
        [$lembur, $lemburLibur] = $this->hitungLembur($karyawanId, $cutoffStart, $cutoffEnd, $gajiPokok, $tunjanganJabatan);

        // =========================
        // UANG MAKAN & TRANSPORT
        // =========================
        $uangMakan = $this->hitungUangMakan($karyawanId, $cutoffStart, $cutoffEnd);
        $transport = ($karyawan->transport ?? 0) * $this->countLemburLibur($karyawanId, $cutoffStart, $cutoffEnd);

        $tunjangan_coc = $karyawan->tunjangan_coc ?? 0;
        $tunjangan_kinerja = $karyawan->tunjangan_kinerja ?? 0;

        // =========================
        // FEE SHARING
        // =========================
        $feeSharing = M_Sharing::where('karyawan_id', $karyawanId)
            ->whereBetween('date', [$cutoffStart, $cutoffEnd])
            ->where('status', 1)
            ->count() * 100000;

        // =========================
        // KASBON
        // =========================
        $kasbon = $this->hitungKasbon($karyawanId, $cutoffEnd);

        // =========================
        // POTONGAN IZIN & TERLAMBAT
        // =========================
        $perHari = ($gajiPokok + $tunjanganJabatan) / 26;

        $potonganIzin = round($perHari * (
            $rekap['izin']
            + 0.5 * $rekap['izin setengah hari']
            + 0.5 * $rekap['izin setengah hari pagi']
            + 0.5 * $rekap['izin setengah hari siang']
            + 0.5 * $rekap['konter izin setengah hari masuk pagi']
        ));

        $potonganTerlambat = $rekap['terlambat'] * 25000;

        // =========================
        // BPJS
        // =========================
        $bpjs = $this->hitungBpjs($gajiPokok, $tunjanganJabatan);
        $rekap['lembur'] = $this->totalJamLembur($karyawanId, $cutoffStart, $cutoffEnd);

        $rekap['cutoff_start'] = $cutoffStart->format('Y-m-d');
        $rekap['cutoff_end']   = $cutoffEnd->format('Y-m-d');

        // =========================
        // TOTAL GAJI
        // =========================
        $total =
            $gajiPokok +
            $tunjanganJabatan +
            $lembur +
            $lemburLibur +
            $feeSharing +
            $transport +
            $tunjangan_coc +
            $tunjangan_kinerja +
            $uangMakan
            - $kasbon
            - $potonganIzin
            - $potonganTerlambat
            - $bpjs['jht']
            - $bpjs['kesehatan'];

        // =========================
        // SIMPAN
        // =========================
        $noSlip = $this->generateNoSlip($periode, $entitasId);

        return PayrollModel::create([
            'karyawan_id' => $karyawanId,
            'entitas_id' => $entitasId,
            'titip' => 0,
            'nip_karyawan' => $karyawan->nip_karyawan ?? null,
            'no_slip' => $noSlip,
            'divisi' => $karyawan->divisi ?? null,

            'gaji_pokok' => $gajiPokok,
            'tunjangan_jabatan' => $tunjanganJabatan,

            'lembur' => $lembur,
            'lembur_libur' => $lemburLibur,

            'tunjangan_kebudayaan' => 0,

            'izin' => $potonganIzin,
            'terlambat' => $potonganTerlambat,

            'tunjangan' => json_encode([]),
            'potongan' => json_encode([]),

            'bpjs' => $bpjs['kesehatan'],
            'bpjs_perusahaan' => 0,

            'bpjs_jht' => $bpjs['jht'],
            'bpjs_jht_perusahaan' => 0,

            'uang_makan' => $uangMakan,
            'jml_uang_makan' => 0,

            'transport' => $transport,
            'jml_transport' => 0,

            'fee_sharing' => $feeSharing,
            'inov_reward' => 0,
            'insentif' => 0,
            'jml_psb' => 0,
            'churn' => 0,

            'kasbon' => $kasbon,
            'voucher' => $karyawan->voucher ?? 0,
            'tunjangan_coc' => $tunjangan_coc,
            'tunjangan_kinerja' => $tunjangan_kinerja,

            'rekap' => json_encode($rekap),

            'total_gaji' => $total,
            'periode' => $periode,
        ]);
    }

    // =========================
    // REKAP PRESENSI
    // =========================
    public function rekapPresensi($id, $start, $end)
    {
        $jadwalList = M_Jadwal::where('karyawan_id', $id)
            ->whereIn('bulan_tahun', [$start->format('Y-m'), $end->format('Y-m')])
            ->get()
            ->keyBy('bulan_tahun');

        $izin = $cuti = $izinSetengah = 0;
        $izinPagi = $izinSiang = $konter = 0;

        $tanggal = $start->copy();

        while ($tanggal->lte($end)) {
            $jadwal = $jadwalList[$tanggal->format('Y-m')] ?? null;

            if ($jadwal) {
                $kode = $jadwal->{'d' . $tanggal->day} ?? null;

                if ($kode == 3) $izin++;
                elseif ($kode == 2) $cuti++;
                elseif ($kode == 8) $izinSetengah++;
                elseif ($kode == 22) $izinPagi++;
                elseif ($kode == 23) $izinSiang++;
                elseif ($kode == 29) $konter++;
            }

            $tanggal->addDay();
        }

        $terlambat = M_Presensi::where('user_id', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 1)
            ->count();

        return [
            'kehadiran' => 26 - $izin - $cuti - (0.5 * $izinSetengah),
            'izin' => $izin,
            'cuti' => $cuti,
            'terlambat' => $terlambat,
            'izin setengah hari' => $izinSetengah,
            'izin setengah hari pagi' => $izinPagi,
            'izin setengah hari siang' => $izinSiang,
            'konter izin setengah hari masuk pagi' => $konter,
        ];
    }

    // =========================
    // LEMBUR
    // =========================
    public function totalJamLembur($id, $start, $end)
    {
        return M_Lembur::where('karyawan_id', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 1)
            ->sum('total_jam');
    }

    public function hitungLembur($id, $start, $end, $gaji, $tunjangan)
    {
        $data = M_Lembur::where('karyawan_id', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 1)
            ->get();

        $biasa = 0;
        $libur = 0;

        foreach ($data as $l) {
            $jam = $l->total_jam;

            if ($l->jenis == 2) {
                $libur += round((1 / 173) * ($gaji + $tunjangan) * $jam * 2);
            } else {
                $biasa += round((1 / 173) * ($gaji + $tunjangan) * $jam);
            }
        }

        return [$biasa, $libur];
    }

    public function hitungUangMakan($id, $start, $end)
    {
        $lembur = M_Lembur::where('karyawan_id', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->where('status', 1)
            ->get();

        $count = 0;

        foreach ($lembur as $l) {
            if ($l->waktu_akhir >= '18:00:00') $count++;
        }

        return $count * 15000;
    }

    public function countLemburLibur($id, $start, $end)
    {
        return M_Lembur::where('karyawan_id', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->where('jenis', 2)
            ->where('status', 1)
            ->count();
    }

    public function hitungKasbon($id, $cutoffEnd)
    {
        return KasbonModel::where('karyawan_id', $id)
            ->where('status', 'aktif')
            ->where('sisa_kasbon', '>', 0)
            ->whereDate('mulai_potong', '<=', $cutoffEnd)
            ->sum('kasbon_perbulan');
    }

    public function hitungBpjs($gaji, $tunjangan)
    {
        $umk = 2628190;
        $dasar = max($gaji + $tunjangan, $umk);

        return [
            'kesehatan' => round($dasar * 0.01),
            'jht' => round($dasar * 0.02),
        ];
    }

    private function num($v)
    {
        return is_numeric($v) ? (int)$v : (int)str_replace(['.', ','], '', $v);
    }

    public function generateNoSlip($periode, $entitasId)
    {
        $entitas = M_Entitas::find($entitasId);
        $kode = $entitas?->nama ?? 'UHO';

        $tahun = \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('Y');
        $bulan = \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('n');

        $romawi = $this->toRoman($bulan);

        $last = PayrollModel::where('entitas_id', $entitasId)
            ->where('periode', $periode)
            ->orderByDesc('id')
            ->first();

        $next = 1;

        if ($last) {
            preg_match('/(\d+)$/', $last->no_slip, $m);
            $next = isset($m[1]) ? ((int)$m[1] + 1) : 1;
        }

        $no = str_pad($next, 3, '0', STR_PAD_LEFT);

        return "006/DJB-{$kode}/HR/{$tahun}/{$romawi}/{$no}";
    }

    private function toRoman($month)
    {
        return [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ][$month] ?? $month;
    }
}
