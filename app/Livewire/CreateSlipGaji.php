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
    public $lembur_nominal = 0;
    public $izin_nominal = 0;
    public $terlambat_nominal = 0;
    public $jml_psb = 0;
    public $insentif = 0;
    public $tunjangan_kehadiran = 0;
    public $transport = 0;
    public $uang_makan = 0;
    public $kebudayaan = 0;
    public $fee_sharing = 0;
    
    public $fee_sharing_digunakan = false;
    public $fee_sharing_nominal = 0;
    public $jml_psb_spv = 0;
    public $insentif_spv = 0;
    public $cutoffStart;
    public $cutoffEnd;
    public $entitasId;
    public $filterCutOff25;
    public $filterCutOffNormal;
    public $cutoffType = 'cutoff_normal';

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
        $this->filterCutOff25 = [
            $cutoffEnd = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 25),
            $cutoffStart = $cutoffEnd->copy()->subMonthNoOverflow()->setDay(26)
        ];

        $startNormal = Carbon::createFromDate($year, $month, 1);
        $endNormal = $startNormal->copy()->endOfMonth();
        $this->filterCutOffNormal = [
            'start' => $startNormal,
            'end' => $endNormal,
        ];
       

        $this->cutoffStart = $this->filterCutOffNormal['start'];
        $this->cutoffEnd = $this->filterCutOffNormal['end'];

        $this->bulanTahun = $this->cutoffEnd->format('Y-m');

        // Set user_id langsung dari $id
        if ($id) {
            try {
                $this->user_id = Crypt::decrypt($id);
                $dataKaryawanId = Crypt::decrypt($id);
                $dataKaryawan = M_DataKaryawan::findOrFail($dataKaryawanId);
                $this->karyawanId = $dataKaryawan->user_id;
                // dd($this->karyawanId);

                $this->loadDataKaryawan($dataKaryawanId);
                $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);
                // dd($this->rekap);
                
                $gajiPokok = $this->numericValue($this->gaji_pokok);
                $tunjanganJabatan = $this->numericValue($this->tunjangan_jabatan);
                $jamLembur = M_Lembur::where('karyawan_id', $dataKaryawanId)
                    ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
                    ->whereNotNull('total_jam')
                    ->where('status', 1)
                    ->sum('total_jam');
                $jenisLembur = M_Lembur::where('karyawan_id', $dataKaryawanId)
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

    public function setCutoffPeriode()
    {
        if ($this->cutoffType === 'cutoff_normal') {
            $this->cutoffStart = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
            $this->cutoffEnd = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();
        } else { // Default cutoff_25
            $this->cutoffEnd = Carbon::create($this->selectedYear, $this->selectedMonth, 25);
            $this->cutoffStart = $this->cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);
        }
        // Tetapkan bulanTahun sesuai tanggal akhir
        $this->bulanTahun = $this->cutoffEnd->format('Y-m');
    }

    public function loadAvailableKaryawanByPeriode()
    {
        $this->setCutoffPeriode();
        // Ambil entitas dari session, jika tidak ada pakai default 'UHO'
        $entitas = session('selected_entitas', 'UHO');

        // Format periode payroll berdasarkan bulan di tanggal 25 (YYYY-MM)
        $periode = $this->bulanTahun;

        return M_DataKaryawan::where('entitas', $entitas)
            ->whereDoesntHave('payrolls', function ($query) use ($periode) {
                $query->where('periode', $periode);
            })
            ->get();
    }

    public function updatedUserId($value)
    {
        if ($this->bulanTahun) {
            $this->loadDataKaryawan($value);
            $this->rekap = $this->hitungRekapPresensi();
            // dd($this->rekap);
            $this->hitungTotalGaji();
        }
    }

    public function updatedBulanTahun($value)
    {
        $this->hitungRekapPresensi();
        $this->hitungTotalGaji();
    }

    public function loadDataKaryawan($id)
    {
        // dd($id);
        $karyawan = M_DataKaryawan::find($id);
        // dd($karyawan);

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

    public function hitungRekapPresensi()
    {
        if (!$this->user_id || !$this->bulanTahun) {
            $this->rekap = [];
            return $this->rekap;
        }

        // Set cutoffStart dan cutoffEnd berdasarkan cutoffType dan bulanTahun
        $this->setCutoffPeriode();

        // Panggil rekapKehadiran dengan tanggal cutoff
        $this->rekap = $this->rekapKehadiran(
            $this->user_id,
            $this->cutoffStart,
            $this->cutoffEnd
        );

        return $this->rekap;
    }

    public function rekapKehadiran($idKaryawan, $cutoffStart, $cutoffEnd)
    {
        $karyawan = M_DataKaryawan::find($idKaryawan);

        $presensiCollection = $karyawan->getPresensi ?? collect();
        $presensi = $presensiCollection->filter(function ($item) use ($cutoffStart, $cutoffEnd) {
            return Carbon::parse($item->tanggal)->between($cutoffStart, $cutoffEnd);
        });

        $terlambat = $presensi->where('status', 1)->count();

        // Ambil bulan dan tahun dari cutoffEnd (bukan dari input manual)
        $bulan = $cutoffEnd->format('m');
        $tahun = $cutoffEnd->format('Y');
        $bulanTahun = $cutoffEnd->format('Y-m');

        $jadwal = M_Jadwal::where('karyawan_id', $idKaryawan)
            ->where('bulan_tahun', $bulanTahun)
            ->first();

        $izin = 0;
        $cuti = 0;
        $izinSetengahHari = 0;

        foreach (range(1, 31) as $i) {
            $kode = $jadwal->{'d' . $i};

            $tanggal = Carbon::createFromFormat('Y-m-d', "{$tahun}-{$bulan}-" . str_pad($i, 2, '0', STR_PAD_LEFT));

            if (!$tanggal->between($cutoffStart, $cutoffEnd)) {
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

        $dataLembur = M_Lembur::where('karyawan_id', $idKaryawan)
            ->whereBetween('tanggal', [$cutoffStart, $cutoffEnd])
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
            'cutoff_start' => $cutoffStart->format('Y-m-d'),
            'cutoff_end' => $cutoffEnd->format('Y-m-d'),
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

    public function hitungTotalGaji()
    {
        // === 1. Ambil dan normalisasi input ===
        $gajiPokok         = $this->numericValue($this->gaji_pokok);
        $tunjanganJabatan  = $this->numericValue($this->tunjangan_jabatan);
        $transport         = $this->numericValue($this->transport ?? 0);
        $uangMakan         = $this->numericValue($this->uang_makan ?? 0);
        $kebudayaan        = $this->numericValue($this->kebudayaan ?? 0);
        $feeSharing        = $this->numericValue($this->fee_sharing ?? 0);
        $insentif          = $this->numericValue($this->insentif ?? 0);
        $insentifSpv       = $this->numericValue($this->insentif_spv ?? 0);

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

        // === 5. Potongan otomatis ===
        $potonganIzin = 0;
        $potonganTerlambat = 0;

        if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
            $perHari = ($gajiPokok + $tunjanganJabatan) / 26;
            $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0);
            $potonganIzin = round($perHari * $totalHariIzin);
            $potonganTerlambat = ($this->rekap['terlambat'] ?? 0) > 0 ? 25000 : 0;
        }

        // === 6. Hitung lembur ===
        $jamLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
            ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->sum('total_jam');

        $lemburNominal = 0;
        if ($jamLembur > 0 && ($gajiPokok > 0 || $tunjanganJabatan > 0)) {
            $jenisLembur = M_Lembur::where('karyawan_id', $this->karyawanId)
                ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$this->bulanTahun])
                ->whereNotNull('total_jam')
                ->where('status', 1)
                ->orderByDesc('tanggal')
                ->value('jenis');

            $faktor = ($jenisLembur == 2) ? 2 : 1;
            $lemburNominal = (1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur * $faktor;
        }

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
            + $insentif
            + $insentifSpv
            + $tunjanganKehadiran
            + $kebudayaan
            + $feeSharing
            + $transport
            + $uangMakan
            - $totalPotonganManual
            - $potonganIzin
            - $potonganTerlambat
            - $this->bpjs_nominal
            - $this->bpjs_jht_nominal
        );
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
            'fee_sharing' => $this->numericValue($this->fee_sharing),
            'insentif' => $this->numericValue($this->insentif),
            'jml_psb' => $this->jml_psb,
            'rekap' => json_encode($this->rekap),
            'total_gaji' => (int) $this->total_gaji,
            'periode' => $this->bulanTahun,
            'bpjs_perusahaan' => $this->bpjs_perusahaan_nominal,
            'bpjs_jht_perusahaan' => $this->bpjs_jht_perusahaan_nominal,
            'tunjangan_kebudayaan' => $this->numericValue($this->kebudayaan),
        ];
        dd($data);

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