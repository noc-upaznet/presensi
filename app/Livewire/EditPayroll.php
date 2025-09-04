<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;
use App\Models\JenisPotonganModel;
use App\Models\JenisTunjanganModel;
use Illuminate\Support\Facades\Crypt;

class EditPayroll extends Component
{
    public $payroll;
    public $karyawan;
    public $divisi;
    public $jabatan;
    public $level;
    public $gaji_pokok = 0;
    public $user_id;
    public $nip_karyawan;
    public $tunjangan_jabatan = 0;

    public $tunjangan = [
        ['nama' => '', 'nominal' => 0],
    ];

    public $potongan = [
        ['nama' => '', 'nominal' => 0],
    ];

    public $jenis_tunjangan = [];
    public $tunjangan_terpilih = [];
    public $jenis_potongan = [];
    public $potongan_terpilih = [];

    public $bulanTahun = '';
    public $rekap = [];

    public $total_gaji = 0;

    public $bpjs_digunakan = false;
    public $persentase_bpjs = 1;
    public $bpjs_nominal = 0;

    public $bpjs_jht_digunakan = false;
    public $persentase_bpjs_jht = 2;
    public $bpjs_jht_nominal = 0;
    public $no_slip;

    public $bpjs_perusahaan_digunakan = false;
    public $persentase_bpjs_perusahaan = 4;
    public $bpjs_perusahaan_nominal = 0;

    public $bpjs_jht_perusahaan_digunakan = false;
    public $persentase_bpjs_jht_perusahaan = 4.24;
    public $bpjs_jht_perusahaan_nominal = 0;

    public $selectedMonth;
    public $selectedYear;
    public $periode;
    public $karyawanId;
    public $izin_nominal = 0;
    public $terlambat_nominal = 0;
    public $jml_psb = 0;
    public $insentif = 0;
    public $tunjangan_kehadiran = 0;
    
    public $kebudayaan = 0;
    public $fee_sharing = 0;

    public $transport;
    public $transport_jumlah;
    public $transport_total = 0;

    public $uang_makan;
    public $uang_makan_jumlah;
    public $uang_makan_total = 0;

    public $inovation_reward;
    public $inovation_reward_jumlah;
    public $inovation_reward_total = 0;
    
    public $fee_sharing_digunakan = false;
    public $fee_sharing_nominal = 0;
    public $jml_psb_spv = 0;
    public $insentif_spv = 0;
    public $insentif_spv_ugr = 0;
    public $cutoffStart;
    public $cutoffEnd;
    public $entitasId;
    public $filterCutOff25;
    public $filterCutOffNormal;
    public $cutoffType = 'cutoff_normal';
    public $izin;
    public $terlambat;
    public $lembur;
    public $bpjs;
    public $bpjs_jht;
    public $bpjs_perusahaan;
    public $bpjs_jht_perusahaan;
    public $kehadiran;
    public $lembur_jam;
    public $cuti;
    public $karyawan_id;
    public $id;
    public $bulan_tahun;
    public $listLemburBiasa = [];
    public $listLemburLibur = [];
    public $lembur_libur = 0;
    public $kasbon = 0;
    public $churn = 0;


    public function mount($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $this->payroll = PayrollModel::findOrFail($id);
        } catch (\Exception $e) {
            abort(404, 'Data tidak ditemukan atau ID tidak valid.');
        }

        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->format('n');

        // 🔥 Tangkap periode dari session
        $this->periode = session('periode', $this->payroll->periode);

        // Misal $this->periode formatnya "2025-08" (Y-m)
        $this->bulanTahun = $this->periode;

        $date = \Carbon\Carbon::createFromFormat('Y-m', $this->periode);
        $this->cutoffStart = $date->copy()->startOfMonth();
        $this->cutoffEnd   = $date->copy()->endOfMonth();

