<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Presensi;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Carbon;
use App\Models\JenisPotonganModel;
use App\Models\JenisTunjanganModel;

class CreateSlipGaji extends Component
{
    public $karyawan;
    public $divisi;
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
    public $rekap = [
        'terlambat' => 0,
        'izin' => 0,
        'cuti' => 0,
        'kehadiran' => 0,
        'lembur' => 0,
    ];

    public $total_gaji = 0;

    public $bpjs_digunakan = false;
    public $persentase_bpjs = 1;
    public $bpjs_nominal = 0;

    public $bpjs_jht_digunakan = false;
    public $persentase_bpjs_jht = 2;
    public $bpjs_jht_nominal = 0;
    public $no_slip;

    public function mount()
    {
        $this->karyawan = M_DataKaryawan::all();
        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();
        $this->hitungTotalGaji();
    }

    public function updatedKaryawanId($value)
    {
        $this->loadDataKaryawan();
        if ($this->bulanTahun) {
            $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
            $this->hitungTotalGaji();
        }
    }

    public function updatedBulanTahun($value)
    {
        if ($this->user_id) {
            $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
            $this->hitungTotalGaji();
        }
    }

    public function loadDataKaryawan()
    {
        $karyawan = M_DataKaryawan::find($this->user_id);
        if ($karyawan) {
            $this->divisi = $karyawan->getDivisi?->nama;
            $this->gaji_pokok = $karyawan->gaji_pokok;
            $this->nip_karyawan = $karyawan->nip_karyawan;
            $this->tunjangan_jabatan = $karyawan->tunjangan_jabatan;
        } else {
            $this->divisi = '';
            $this->gaji_pokok = '';
            $this->nip_karyawan = '';
            $this->tunjangan_jabatan = '';
        }
    }

    public function hitungRekapPresensi($karyawanId, $bulanTahun)
    {
        [$tahun, $bulan] = explode('-', $bulanTahun);
        return $this->rekapKehadiran($karyawanId, $bulan, $tahun);
    }

    public function rekapKehadiran($idKaryawan, $bulan, $tahun)
    {
        $karyawan = M_DataKaryawan::with(['getPresensi', 'getJadwal'])->findOrFail($idKaryawan);
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $presensiCollection = $karyawan->getPresensi ?? collect();
        $presensi = $presensiCollection->filter(function ($item) use ($startDate, $endDate) {
            return Carbon::parse($item->tanggal)->between($startDate, $endDate);
        });

        $kehadiran = $presensi->count();
        $terlambat = $presensi->where('status', 1)->count();

        $bulanFormat = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $jadwal = $karyawan->getJadwal->where('bulan_tahun', $bulanFormat)->first();

        $izin = 0;
        $cuti = 0;

        if ($jadwal) {
            foreach (range(1, 31) as $i) {
                $kode = $jadwal->{'d' . $i};

                if ($kode == 2) {
                    $izin++;
                } elseif ($kode == 3) {
                    $cuti++;
                }
            }
        }

        $lembur = $presensi->filter(function ($p) use ($jadwal) {
            if (!$jadwal) return false;
            $day = (int) Carbon::parse($p->tanggal)->day;
            return !isset($jadwal->{'d' . $day}) || $jadwal->{'d' . $day} === null;
        })->count();

        return [
            'kehadiran' => $kehadiran,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'cuti' => $cuti,
            'lembur' => $lembur,
        ];
    }

    private function numericValue($value)
    {
        return is_numeric($value) ? (int) $value : (int) str_replace(['.', ','], '', $value);
    }

