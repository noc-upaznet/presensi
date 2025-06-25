<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_Presensi;
use App\Models\PayrollModel;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Carbon;
use App\Models\JenisPotonganModel;
use Illuminate\Support\Facades\DB;
use App\Models\JenisTunjanganModel;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Karyawan\JadwalShift;
use Illuminate\Contracts\Encryption\DecryptException;

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
        'kehadiran' => 26,
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

    public $selectedMonth;
    public $selectedYear;
    public $periode;
    public $karyawanId;
    public $lembur_nominal;
    public $izin_nominal;
    public $terlambat_nominal;

    public function mount($id = null, $month = null, $year = null)
    {
        // $this->karyawan = M_DataKaryawan::all(); // semua karyawan untuk dropdown
            
        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();

        $this->selectedMonth = $month ?? now()->subMonth()->format('n');
        $this->selectedYear = $year ?? now()->year;
        $this->karyawan = $this->loadAvailableKaryawanByPeriode();
        // dd($this->karyawan);

        $this->bulanTahun = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);

        // Set user_id langsung dari $id
        if ($id) {
            try {
                $this->user_id = Crypt::decrypt($id);
                $dataKaryawanId = Crypt::decrypt($id);
                $dataKaryawan = M_DataKaryawan::findOrFail($dataKaryawanId);
                $this->karyawanId = $dataKaryawan->user_id;
                // dd($this->karyawanId);

                $this->loadDataKaryawan(); // load data karyawan jika ID valid
                $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
                // dd($this->rekap);
                
                // Hitung nominal lembur dan simpan ke property jika ingin ditampilkan di view
                $gajiPokok = $this->numericValue($this->gaji_pokok);
                $tunjanganJabatan = $this->numericValue($this->tunjangan_jabatan);
                $jamLembur = M_Lembur::where('user_id', $this->karyawanId)
                    ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
                    ->whereNotNull('total_jam')
                    ->where('status', 1)
                    ->sum('total_jam');
                $jenisLembur = M_Lembur::where('user_id', $this->karyawanId)
                    ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
                    ->whereNotNull('total_jam')
                    ->where('status', 1)
                    ->orderByDesc('tanggal')
                    ->value('jenis');
                $this->lembur_nominal = 0;
                if (($gajiPokok > 0 || $tunjanganJabatan > 0) && $jamLembur > 0) {
                    if ($jenisLembur == 2) {
                    $this->lembur_nominal = round((1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur * 2);
                    } else {
                    $this->lembur_nominal = round((1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur);
                    }
                }
                $this->izin_nominal = 0;
                if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
                    $perHari = ($gajiPokok + $tunjanganJabatan) / 26;
                    $this->izin_nominal = round($perHari * ($this->rekap['izin'] ?? 0));
                }
                $this->terlambat_nominal = 0;
                if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
                    $this->terlambat_nominal = ($this->rekap['terlambat'] ?? 0) > 0 ? 25000 : 0;
                }
            } catch (DecryptException $e) {
                abort(403, 'ID tidak valid');
            }
        }

        $this->no_slip = $this->generateNoSlip();

        $this->hitungTotalGaji();
    }

    public function loadAvailableKaryawanByPeriode()
    {
        $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        $periode = $this->selectedYear . '-' . $bulanFormatted;

        return M_DataKaryawan::whereDoesntHave('payrolls', function ($query) use ($periode) {
            $query->where('periode', $periode);
        })->get();
    }

    public function updatedUserId($value)
    {
        $this->loadDataKaryawan();
        if ($this->bulanTahun) {
            $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
            // dd($this->rekap);
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

        // $kehadiran = $presensi->count();
        $terlambat = $presensi->where('status', 1)->count();

        $jadwal = M_Jadwal::where('user_id', $this->karyawanId)
            ->where('bulan_tahun', $this->bulanTahun)
            ->first();

        // Sekarang $nama_shift berisi nama shift dari kode shift (misal 3) pada hari ke-1
        // dd($jadwalShift);
        
        $izin = 0;
        $cuti = 0;

        if ($jadwal) {
            foreach (range(1, 31) as $i) {
                $kode = $jadwal->{'d' . $i};

                if ($kode == 3) {
                    $izin++;
                } elseif ($kode == 2) {
                    $cuti++;
                }
            }
        }

        // ✅ Ambil daftar lembur
        $dataLembur = M_Lembur::where('user_id', $karyawan->user_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->get(['tanggal', 'total_jam']);


        $totalJamLembur = $dataLembur->sum('total_jam');

        return [
            'kehadiran' => 26 - $izin - $cuti,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'cuti' => $cuti,
            'lembur' => $totalJamLembur,
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

    public function hitungTotalGaji($id = null)
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
        // dd($perHari);

            $potonganIzin = $perHari * ($this->rekap['izin'] ?? 0);
            $potonganTerlambat = $this->rekap['terlambat'] > 0 ? 25000 : 0;
        }

        $jamLembur = M_Lembur::where('user_id', $this->karyawanId)
            ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->sum('total_jam');

        // dd($jamLembur);
        // Hitung nominal lembur
        // Default lemburNominal
        $lemburNominal = 0;

        $jenisLembur = M_Lembur::where('user_id', $this->karyawanId)
            ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->orderByDesc('tanggal')
            ->value('jenis');

        if (($gajiPokok > 0 || $tunjanganJabatan > 0) && $jamLembur > 0) {
            if ($jenisLembur == 2) {
            $lemburNominal = (1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur * 2;
            } else {
            $lemburNominal = (1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur;
            }
        }
        // if (($gajiPokok > 0 || $tunjanganJabatan > 0) && $jamLembur > 0) {
        //     $lemburNominal = (1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur;
        // }
        // dd($lemburNominal);

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
            + $lemburNominal
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
            'bulanTahun.required' => 'Bulan dan tahun wajib dipilih.',
            'user_id.required' => 'Pilih karyawan terlebih dahulu.',
        ];
    }

    public function generateNoSlip()
    {
        // Ambil periode
        $periode = $this->bulanTahun; // format: "2025-06"
        $tahun = \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('y'); // jadi "25"
        $bulanAngka = \Carbon\Carbon::createFromFormat('Y-m', $periode)->format('n'); // jadi 6
        $bulanRomawi = $this->toRoman($bulanAngka); // jadi "VI"

        // Hitung jumlah slip yang sudah dicetak untuk periode tersebut
        $count = \App\Models\PayrollModel::where('periode', $periode)->count() + 1;
        $nomorUrut = str_pad($count, 3, '0', STR_PAD_LEFT); // jadi "006" jika slip ke-6

        return "DJB/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
    }

    public function toRoman($month)
    {
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romawi[$month] ?? $month;
    }

    public function store()
    {
        $this->validate([
            'bulanTahun' => 'required|date_format:Y-m',
            'user_id' => 'required|exists:data_karyawan,id',
        ]);

        $this->no_slip = $this->generateNoSlip();
        
        $data = [
            'karyawan_id' => $this->user_id,
            'nip_karyawan' => $this->nip_karyawan,
            'no_slip' => $this->no_slip,
            'divisi' => $this->divisi,
            'gaji_pokok' => $this->numericValue($this->gaji_pokok),
            'tunjangan_jabatan' => $this->numericValue($this->tunjangan_jabatan),
            'lembur' => $this->numericValue($this->lembur_nominal),
            'izin' => $this->numericValue($this->izin_nominal),
            'terlambat' => $this->numericValue($this->terlambat_nominal),
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

        $this->dispatch(
            'swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully',
            'showConfirmButton' => false,
            'timer' => 1500
        ]);
        return redirect()->route('payroll');
    }

    public function render()
    {
        return view('livewire.create-slip-gaji');
    }
}