        // Normal periode
        $startNormal = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1);
        if ($this->selectedYear == now()->year && $this->selectedMonth == now()->month) {
            // Kalau bulan berjalan → cutoff sampai hari ini
            $endNormal = now();
        } else {
            $endNormal = $startNormal->copy()->endOfMonth();
        }
        $this->filterCutOffNormal = [
            'start' => $startNormal,
            'end' => $endNormal,
        ];

        // Cutoff 25
        $cutoffEnd25 = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        if ($this->selectedYear == now()->year && $this->selectedMonth == now()->month && now()->day < 25) {
            // Kalau masih sebelum tanggal 25 di bulan ini → pakai hari ini
            $cutoffEnd25 = now();
        }
        $cutoffStart25 = $cutoffEnd25->copy()->subMonthNoOverflow()->setDay(26);
        $this->filterCutOff25 = [
            $cutoffEnd25,
            $cutoffStart25
        ];

        // Default set cutoff normal
        // $this->cutoffStart = $this->filterCutOffNormal['start'];
        // $this->cutoffEnd = $this->filterCutOffNormal['end'];
        // $this->bulanTahun = $this->cutoffEnd->format('Y-m');

        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();
        $this->loadData($id);
        // dd($id);
        $this->hitungUangMakanTransport();
        $this->hitungTotalGaji();

    }

    public function hitungRekap($userId)
    {
        if (!$this->cutoffStart || !$this->cutoffEnd) {
            $this->rekap = [];
            return $this->rekap;
        }

        // --- TERLAMBAT ---
        $terlambat = M_Presensi::where('user_id', $userId)
            ->where('status', 1)
            ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('lokasi_lock', 0)
                    ->where('approve', 1);
                })->orWhere(function ($q2) {
                    $q2->where('lokasi_lock', 1)
                    ->where('approve', 0);
                });
            })
            ->count();

            // dd($terlambat);

        // --- AMBIL DATA JADWAL ---
        $jadwal = M_Jadwal::where('karyawan_id', $userId)
            ->where('bulan_tahun', $this->bulanTahun) // bulanTahun sudah di-set di mount
            ->first();

        $izin = 0;
        $cuti = 0;
        $izinSetengahHari = 0;

        if ($jadwal) {
            for ($i = 1; $i <= 31; $i++) {
                $kolom = 'd'.$i;
                $val = $jadwal->$kolom ?? null;

                if ($val == 3) {
                    $izin++;
                } elseif ($val == 2) {
                    $cuti++;
                } elseif ($val == 8) {
                    $izinSetengahHari++;
                }
            }
        }

        // --- LEMBUR JAM ---
        $lemburJam = M_Lembur::where('karyawan_id', $userId)
            ->where('status', 1)
            ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
            ->sum('total_jam');

        // ✅ Hitung kehadiran (fix 26 hari kerja)
        $this->kehadiran = 26 - ($izin + $cuti + (0.5 * $izinSetengahHari));
        if ($this->kehadiran < 0) $this->kehadiran = 0; // jangan minus

        $this->terlambat   = $terlambat ?? 0;
        $this->izin        = ($izin ?? 0) + (0.5 * ($izinSetengahHari ?? 0));
        $this->cuti        = $cuti ?? 0;
        $this->lembur_jam  = $lemburJam ?? 0;

        $this->rekap = [
            'kehadiran' => $this->kehadiran,
            'terlambat' => $this->terlambat,
            'izin'      => $this->izin,
            'cuti'      => $this->cuti,
            'lembur'    => $this->lembur_jam,
        ];

        return $this->rekap;
    }

    public function loadData($id)
    {
        $payroll = PayrollModel::findOrFail($id);
        $this->karyawan = M_DataKaryawan::findOrFail($payroll->karyawan_id);
        
        // Data umum
        $this->no_slip = $payroll->no_slip;
        $this->periode = $payroll->periode;

        // Data karyawan
        $this->user_id = $this->karyawan->nama_karyawan;
        // $this->nama_karyawan = $this->karyawan->nama_karyawan ?? '';
        $this->nip_karyawan = $payroll->nip_karyawan;
        $this->divisi = $payroll->divisi;

        // Komponen gaji
        $this->gaji_pokok = $payroll->gaji_pokok;
        $this->tunjangan_jabatan = $payroll->tunjangan_jabatan;
        $this->lembur = $payroll->lembur;
        $this->lembur_libur = $payroll->lembur_libur;
        $this->kehadiran = $rekap['kehadiran'] ?? 0;
        // $this->inovation_reward = $payroll->inov_reward;
        $this->jml_psb = $payroll->jml_psb;
        $this->insentif = $payroll->insentif;
        $this->transport = $payroll->transport;
        $this->uang_makan = 15000;
        $this->uang_makan_jumlah = $payroll->jml_uang_makan;
        $this->transport_jumlah = $payroll->jml_transport;
        $this->kebudayaan = $payroll->tunjangan_kebudayaan;
        $this->fee_sharing = $payroll->fee_sharing;
        $this->kasbon = $payroll->kasbon;
        $this->churn = $payroll->churn;

        // Data JSON tunjangan & potongan
        $this->tunjangan = json_decode($payroll->tunjangan ?? '[]', true);
        $this->potongan = json_decode($payroll->potongan ?? '[]', true);

        // Potongan izin/terlambat
        // dd($payroll);
        $this->hitungPotonganIzinTerlambat();

        // BPJS
        $this->bpjs = $payroll->bpjs;
        $this->bpjs_jht = $payroll->bpjs_jht;
        $this->bpjs_perusahaan = $payroll->bpjs_perusahaan;
        $this->bpjs_jht_perusahaan = $payroll->bpjs_jht_perusahaan;

        // Checklist otomatis aktif jika nilai > 0
        $this->bpjs_digunakan = !empty($this->bpjs) && $this->bpjs > 0;
        $this->bpjs_jht_digunakan = !empty($this->bpjs_jht) && $this->bpjs_jht > 0;
        $this->bpjs_perusahaan_digunakan = !empty($this->bpjs_perusahaan) && $this->bpjs_perusahaan > 0;
        $this->bpjs_jht_perusahaan_digunakan = !empty($this->bpjs_jht_perusahaan) && $this->bpjs_jht_perusahaan > 0;

        // Opsional: set nominal awal
        $this->bpjs_nominal = $this->bpjs ?? 0;
        $this->bpjs_jht_nominal = $this->bpjs_jht ?? 0;
        $this->bpjs_perusahaan_nominal = $this->bpjs_perusahaan ?? 0;
        $this->bpjs_jht_perusahaan_nominal = $this->bpjs_jht_perusahaan ?? 0;

        // Rekap kehadiran
        $this->hitungRekap($this->karyawan->id);

        $this->hitungInovationReward();
        // $this->inovation_reward_jumlah = (int) $this->kehadiran;

        $lembur = M_Lembur::where('karyawan_id', $this->karyawan->id)
            ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->orderBy('tanggal')
            ->get();
        // dd($lembur);
        // Reset
        $this->lembur = 0;
        $this->lembur_libur = 0;
        $this->listLemburBiasa = [];
        $this->listLemburLibur = [];

        foreach ($lembur as $l) {
            $jamLembur = $l->total_jam;
            $jenisLembur = $l->jenis; // 1 = biasa, 2 = libur

            if ($jenisLembur == 2) {
                $this->lembur_libur += round((1 / 173) * ($this->gaji_pokok + $this->tunjangan_jabatan) * $jamLembur * 2);
                $this->listLemburLibur[] = [
                    'tanggal'     => $l->tanggal,
                    'waktu_mulai' => $l->waktu_mulai,
                    'waktu_akhir' => $l->waktu_akhir,
                    'jam'         => $jamLembur,
                ];
            } else {
                $this->lembur += round((1 / 173) * ($this->gaji_pokok + $this->tunjangan_jabatan) * $jamLembur);
                $this->listLemburBiasa[] = [
                    'tanggal'     => $l->tanggal,
                    'waktu_mulai' => $l->waktu_mulai,
                    'waktu_akhir' => $l->waktu_akhir,
                    'jam'         => $jamLembur,
                ];
            }
        }
    }

    public function hitungPotonganIzinTerlambat()
    {
        $this->hitungRekap($this->karyawan->id);
        $potonganIzin = 0;
        $potonganTerlambat = 0;

        if ($this->gaji_pokok > 0 || $this->tunjangan_jabatan > 0) {
            $perHari = ($this->gaji_pokok + $this->tunjangan_jabatan) / 26;
            $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0);
            $potonganIzin = round($perHari * $totalHariIzin);
            $potonganTerlambat = ($this->rekap['terlambat'] ?? 0) * 25000;
            // dd($potonganTerlambat);
        }
        $this->izin_nominal = $potonganIzin; // misal 50rb per izin
        $this->terlambat_nominal = $potonganTerlambat; // misal 20rb per terlambat

        $this->hitungTotalGaji();
    }

    public function hitungUangMakanTransport()
    {
        if ($this->uang_makan > 0 && $this->uang_makan_jumlah > 0) {
            $this->uang_makan_total = (float)$this->uang_makan * (float)$this->uang_makan_jumlah;
        }

        if ($this->transport > 0 && $this->transport_jumlah > 0) {
            $this->transport_total = (float)$this->transport * (float)$this->transport_jumlah;
        }
    }

    public function isSalesPosition()
    {
        return in_array(strtolower($this->divisi), ['sales', 'sm', 'sales marketing']);
    }

    public function isSalesPositionSpv()
    {
        // dd($this->karyawan);
        if ($this->karyawan) {
            return $this->level === 'SPV'
                && $this->jabatan === 'Sales Marketing'
                && $this->entitas === 'UNR';
        }
    }

    public function isSalesPositionSpvUGR()
    {
        // dd($this->karyawan);
        if ($this->karyawan) {
            return $this->level === 'SPV'
                && $this->jabatan === ['Sales Marketing', 'Sales & Marketing']
                && $this->entitas === 'UGR';
                // dd($this->entitas);
        }
    }

    public function updatedJmlPsb()
    {
        if ($this->isSalesPosition() || $this->isCollectorPosition()) {
            $insentifMapping = [
                1 => [1000000, 50000],
                2 => [1000000, 100000],
                3 => [1000000, 150000],
                4 => [1000000, 200000],
                5 => [1000000, 250000],
                6 => [1160000, 300000],
                7 => [1160000, 350000],
                8 => [1160000, 400000],
                9 => [1160000, 450000],
                10 => [1160000, 500000],
                11 => [1508000, 825000],
                12 => [1508000, 900000],
                13 => [1508000, 975000],
                14 => [1508000, 1050000],
                15 => [1508000, 1125000],
                16 => [1508000, 1200000],
                17 => [1508000, 1275000],
                18 => [1508000, 1350000],
                19 => [1508000, 1425000],
                20 => [2320000, 1700000],
                21 => [2320000, 1785000],
                22 => [2320000, 1870000],
                23 => [2320000, 1955000],
                24 => [2320000, 2040000],
                25 => [2320000, 2125000],
                26 => [2320000, 2210000],
                27 => [2320000, 2295000],
                28 => [2320000, 2380000],
                29 => [2320000, 2465000],
                30 => [2320000, 2550000],
            ];

            if (isset($insentifMapping[$this->jml_psb])) {
                [$upah, $insentif] = $insentifMapping[$this->jml_psb];

                // gaji pokok = 75% dari upah, tunjangan jabatan = 25% dari upah
                $this->gaji_pokok = round($upah * 0.75);
                $this->tunjangan_jabatan = $upah * 0.25; 
                $this->insentif = $insentif;

                // dd('gaji:'. $this->gaji_pokok, 'tunjangan:'. $this->tunjangan_jabatan, 'insentif:'.$this->insentif);

                $this->hitungTotalGaji();
            } else {
                $this->insentif = 0;
            }
        }
    }

    public function updatedJmlPsbSpv()
    {
        if ($this->isSalesPositionSpv()) {
            $this->insentif_spv = 10000 * ((int) ($this->jml_psb_spv ?? 0));
            $this->hitungTotalGaji();
        }
    }

    public function updatedJmlPsbSpvUGR()
    {
        if ($this->isSalesPositionSpvUGR()) {
            $this->insentif_spv_ugr = 50000 * ((int) ($this->jml_psb_spv_ugr ?? 0));
            $this->hitungTotalGaji();
        }
    }

    public function updatedBpjsDigunakan()
    {
        $this->hitungTotalGaji();
    }

    public function updatedBpjsJhtDigunakan()
    {
        $this->hitungTotalGaji();
    }

    public function updatedBpjsPerusahaanDigunakan()
    {
        $gajiPokok = $this->gaji_pokok + $this->tunjangan_jabatan;
        $umk = 2470800;

        if ($this->bpjs_perusahaan_digunakan) {
            // $gajiPokok = $this->gaji_pokok ?? 0;

            // Hitung 4%
            if ($gajiPokok > $umk) {
                $this->bpjs_perusahaan_nominal = round($gajiPokok * 0.04);
            } else {
                $this->bpjs_perusahaan_nominal = round($umk * 0.04);
            }
        } else {
            $this->bpjs_perusahaan_nominal = 0;
        }
    }

    public function updatedBpjsJhtPerusahaanDigunakan()
    {
        $gajiPokok = $this->gaji_pokok + $this->tunjangan_jabatan;
        $umk = 2470800;

        if ($this->bpjs_jht_perusahaan_digunakan) {
            // $gajiPokok = $this->gaji_pokok ?? 0;

            // Hitung 4%
            if ($gajiPokok > $umk) {
                $this->bpjs_jht_perusahaan_nominal = round($gajiPokok * 0.0424);
            } else {
                $this->bpjs_jht_perusahaan_nominal = round($umk * 0.0424);
            }
        } else {
            $this->bpjs_jht_perusahaan_nominal = 0;
        }
    }

    private function numericValue($value)
    {
        return is_numeric($value) ? (int) $value : (int) str_replace(['.', ','], '', $value);
    }

    public function hitungInovationReward()
    {
        $userId = $this->karyawan->id ?? null;
        // dd($userId);
        if (!$this->karyawan || !$this->karyawan->inov_reward) {
            $this->inovation_reward = 0;
            $this->inovation_reward_jumlah = 0;
            $this->inovation_reward_total = 0;
            return;
        }

        // Simpan nilai inov_reward bulanan dari database
        $this->inovation_reward = (float) $this->karyawan->inov_reward;

        // Hitung jumlah kehadiran bulan berjalan
        $jadwal = M_Jadwal::where('karyawan_id', $userId)
            ->where('bulan_tahun', $this->bulanTahun)
            ->first();

        $izin = 0;
        $cuti = 0;

        if ($jadwal) {
            for ($i = 1; $i <= 31; $i++) {
                $col = 'd'.$i;
                if (isset($jadwal->$col)) {
                    if ($jadwal->$col == 2) {
                        $cuti++;
                    } elseif ($jadwal->$col == 3) {
                        $izin++;
                    }
                }
            }
        }

        // total kehadiran fix 26 hari - (izin + cuti)
        $kehadiran = 26 - ($izin + $cuti);
        // dd($kehadiran);

        $this->rekap['kehadiran'] = $kehadiran;

        // hitung inov reward
        $inovRewardPerHari = $this->inovation_reward / 26;
        $this->inovation_reward_jumlah = $kehadiran;
        $this->inovation_reward = round($inovRewardPerHari * $kehadiran);
    }

    public function updated($propertyName, $id = null)
    {
        // Convert nilai numeric
        $gaji = $this->numericValue($this->gaji_pokok);
        $tunjangan = $this->numericValue($this->tunjangan_jabatan);
        
        // $this->hitungInovationReward();

        if (in_array($propertyName, ['transport', 'transport_jumlah'])) {
            $this->transport_total = (float)$this->transport * (float)$this->transport_jumlah;
        }

        if (in_array($propertyName, ['uang_makan', 'uang_makan_jumlah'])) {
            $this->uang_makan_total = (float)$this->uang_makan * (float)$this->uang_makan_jumlah;
        }

        // Hitung otomatis kalau dua komponen utama sudah > 0
        if ($gaji > 0 || $tunjangan > 0) {
            $this->hitungTotalGaji();
        }

        // Jika properti lain yang berubah
        if (
            in_array($propertyName, [
                'bpjs_digunakan',
                'persentase_bpjs',
                'bpjs_jht_digunakan',
                'persentase_bpjs_jht',
            ]) ||
            str_starts_with($propertyName, 'tunjangan.') ||
            str_starts_with($propertyName, 'potongan.')
        ) {
            $this->hitungTotalGaji();
        }
    }

    public function updatedFeeSharingDigunakan()
    {
        $this->hitungTotalGaji();
    }

    public function hitungTotalGaji()
    {
        // === 1. Ambil dan normalisasi input ===
        $gajiPokok         = $this->numericValue($this->gaji_pokok);
        $tunjanganJabatan  = $this->numericValue($this->tunjangan_jabatan);
        $transport         = $this->numericValue($this->transport_total ?? 0);
        $uangMakan         = $this->numericValue($this->uang_makan_total ?? 0);
        $inovationReward   = $this->numericValue($this->inovation_reward ?? 0);
        $kebudayaan        = $this->numericValue($this->kebudayaan ?? 0);
        $feeSharing        = $this->numericValue($this->fee_sharing ?? 0);
        $insentif          = $this->numericValue($this->insentif ?? 0);
        $insentifSpv       = $this->numericValue($this->insentif_spv ?? 0);
        $lemburLiburNominal= $this->numericValue($this->lembur_libur ?? 0);
        $lemburNominal     = $this->numericValue($this->lembur ?? 0);
        $kasbon            = $this->numericValue($this->kasbon ?? 0);
        $churn            = $this->numericValue($this->churn ?? 0);

        // === 2. Tunjangan kehadiran (0 jika ada keterlambatan) ===
        $tunjanganKehadiran = 0;
        if (($this->rekap['terlambat'] ?? 0) == 0) {
            $tunjanganKehadiran = $this->numericValue($this->tunjangan_kehadiran);
        }

        // === 3. Hitung total tunjangan tambahan (manual input) ===
        $totalTunjangan = 0;
        foreach ($this->tunjangan as $item) {
            $totalTunjangan += $this->numericValue($item['nominal']);
        }

        // === 4. Hitung potongan manual ===
        $totalPotonganManual = 0;
        foreach ($this->potongan as $item) {
            $totalPotonganManual += $this->numericValue($item['nominal']);
        }

        // === 6. Hitung lembur ===

        // === 7. Hitung BPJS ===
        $dasarBpjs = $gajiPokok + $tunjanganJabatan;
        $umk = 2470800;

        if ($dasarBpjs < $umk) {
            $nilaiDasarBpjs = $umk;
        } elseif($dasarBpjs > $umk) {
            $nilaiDasarBpjs = $dasarBpjs;
        }

        $bpjsNominal = $this->bpjs_digunakan
            ? ($nilaiDasarBpjs * $this->persentase_bpjs / 100)
            : 0;

        $bpjsJhtNominal = $this->bpjs_jht_digunakan
            ? ($nilaiDasarBpjs * $this->persentase_bpjs_jht / 100)
            : 0;

        $this->bpjs_nominal = round($bpjsNominal);
        $this->bpjs_jht_nominal = round($bpjsJhtNominal);

        // === 8. Hitung total gaji akhir ===
        $this->total_gaji = round(
            $gajiPokok
            + $tunjanganJabatan
            + $totalTunjangan
            + $lemburNominal
            + $lemburLiburNominal
            + $insentif
            + $insentifSpv
            + $tunjanganKehadiran
            + $kebudayaan
            + $feeSharing
            + $transport
            + $uangMakan
            + $inovationReward
            - $totalPotonganManual
            - $this->izin_nominal
            - $this->terlambat_nominal
            - $this->bpjs_nominal
            - $kasbon
            - $churn
            - $this->bpjs_jht_nominal
        );
        // dd($this->total_gaji);
    }

    public function addTunjangan()
    {
        $this->tunjangan[] = ['nama' => '', 'nominal' => 0];
        $this->tunjangan_terpilih = array_column($this->tunjangan, 'nama');
    }

    public function updatedTunjangan($value, $key)
    {
        [$index, $property] = explode('.', $key);

        if ($property === 'nama') {
            $namaDipilih = $value;

            // Isi nominal otomatis jika "Tunjangan Kehadiran"
            if ($namaDipilih === 'Tunjangan Kehadiran') {
                $tunjangan = JenisTunjanganModel::where('nama_tunjangan', $namaDipilih)->first();
                $tunjanganKehadiran = 0;
                if (($this->rekap['terlambat'] ?? 0) == 0) {
                    $tunjanganKehadiran = ($this->rekap['kehadiran'] ?? 0) * $tunjangan->deskripsi;
                }
                $this->tunjangan[$index]['nominal'] = $tunjanganKehadiran;
            } else {
                // Default ambil dari kolom deskripsi di DB
                $tunjangan = JenisTunjanganModel::where('nama_tunjangan', $namaDipilih)->first();
                if ($tunjangan && is_numeric($tunjangan->deskripsi)) {
                    $this->tunjangan[$index]['nominal'] = (int) $tunjangan->deskripsi;
                }
            }

            // Perbarui daftar terpilih
            $this->tunjangan_terpilih = array_column($this->tunjangan, 'nama');
        }

        $this->hitungTotalGaji();
    }

    public function removeTunjangan($index)
    {
        unset($this->tunjangan[$index]);
        $this->tunjangan = array_values($this->tunjangan);
        $this->tunjangan_terpilih = array_column($this->tunjangan, 'nama');
        $this->hitungTotalGaji();
    }

    public function addPotongan()
    {
        $this->potongan[] = ['nama' => '', 'nominal' => 0];

    }

    public function updatedPotongan($value, $key)
    {
        [$index, $property] = explode('.', $key);

        if ($property === 'nama') {
            $namaDipilih = $value;

            // if ($namaDipilih === 'Voucher') {
            //     $voucher = 100000;
            //     $this->potongan[$index]['nominal'] = $voucher;
            // }elseif($namaDipilih === 'Potongan Kebudayaan') {
            //     $potonganKebudayaan = 100000;
            //     $this->potongan[$index]['nominal'] = $potonganKebudayaan;
            // }else {
            //     $potongan = JenisPotonganModel::where('nama_potongan', $namaDipilih)->first();
            //     if ($potongan) {
            //         $this->potongan[$index]['nominal'] = $potongan->nominal;
            //     }
            // }

            $potongan = JenisPotonganModel::where('nama_potongan', $namaDipilih)->first();
            if ($potongan) {
                $this->potongan[$index]['nominal'] = $potongan->deskripsi; // ambil dari kolom deskripsi
            }

            // Perbarui daftar terpilih
            $this->tunjangan_terpilih = array_column($this->tunjangan, 'nama');
        }

        $this->hitungTotalGaji();
    }

    public function removePotongan($index)
    {
        unset($this->potongan[$index]);
        $this->potongan = array_values($this->potongan);
        $this->hitungTotalGaji();
    }

    public function saveEdit()
    {
        // dd($this->id);

        $payroll = PayrollModel::findOrFail(decrypt($this->id));
        // dd($payroll);
        $data = [
            'no_slip' => $this->no_slip,
            'karyawan_id' => $this->karyawan->id,
            'periode' => $this->periode,
            'nip_karyawan' => $this->nip_karyawan,
            'divisi' => $this->divisi,
            'gaji_pokok' => $this->gaji_pokok,
            'tunjangan_jabatan' => $this->tunjangan_jabatan,
            'lembur' => $this->lembur,
            'lembur_libur' => $this->lembur_libur,
            'uang_makan' => $this->uang_makan_total,
            'jml_uang_makan' => $this->uang_makan_jumlah,
            'transport' => $this->transport_total,
            'jml_transport' => $this->transport_jumlah,
            'tunjangan_kebudayaan' => $this->kebudayaan,
            'inov_reward' => $this->inovation_reward,
            'fee_sharing' => $this->fee_sharing,
            'jml_psb' => $this->jml_psb,
            'rekap' => json_encode($this->rekap),
            'insentif' => $this->insentif,
            'izin' => $this->izin_nominal,
            'terlambat' => $this->terlambat_nominal,
            'tunjangan' => json_encode($this->tunjangan),
            'potongan' => json_encode($this->potongan),
            'churn' => $this->churn,
            'kasbon' => $this->kasbon,
            'bpjs' => $this->bpjs_nominal,
            'bpjs_perusahaan' => $this->bpjs_perusahaan_nominal,
            'bpjs_jht' => $this->bpjs_jht_nominal,
            'bpjs_jht_perusahaan' => $this->bpjs_jht_perusahaan_nominal,
            'total_gaji' => $this->total_gaji,
        ];
        // dd($data);
        $payroll->update($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        return redirect()->route('payroll');
    }

    public function render()
    {
        return view('livewire.edit-payroll');
    }
}