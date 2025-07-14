<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\PayrollModel;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Carbon;
use App\Models\JenisPotonganModel;
use Illuminate\Support\Facades\DB;
use App\Models\JenisTunjanganModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Livewire\Karyawan\JadwalShift;
use Illuminate\Contracts\Encryption\DecryptException;

class CreateSlipGaji extends Component
{
    public $karyawan;
    public $divisi;
    public $jabatan;
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
        'izin setengah hari' => 0,
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
    public $lembur_nominal = 0;
    public $izin_nominal = 0;
    public $terlambat_nominal = 0;
    public $jml_psb = 0;
    public $insentif = 0;
    public $tunjangan_kehadiran = 0;
    public $transport = 0;
    public $uang_makan = 0;
    
    public $fee_sharing_digunakan = false;
    public $fee_sharing_nominal = 0;
    public $jml_psb_spv = 0;
    public $insentif_spv = 0;
    public $cutoffStart;
    public $cutoffEnd;
    public $entitasId;

    public function mount($id = null, $month = null, $year = null)
    {
        if (Auth::user()?->current_role !== 'admin') {
            // Bisa redirect atau abort
            return redirect()->route('dashboard');
            // abort(403, 'Access Denied');
        }
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }
        // $this->karyawan = M_DataKaryawan::all(); // semua karyawan untuk dropdown
            
        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();

        $this->selectedMonth = $month ?? now()->subMonth()->format('n');
        $this->selectedYear = $year ?? now()->year;
        $this->karyawan = $this->loadAvailableKaryawanByPeriode();
        // dd($this->karyawan);

        // $this->bulanTahun = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);

        // Hitung cut-off: dari tanggal 26 bulan sebelumnya sampai 25 bulan yang dipilih
        $cutoffEnd = \Carbon\Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        // Simpan sebagai property jika mau dipakai di view atau hitungan lain
        $this->cutoffStart = $cutoffStart;
        $this->cutoffEnd = $cutoffEnd;

        // Tetap simpan bulanTahun sebagai penanda periode gaji
        $this->bulanTahun = $cutoffEnd->format('Y-m');

        // Set user_id langsung dari $id
        if ($id) {
            try {
                $this->user_id = Crypt::decrypt($id);
                $dataKaryawanId = Crypt::decrypt($id);
                $dataKaryawan = M_DataKaryawan::findOrFail($dataKaryawanId);
                $this->karyawanId = $dataKaryawan->user_id;
                // dd($this->karyawanId);

                $this->loadDataKaryawan($dataKaryawanId); // load data karyawan jika ID valid
                $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
                // dd($this->rekap);
                
                // Hitung nominal lembur dan simpan ke property jika ingin ditampilkan di view
                $gajiPokok = $this->numericValue($this->gaji_pokok);
                $tunjanganJabatan = $this->numericValue($this->tunjangan_jabatan);
                $jamLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
                    ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
                    ->whereNotNull('total_jam')
                    ->where('status', 1)
                    ->sum('total_jam');
                $jenisLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
                    ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
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
                    $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0);
                    $this->izin_nominal = round($perHari * $totalHariIzin);
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

    // public function loadAvailableKaryawanByPeriode()
    // {
    //     $bulanFormatted = str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
    //     $periode = $this->selectedYear . '-' . $bulanFormatted;

    //     return M_DataKaryawan::whereDoesntHave('payrolls', function ($query) use ($periode) {
    //         $query->where('periode', $periode);
    //     })->get();
    // }

    public function loadAvailableKaryawanByPeriode()
    {
        // Hitung cut-off akhir (tanggal 25 bulan terpilih)
        $cutoffEnd = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25);
        
        // Format periode payroll berdasarkan bulan di tanggal 25 (YYYY-MM)
        $periode = $cutoffEnd->format('Y-m');

        return M_DataKaryawan::whereDoesntHave('payrolls', function ($query) use ($periode) {
            $query->where('periode', $periode);
        })->get();
    }


    public function updatedUserId($value)
    {
         // jika perlu
        // $this->hitungRekapPresensi();
        // $this->hitungTotalGaji();

        if ($this->bulanTahun) {
            $this->loadDataKaryawan($value);
            $this->rekap = $this->hitungRekapPresensi();
            dd($this->rekap);
            $this->hitungTotalGaji();
        }
    }