    public function updated($propertyName)
    {
        // Convert nilai numeric
        $gaji = $this->numericValue($this->gaji_pokok);
        $tunjangan = $this->numericValue($this->tunjangan_jabatan);

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

    public function updatedBpjsDigunakan()
    {
        $this->hitungTotalGaji();
    }

    public function updatedBpjsJhtDigunakan()
    {
        $this->hitungTotalGaji();
    }

    public function hitungTotalGaji()
    {
        $gajiPokok = $this->numericValue($this->gaji_pokok);
        $tunjanganJabatan = $this->numericValue($this->tunjangan_jabatan);

        $totalTunjangan = 0;
        foreach ($this->tunjangan as $item) {
            $totalTunjangan += $this->numericValue($item['nominal']);
        }

        $totalPotonganManual = 0;
        foreach ($this->potongan as $item) {
            $totalPotonganManual += $this->numericValue($item['nominal']);
        }

        // Potongan otomatis
        $potonganIzin = 0;
        $potonganTerlambat = 0;

        if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
            $perHari = ($gajiPokok + $tunjanganJabatan) / 26;
            $potonganIzin = $perHari * ($this->rekap['izin'] ?? 0);
            $potonganTerlambat = 25000;
        }

        // Hitung BPJS
        $dasar_bpjs = $gajiPokok + $tunjanganJabatan;
        $umk = 2470800;

        if ($gajiPokok <= 0 && $tunjanganJabatan <= 0) {
            $this->bpjs_nominal = 0;
            $this->bpjs_jht_nominal = 0;
        } else {
            $nilai_dasar_bpjs = $dasar_bpjs < $umk ? $umk : $dasar_bpjs;

            $this->bpjs_nominal = $this->bpjs_digunakan
                ? ($nilai_dasar_bpjs * $this->persentase_bpjs / 100)
                : 0;

            $this->bpjs_jht_nominal = $this->bpjs_jht_digunakan
                ? ($nilai_dasar_bpjs * $this->persentase_bpjs_jht / 100)
                : 0;
        }

        $this->total_gaji = $gajiPokok
            + $tunjanganJabatan
            + $totalTunjangan
            - $totalPotonganManual
            - $potonganIzin
            - $potonganTerlambat
            - $this->bpjs_nominal
            - $this->bpjs_jht_nominal;
    }


    public function addTunjangan()
    {
        $this->tunjangan[] = ['nama' => '', 'nominal' => 0];
    }

    public function removeTunjangan($index)
    {
        unset($this->tunjangan[$index]);
        $this->tunjangan = array_values($this->tunjangan);
        $this->hitungTotalGaji();
    }

    public function addPotongan()
    {
        $this->potongan[] = ['nama' => '', 'nominal' => 0];
    }

    public function removePotongan($index)
    {
        unset($this->potongan[$index]);
        $this->potongan = array_values($this->potongan);
        $this->hitungTotalGaji();
    }

    protected function messages()
    {
        return [
            'no_slip.required' => 'Nomor slip wajib diisi.',
            'bulanTahun.required' => 'Bulan dan tahun wajib dipilih.',
            'user_id.required' => 'Pilih karyawan terlebih dahulu.',
        ];
    }

    public function store()
    {
        $this->validate([
            'no_slip' => 'required|string|max:50',
            'bulanTahun' => 'required|date_format:Y-m',
            'user_id' => 'required|exists:data_karyawan,id',
        ]);
        
        $data = [
            'karyawan_id' => $this->user_id,
            'nip_karyawan' => $this->nip_karyawan,
            'no_slip' => $this->no_slip,
            'divisi' => $this->divisi,
            'gaji_pokok' => $this->numericValue($this->gaji_pokok),
            'tunjangan_jabatan' => $this->numericValue($this->tunjangan_jabatan),
            'tunjangan' => json_encode($this->tunjangan),
            'potongan' => json_encode($this->potongan),
            'bpjs' => $this->bpjs_nominal,
            'bpjs_jht' => $this->bpjs_jht_nominal,
            'rekap' => json_encode($this->rekap),
            'total_gaji' => (int) $this->total_gaji,
            'periode' => $this->bulanTahun,
        ];
        // dd($data);

        PayrollModel::create($data);



        session()->flash('message', 'Slip gaji berhasil disimpan!');
        return redirect()->route('payroll'); // ganti dengan rute yang kamu pakai
    }

    public function render()
    {
        return view('livewire.create-slip-gaji');
    }
}