    public function updatedBulanTahun($value)
    {
        
        $this->hitungRekapPresensi();
        $this->hitungTotalGaji();
    }

    public function loadDataKaryawan($dataKaryawanId)
    {
        $karyawan = M_DataKaryawan::find($dataKaryawanId);
        if ($karyawan) {
            $this->divisi = $karyawan->divisi;
            $this->jabatan = $karyawan->jabatan;
            $this->gaji_pokok = $karyawan->gaji_pokok;
            $this->nip_karyawan = $karyawan->nip_karyawan;
            $this->tunjangan_jabatan = $karyawan->tunjangan_jabatan;
        } else {
            $this->divisi = '';
            $this->jabatan = '';
            $this->gaji_pokok = '';
            $this->nip_karyawan = '';
            $this->tunjangan_jabatan = '';
        }
    }

    public function isSalesPosition()
    {
        return in_array(strtolower($this->jabatan), ['sales', 'sm', 'sales marketing']);
    }
    public function isSalesPositionSpv()
    {
        return in_array(strtolower($this->jabatan), ['spv sales marketing', 'spv sales']);
    }

    public function updatedJmlPsb()
    {
        if ($this->isSalesPosition()) {
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
                $this->tunjangan_jabatan = round($upah * 0.25);
                $this->insentif = $insentif;

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


    public function hitungRekapPresensi()
    {
        if (!$this->user_id || !$this->bulanTahun) {
            $this->rekap = [];
            return $this->rekap;
        }

        [$tahun, $bulan] = explode('-', $this->bulanTahun);

        $cutoffEnd = Carbon::createFromDate($tahun, $bulan, 25);
        $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);

        $this->cutoffStart = $cutoffStart;
        $this->cutoffEnd = $cutoffEnd;

        $this->rekap = $this->rekapKehadiran($this->user_id, $bulan, $tahun);
        // dd($this->rekap);
        return $this->rekap;
    }

    // public function rekapKehadiran($idKaryawan, $bulan, $tahun)
    // {
    //     $karyawan = M_DataKaryawan::with(['getPresensi', 'getJadwal'])->findOrFail($idKaryawan);
    //     $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
    //     $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

    //     $presensiCollection = $karyawan->getPresensi ?? collect();
    //     $presensi = $presensiCollection->filter(function ($item) use ($startDate, $endDate) {
    //         return Carbon::parse($item->tanggal)->between($startDate, $endDate);
    //     });

    //     // $kehadiran = $presensi->count();
    //     $terlambat = $presensi->where('status', 1)->count();

    //     $jadwal = M_Jadwal::where('karyawan_id', $idKaryawan)
    //         ->where('bulan_tahun', $this->bulanTahun)
    //         ->first();
    //         // dd($jadwal);

    //     // Sekarang $nama_shift berisi nama shift dari kode shift (misal 3) pada hari ke-1
    //     // dd($jadwalShift);
        
    //     $izin = 0;
    //     $cuti = 0;
    //     $izinSetengahHari = 0;

    //     if ($jadwal) {
    //         foreach (range(1, 31) as $i) {
    //             $kode = $jadwal->{'d' . $i};

    //             if ($kode == 3) {
    //                 $izin++;
    //             } elseif ($kode == 2) {
    //                 $cuti++;
    //             } elseif ($kode == 8) {
    //                 $izinSetengahHari++;
    //             }
    //         }
    //     }

    //     // Ambil daftar lembur
    //     $dataLembur = M_Lembur::where('user_id', $karyawan->user_id)
    //         ->whereBetween('tanggal', [$startDate, $endDate])
    //         ->whereNotNull('total_jam')
    //         ->where('status', 1)
    //         ->get(['tanggal', 'total_jam']);


    //     $totalJamLembur = $dataLembur->sum('total_jam');

    //     return [
    //         'kehadiran' => 26 - $izin - $cuti - (0.5 * $izinSetengahHari),
    //         'terlambat' => $terlambat,
    //         'izin' => $izin,
    //         'cuti' => $cuti,
    //         'lembur' => $totalJamLembur,
    //         'izin setengah hari' => $izinSetengahHari,
    //     ];
    // }

    public function rekapKehadiran($idKaryawan, $bulan, $tahun)
    {
        // dd(M_DataKaryawan::where('user_id', $this->karyawanId)->exists());
        $karyawan = M_DataKaryawan::with(['getPresensi', 'getJadwal'])->where('user_id', $this->karyawanId)->firstOrFail();

        // CUT OFF GAJI: dari 26 bulan sebelumnya s/d 25 bulan ini
        $startDate = Carbon::createFromDate($tahun, $bulan, 26)->subMonthNoOverflow();
        $endDate = Carbon::createFromDate($tahun, $bulan, 25);

        $presensiCollection = $karyawan->getPresensi ?? collect();
        $presensi = $presensiCollection->filter(function ($item) use ($startDate, $endDate) {
            return Carbon::parse($item->tanggal)->between($startDate, $endDate);
        });

        $terlambat = $presensi->where('status', 1)->count();

        $bulanTahun = sprintf('%04d-%02d', $tahun, $bulan);
        $jadwal = M_Jadwal::where('karyawan_id', $idKaryawan)
            ->where('bulan_tahun', $bulanTahun)
            ->first();

        $izin = 0;
        $cuti = 0;
        $izinSetengahHari = 0;
        // dd($idKaryawan, $bulanTahun);
        foreach (range(1, 31) as $i) {
            $kode = $jadwal->{'d' . $i};
            // buat tanggal lengkap berdasarkan hari ke-i
            $tanggal = Carbon::createFromFormat('Y-m-d', "{$tahun}-{$bulan}-" . str_pad($i, 2, '0', STR_PAD_LEFT));

            // skip jika tanggal tidak dalam periode cut-off
            if (!$tanggal->between($startDate, $endDate)) {
                continue;
            }

            if ($kode == 3) {
                $izin++;
            } elseif ($kode == 2) {
                $cuti++;
            } elseif ($kode == 8) {
                $izinSetengahHari++;
            }
        }
        // dd($karyawan->user_id, $startDate, $endDate);
        $dataLembur = M_Lembur::where('karyawan_id', $idKaryawan)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->get(['tanggal', 'total_jam']);
        $totalJamLembur = $dataLembur->sum('total_jam');
        return [
            'kehadiran' => 26 - $izin - $cuti - (0.5 * $izinSetengahHari),
            'terlambat' => $terlambat,
            'izin' => $izin,
            'cuti' => $cuti,
            'lembur' => $totalJamLembur,
            'izin setengah hari' => $izinSetengahHari,
            'cutoff_start' => $startDate->format('Y-m-d'),
            'cutoff_end' => $endDate->format('Y-m-d'),
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

    public function updatedFeeSharingDigunakan()
    {
        $this->hitungTotalGaji();
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
        // Tambahan: Uang Transport dan Uang Makan
        $transport = $this->numericValue($this->transport ?? 0);
        $uangMakan = $this->numericValue($this->uang_makan ?? 0);

        // Tunjangan kehadiran: 2000/hari jika tidak terlambat, jika ada terlambat maka hangus (0)
        $tunjanganKehadiran = 0;
        if (($this->rekap['terlambat'] ?? 0) == 0) {
            $tunjanganKehadiran = ($this->rekap['kehadiran'] ?? 0) * 2000;
        }
        // $this->tunjangan_kehadiran = $tunjanganKehadiran;

        if ($this->user_id) {
            $this->rekap['kehadiran'] == 0;
        }

        //gaji pokok jabatan sales 
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
        $potonganIzin2 = 0;

        if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
            $perHari = ($gajiPokok + $tunjanganJabatan) / 26;
        // dd($perHari);
            $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0);
            $potonganIzin = $perHari * $totalHariIzin;
            $potonganTerlambat = $this->rekap['terlambat'] > 0 ? 25000 : 0;
        }

        $jamLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
            ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->sum('total_jam');

        // dd($jamLembur);
        // Hitung nominal lembur
        // Default lemburNominal
        $lemburNominal = 0;

        $jenisLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
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

        $this->fee_sharing_nominal = $this->fee_sharing_digunakan
            ? 100000
            : 0;

        $this->total_gaji = $gajiPokok
            + $tunjanganJabatan
            + $totalTunjangan
            + $lemburNominal
            + $this->insentif
            + $this->insentif_spv
            + $this->tunjangan_kehadiran
            + ($this->fee_sharing_digunakan ? $this->fee_sharing_nominal : 0)
            + $transport
            + $uangMakan
            - $totalPotonganManual
            - $potonganIzin
            - $potonganTerlambat
            - $this->bpjs_nominal
            - $this->bpjs_jht_nominal;
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
                $tunjanganKehadiran = 0;
                if (($this->rekap['terlambat'] ?? 0) == 0) {
                    $tunjanganKehadiran = ($this->rekap['kehadiran'] ?? 0) * 2000;
                }
                $this->tunjangan[$index]['nominal'] = $tunjanganKehadiran;
            }else if ($namaDipilih === 'Tunjangan Kebudayaan') {
                $tunjanganKebudayaan = 100000;
                $this->tunjangan[$index]['nominal'] = $tunjanganKebudayaan;
            }else {
                // default: ambil dari DB jika bukan "Tunjangan Kehadiran"
                $tunjangan = JenisTunjanganModel::where('nama_tunjangan', $namaDipilih)->first();
                if ($tunjangan) {
                    $this->tunjangan[$index]['nominal'] = $tunjangan->nominal;
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

            // Isi nominal otomatis jika "Voucher"
            if ($namaDipilih === 'Voucher') {
                $voucher = 100000;
                $this->potongan[$index]['nominal'] = $voucher;
            }else {
                // default: ambil dari DB jika bukan "Tunjangan Kehadiran"
                $potongan = JenisPotonganModel::where('nama_potongan', $namaDipilih)->first();
                if ($potongan) {
                    $this->potongan[$index]['nominal'] = $potongan->nominal;
                }
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

    protected function messages()
    {
        return [
            'bulanTahun.required' => 'Bulan dan tahun wajib dipilih.',
            'user_id.required' => 'Pilih karyawan terlebih dahulu.',
        ];
    }

    public function generateNoSlip()
    {
        // Ambil entitas dari session
        $entitasNama = session('selected_entitas', 'UHO'); // default UHO jika tidak dipilih

        // Cari entitas dari tabel
        $entitasModel = M_Entitas::where('nama', $entitasNama)->first();
        $entitasId = $entitasModel?->id;

        // Jika tidak ditemukan, fallback ke default UHO
        $entitasKode = $entitasModel?->nama ?? 'UHO';

        // Ambil periode
        $periode = $this->bulanTahun; // format: "2025-06"
        $tahun = Carbon::createFromFormat('Y-m', $periode)->format('y'); // "25"
        $bulanAngka = Carbon::createFromFormat('Y-m', $periode)->format('n'); // "6"
        $bulanRomawi = $this->toRoman($bulanAngka); // "VI"

        // Hitung jumlah slip yang sudah dicetak untuk periode tersebut (per entitas)
        $count = PayrollModel::where('periode', $periode)
            ->when($entitasModel, function ($query) use ($entitasModel) {
                return $query->where('entitas_id', $entitasModel->id);
            })->count() + 1;

        $nomorUrut = str_pad($count, 3, '0', STR_PAD_LEFT);

        // Format slip berdasarkan nama entitas
        switch (strtoupper($entitasKode)) {
            case 'UHO':
                return "DJB/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UNR':
                return "UNR/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UNB':
                return "UNB/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UGR':
                return "UGR/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            default:
                return "{$entitasKode}/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
        }
    }



    public function toRoman($month)
    {
        $romawi = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        return $romawi[$month] ?? $month;
    }

    public function store()
    {
        $entitasNama = session('selected_entitas', 'UHO');
        $entitasModel = M_Entitas::where('nama', $entitasNama)->first();
        $entitasId = $entitasModel?->id;
        $this->validate([
            'bulanTahun' => 'required|date_format:Y-m',
            'user_id' => 'required|exists:data_karyawan,id',
        ]);

        $this->no_slip = $this->generateNoSlip();
        
        $data = [
            'karyawan_id' => $this->user_id,
            'entitas_id' => $entitasId,
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
            'uang_makan' => $this->numericValue($this->uang_makan),
            'transport' => $this->numericValue($this->transport),
            'fee_sharing' => $this->numericValue($this->fee_sharing_nominal),
            'insentif' => $this->numericValue($this->insentif),
            'jml_psb' => $this->jml_psb,
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