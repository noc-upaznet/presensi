<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Lembur;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Carbon;
use App\Models\JenisPotonganModel;
use App\Models\JenisTunjanganModel;
use App\Models\M_Sharing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CreateSlipGaji extends Component
{
    public $karyawan;
    public $divisi;
    public $jabatan;
    public $level;
    public $entitas;
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
    public $id;
    public $rekap = [
        'terlambat' => 0,
        'izin' => 0,
        'cuti' => 0,
        'kehadiran' => 0,
        'lembur' => 0,
        'izin setengah hari' => 0,
        'izin setengah hari pagi' => 0,
        'izin setengah hari siang' => 0,
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
    public $lemburLibur_nominal = 0;
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

    public $uang_makan = 15000;
    public $uang_makan_jumlah;
    public $uang_makan_total = 0;

    public $inovation_reward;
    public $inovation_reward_jumlah;
    public $inovation_reward_total = 0;

    public $fee_sharing_digunakan = false;
    public $fee_sharing_nominal = 0;
    public $jml_psb_spv = 0;
    public $jml_psb_spv_ugr = 0;
    public $insentif_spv = 0;
    public $insentif_spv_ugr = 0;
    public $cutoffStart;
    public $cutoffEnd;
    public $entitasId;
    public $filterCutOff25;
    public $filterCutOffNormal;
    public $cutoffType = 'cutoff_normal';
    public $listLemburBiasa = [];
    public $listLemburLibur = [];
    public $kasbon;
    public $churn = 0;
    public $bpjsKaryawan;

    public function mount($id = null, $month = null, $year = null)
    {
        if ($month && str_contains($month, '-')) {
            [$year, $month] = explode('-', $month);
        }
        // dd($id, $month, $year);
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }

        $this->jenis_tunjangan = JenisTunjanganModel::all();
        $this->jenis_potongan = JenisPotonganModel::all();

        $this->id = $id;
        $this->selectedMonth = $month ?? now()->format('n'); // default ke bulan ini
        $this->selectedYear = $year ?? now()->year;
        $this->karyawan = $this->loadAvailableKaryawanByPeriode();

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
        $this->cutoffStart = $this->filterCutOffNormal['start'];
        $this->cutoffEnd = $this->filterCutOffNormal['end'];

        // Jika ada ID karyawan → proses gaji
        if ($id) {
            try {
                $this->user_id = Crypt::decrypt($id);
                $dataKaryawanId = Crypt::decrypt($id);
                $dataKaryawan = M_DataKaryawan::findOrFail($dataKaryawanId);
                $this->karyawanId = $dataKaryawan->user_id;
                $this->bpjsKaryawan = $dataKaryawan;

                $this->loadDataKaryawan($dataKaryawanId);
                $this->rekap = $this->hitungRekapPresensi($this->user_id, $this->bulanTahun);

                $gajiPokok = $this->numericValue($this->gaji_pokok);
                $tunjanganJabatan = $this->numericValue($this->tunjangan_jabatan);
                // Hitung lembur
                $lembur = M_Lembur::where('karyawan_id', $dataKaryawanId)
                    ->whereBetween('tanggal', [$this->cutoffStart, $this->cutoffEnd])
                    ->whereNotNull('total_jam')
                    ->where('status', 1)
                    ->orderBy('tanggal')
                    ->get();

                // Reset
                $this->lembur_nominal = 0;
                $this->lemburLibur_nominal = 0;
                $this->listLemburBiasa = [];
                $this->listLemburLibur = [];

                foreach ($lembur as $l) {
                    $jamLembur = $l->total_jam;
                    $jenisLembur = $l->jenis; // 1 = biasa, 2 = libur

                    if ($jenisLembur == 2) {
                        $this->lemburLibur_nominal += round((1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur * 2);
                        $this->listLemburLibur[] = [
                            'tanggal' => $l->tanggal,
                            'waktu_mulai' => $l->waktu_mulai,
                            'waktu_akhir' => $l->waktu_akhir,
                            'jam' => $jamLembur,
                        ];
                    } else {
                        $this->lembur_nominal += round((1 / 173) * ($gajiPokok + $tunjanganJabatan) * $jamLembur);
                        $this->listLemburBiasa[] = [
                            'tanggal' => $l->tanggal,
                            'waktu_mulai' => $l->waktu_mulai,
                            'waktu_akhir' => $l->waktu_akhir,
                            'jam' => $jamLembur,
                        ];
                    }
                }

                $this->kasbon = $dataKaryawan->kasbon ?? 0;
                $this->fee_sharing = M_Sharing::where('karyawan_id', $dataKaryawanId)
                    ->whereBetween('date', [$this->cutoffStart, $this->cutoffEnd])
                    ->where('status', 1)
                    ->exists();

                $this->fee_sharing_nominal = $this->fee_sharing ? 100000 : 0;

                $this->inovation_reward_jumlah = (int) $this->rekap['kehadiran'];
                $this->izin_nominal = 0;
                if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
                    $perHari = ($gajiPokok + $tunjanganJabatan) / 26;
                    $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari pagi'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari siang'] ?? 0);
                    $this->izin_nominal = round($perHari * $totalHariIzin);
                }
                $this->terlambat_nominal = 0;
                if ($gajiPokok > 0 || $tunjanganJabatan > 0) {
                    $currentBranch = session('selected_entitas');
                    if ($currentBranch === 'MC') {
                        $this->terlambat_nominal = ($this->rekap['terlambat'] ?? 0) * 15000;
                    } else {
                        $this->terlambat_nominal = ($this->rekap['terlambat'] ?? 0) * 25000;
                    }
                }
            } catch (DecryptException $e) {
                abort(403, 'ID tidak valid');
            }
        }

        if (!empty($this->bpjsKaryawan->no_bpjs)) {
            $this->bpjs_digunakan = true;
        }
        if (!empty($this->bpjsKaryawan->no_bpjs_tk)) {
            $this->bpjs_jht_digunakan = true;
        }

        // $currentBranch = session('selected_entitas', 'UHO');

        $this->tunjangan = [];
        $this->potongan = [];

        $kehadiran = $this->rekap['kehadiran'] ?? 0;
        $terlambat  = $this->rekap['terlambat'] ?? 0;

        $tunjanganKehadiran = JenisTunjanganModel::where('nama_tunjangan', 'Tunjangan Kehadiran')->first();
        $voucher = JenisPotonganModel::where('nama_potongan', 'Voucher')->first();

        if (is_array($currentBranch)) {
            $currentBranch = $currentBranch[0] ?? null;
        }

        if ($currentBranch === 'UGR') {
            $this->kebudayaan = 0;

            if ($tunjanganKehadiran) {
                $nominal = 0;
                if ($terlambat == 0) {
                    $nominal = $kehadiran * (int) $tunjanganKehadiran->deskripsi;
                }

                $this->tunjangan[] = [
                    'nama' => 'Tunjangan Kehadiran',
                    'nominal' => $nominal,
                ];
            }

            $this->potongan[] = [
                'nama' => '',
                'nominal' => 0,
            ];
        } elseif (in_array($currentBranch, ['UHO', 'UNR'])) {
            $this->kebudayaan = 100000;

            if ($tunjanganKehadiran) {
                $nominal = 0;
                if ($terlambat == 0) {
                    $nominal = $kehadiran * (int) $tunjanganKehadiran->deskripsi;
                }

                $this->tunjangan[] = [
                    'nama' => 'Tunjangan Kehadiran',
                    'nominal' => $nominal,
                ];
            }

            if ($voucher) {
                $this->potongan[] = [
                    'nama' => 'Voucher',
                    'nominal' => (int) $voucher->deskripsi,
                ];
            } else {
                $this->potongan[] = [
                    'nama' => 'Voucher',
                    'nominal' => 0,
                ];
            }
        } else {
            $this->kebudayaan = 0;
            $this->tunjangan[] = [
                'nama' => '',
                'nominal' => 0,
            ];
            $this->potongan[] = [
                'nama' => '',
                'nominal' => 0,
            ];
        }

        $this->tunjangan_terpilih = array_column($this->tunjangan, 'nama');
        $this->potongan_terpilih = array_column($this->potongan, 'nama');



        $this->hitungInovationReward();
        $this->no_slip = $this->generateNoSlip();
        $this->hitungTotalGaji();
    }

    public function updatedCutoffType()
    {
        // setiap ganti mode cutoff:
        $this->setCutoffPeriode();
        $this->rekap = $this->hitungRekapPresensi();
        $this->hitungTotalGaji();
    }

    public function setCutoffPeriode()
    {
        if ($this->cutoffType === 'cutoff_normal') {
            $this->cutoffStart = Carbon::create($this->selectedYear, $this->selectedMonth, 1);
            if ($this->selectedYear == now()->year && $this->selectedMonth == now()->month) {
                $this->cutoffEnd = now();
            } else {
                $this->cutoffEnd = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->endOfMonth();
            }
        } else { // cutoff_25
            if ($this->selectedYear == now()->year && $this->selectedMonth == now()->month && now()->day < 25) {
                $this->cutoffEnd = now();
            } else {
                $this->cutoffEnd = Carbon::create($this->selectedYear, $this->selectedMonth, 25);
            }
            $this->cutoffStart = $this->cutoffEnd->copy()->subMonthNoOverflow()->setDay(26);
        }

        $this->bulanTahun = $this->cutoffEnd->format('Y-m');
    }

    public function loadAvailableKaryawanByPeriode()
    {
        $this->setCutoffPeriode();
        $periode = $this->bulanTahun;

        $selectedEntitas = session('selected_entitas', 'UHO');

        return M_DataKaryawan::whereDoesntHave('payrolls', function ($query) use ($periode) {
            $query->where('periode', $periode);
        })
            ->where(function ($query) use ($selectedEntitas) {
                // Tampilkan karyawan yang entitasnya sesuai selectedEntitas
                $query->where('entitas', $selectedEntitas)
                    // Atau karyawan yang entitasnya bukan selectedEntitas
                    ->orWhereNotIn('entitas', (array) $selectedEntitas);
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
        // dd('oke');
        [$year, $month] = explode('-', $value);
        $this->selectedYear = (int) $year;
        $this->selectedMonth = (int) $month;

        $this->setCutoffPeriode();
        $this->rekap = $this->hitungRekapPresensi();
        $this->hitungTotalGaji();

        $this->no_slip = $this->generateNoSlip();
        // dd($this->no_slip);
        $this->karyawan = $this->loadAvailableKaryawanByPeriode();
    }

    public function loadDataKaryawan($id)
    {
        $karyawan = M_DataKaryawan::find($id);
        // dd($karyawan);

        if ($karyawan) {
            $this->divisi = $karyawan->divisi;
            $this->jabatan = $karyawan->jabatan;
            $this->level = $karyawan->level;
            $this->gaji_pokok = $karyawan->gaji_pokok;
            $this->nip_karyawan = $karyawan->nip_karyawan;
            $this->tunjangan_jabatan = $karyawan->tunjangan_jabatan;
            $this->entitas = $karyawan->entitas;
        } else {
            $this->divisi = '';
            $this->jabatan = '';
            $this->level = '';
            $this->gaji_pokok = '';
            $this->nip_karyawan = '';
            $this->tunjangan_jabatan = '';
            $this->entitas = '';
        }
    }

    public function isCollectorPosition()
    {
        return in_array(strtolower($this->divisi), ['collector', 'col', 'cl']);
    }

    public function isSalesPosition()
    {
        if ($this->karyawan) {
            return $this->level === 'Staff'
                && $this->jabatan === 'Sales Marketing';
        }
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
                && $this->jabatan === 'Sales Marketing'
                && $this->entitas === 'UGR';
            // dd($this->entitas);
        }
    }

    public function updatedJmlPsb()
    {
        if (!$this->karyawan) {
            return;
        }

        // Staff Sales / Collector pakai insentif mapping
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

                $this->gaji_pokok = round($upah * 0.75);
                $this->tunjangan_jabatan = $upah * 0.25;
                $this->insentif = $insentif;
            } else {
                $this->insentif = 0;
            }
        }
        // SPV UNR
        elseif ($this->isSalesPositionSpv()) {
            $this->insentif = 10000 * ((int) ($this->jml_psb ?? 0));
        }
        // SPV UGR
        elseif ($this->isSalesPositionSpvUGR()) {
            $this->insentif = 50000 * ((int) ($this->jml_psb ?? 0));
        }

        $this->hitungTotalGaji();
    }

    public function hitungRekapPresensi()
    {
        $rekap = [
            'kehadiran' => 0,
            'terlambat' => 0,
            'izin' => 0,
            'cuti' => 0,
            'lembur' => 0,
            'izin setengah hari' => 0,
            'izin setengah hari pagi' => 0,
            'izin setengah hari siang' => 0,
            'cutoff_start' => null,
            'cutoff_end' => null,
        ];

        if (!$this->user_id || !$this->bulanTahun) {
            $this->rekap = $rekap;
            return $this->rekap;
        }

        // Set cutoffStart dan cutoffEnd
        $this->setCutoffPeriode();

        // Ambil data dari rekapKehadiran
        $data = $this->rekapKehadiran(
            $this->user_id,
            $this->cutoffStart,
            $this->cutoffEnd
        );

        // Gabungkan default + data supaya semua key tetap ada
        $this->rekap = array_merge($rekap, $data);
        // dd($this->rekap);

        return $this->rekap;
    }

    // public function rekapKehadiran($id, $cutoffStart, $cutoffEnd)
    // {
    //     // dd($id);
    //     $karyawan = M_DataKaryawan::find($id);
    //     // dd($karyawan);

    //     $presensiCollection = $karyawan->getPresensi ?? collect();
    //     $presensi = $presensiCollection->filter(function ($item) use ($cutoffStart, $cutoffEnd) {
    //         return Carbon::parse($item->tanggal)->between($cutoffStart, $cutoffEnd);
    //     });
    //     // dd($presensi);
    //     $terlambat = M_Presensi::where('user_id', $id)
    //         ->where('status', 1)
    //         ->whereBetween('tanggal', [$cutoffStart, $cutoffEnd])
    //         ->where(function ($query) {
    //             $query->where('lokasi_lock', 0)->where('approve', 1)
    //                 ->orWhere(function ($q) {
    //                     $q->where('lokasi_lock', 1)->where('approve', 0);
    //                 });
    //         })
    //         ->count();
    //     // dd($terlambat);
    //     // Ambil bulan dan tahun dari cutoffEnd (bukan dari input manual)
    //     $bulan = $cutoffEnd->format('m');
    //     $tahun = $cutoffEnd->format('Y');
    //     $bulanTahun = $cutoffEnd->format('Y-m');
    //     // dd($bulanTahun);
    //     $jadwal = M_Jadwal::where('karyawan_id', $id)
    //         ->where('bulan_tahun', $bulanTahun)
    //         ->first();
    //     // dd($jadwal);
    //     $izin = 0;
    //     $cuti = 0;
    //     $izinSetengahHari = 0;
    //     $izinSetengahHariPagi = 0;
    //     $izinSetengahHariSiang = 0;

    //     foreach (range(1, 31) as $i) {
    //         $kode = $jadwal->{'d' . $i};

    //         $tanggal = Carbon::createFromFormat('Y-m-d', "{$tahun}-{$bulan}-" . str_pad($i, 2, '0', STR_PAD_LEFT));

    //         if (!$tanggal->between($cutoffStart, $cutoffEnd)) {
    //             continue;
    //         }

    //         if ($kode == 3) {
    //             $izin++;
    //         } elseif ($kode == 2) {
    //             $cuti++;
    //         } elseif ($kode == 8) {
    //             $izinSetengahHari++;
    //         } elseif ($kode == 22) {
    //             $izinSetengahHariPagi++;
    //         } elseif ($kode == 23) {
    //             $izinSetengahHariSiang++;
    //         }
    //     }

    //     $dataLembur = M_Lembur::where('karyawan_id', $id)
    //         ->whereBetween('tanggal', [$cutoffStart, $cutoffEnd])
    //         ->whereNotNull('total_jam')
    //         ->where('status', 1)
    //         ->get(['tanggal', 'total_jam']);

    //     $totalJamLembur = $dataLembur->sum('total_jam');

    //     return [
    //         'kehadiran' => 26 - $izin - $cuti - (0.5 * $izinSetengahHari) - (0.5 * $izinSetengahHariPagi) - (0.5 * $izinSetengahHariSiang),
    //         'terlambat' => $terlambat,
    //         'izin' => $izin,
    //         'cuti' => $cuti,
    //         'lembur' => $totalJamLembur,
    //         'izin setengah hari' => $izinSetengahHari,
    //         'izin setengah hari pagi' => $izinSetengahHariPagi,
    //         'izin setengah hari siang' => $izinSetengahHariSiang,
    //         'cutoff_start' => $cutoffStart->format('Y-m-d'),
    //         'cutoff_end' => $cutoffEnd->format('Y-m-d'),
    //     ];
    // }

    public function rekapKehadiran($id, $cutoffStart, $cutoffEnd)
    {
        $cutoffStart = $cutoffStart instanceof Carbon ? $cutoffStart : Carbon::parse($cutoffStart);
        $cutoffEnd   = $cutoffEnd   instanceof Carbon ? $cutoffEnd   : Carbon::parse($cutoffEnd);

        $karyawan = M_DataKaryawan::find($id);

        // (optional) kalau masih butuh presensiCollection
        $presensiCollection = $karyawan->getPresensi ?? collect();
        $presensi = $presensiCollection->filter(function ($item) use ($cutoffStart, $cutoffEnd) {
            return Carbon::parse($item->tanggal)->between($cutoffStart, $cutoffEnd);
        });

        // TERLAMBAT
        $terlambat = M_Presensi::where('user_id', $id)
            ->where('status', 1)
            ->whereBetween('tanggal', [$cutoffStart, $cutoffEnd])
            ->where(function ($query) {
                $query->where('lokasi_lock', 0)->where('approve', 1)
                    ->orWhere(function ($q) {
                        $q->where('lokasi_lock', 1)->where('approve', 0);
                    });
            })
            ->count();

        // ============ JADWAL ============

        // $bulanTahunStart = $cutoffStart->format('Y-m');
        // $bulanTahunEnd   = $cutoffEnd->format('Y-m');

        // $tahunStart = (int) $cutoffStart->format('Y');
        // $bulanStart = (int) $cutoffStart->format('m');

        // $tahunEnd   = (int) $cutoffEnd->format('Y');
        // $bulanEnd   = (int) $cutoffEnd->format('m');

        // $jadwalStart = M_Jadwal::where('karyawan_id', $id)
        //     ->where('bulan_tahun', $bulanTahunStart)
        //     ->first();

        // $jadwalEnd = M_Jadwal::where('karyawan_id', $id)
        //     ->where('bulan_tahun', $bulanTahunEnd)
        //     ->first();

        // $izin = 0;
        // $cuti = 0;
        // $izinSetengahHari = 0;
        // $izinSetengahHariPagi = 0;
        // $izinSetengahHariSiang = 0;

        // // 🔹 CASE 1: periode 1 bulan (mode normal)
        // if ($bulanTahunStart === $bulanTahunEnd) {
        //     $jadwal = $jadwalStart ?? $jadwalEnd;

        //     if ($jadwal) {
        //         $startDay = (int) $cutoffStart->day;   // bisa 1 atau tanggal lain
        //         $endDay   = (int) $cutoffEnd->day;     // bisa akhir bulan / hari ini

        //         foreach (range($startDay, $endDay) as $day) {
        //             $tanggal = Carbon::createFromDate($tahunStart, $bulanStart, $day);

        //             if (!$tanggal->between($cutoffStart, $cutoffEnd)) {
        //                 continue;
        //             }

        //             $kode = $jadwal->{'d' . $day} ?? null;
        //             $this->tambahHitunganKode(
        //                 $kode,
        //                 $izin,
        //                 $cuti,
        //                 $izinSetengahHari,
        //                 $izinSetengahHariPagi,
        //                 $izinSetengahHariSiang
        //             );
        //         }
        //     }
        // }
        // // 🔹 CASE 2: periode lintas bulan (cutoff 26–25)
        // else {
        //     // ---- BULAN AWAL (biasanya 26–akhir bulan) ----
        //     if ($jadwalStart) {
        //         $startDay = (int) $cutoffStart->day;                 // contoh 26
        //         $endDay   = $cutoffStart->copy()->endOfMonth()->day; // 30/31

        //         foreach (range($startDay, $endDay) as $day) {
        //             $tanggal = Carbon::createFromDate($tahunStart, $bulanStart, $day);

        //             if (!$tanggal->between($cutoffStart, $cutoffEnd)) {
        //                 continue;
        //             }

        //             $kode = $jadwalStart->{'d' . $day} ?? null;
        //             $this->tambahHitunganKode(
        //                 $kode,
        //                 $izin,
        //                 $cuti,
        //                 $izinSetengahHari,
        //                 $izinSetengahHariPagi,
        //                 $izinSetengahHariSiang
        //             );
        //         }
        //     }

        //     // ---- BULAN AKHIR (biasanya 1–25) ----
        //     if ($jadwalEnd) {
        //         $startDay = 1;
        //         $endDay   = (int) $cutoffEnd->day; // contoh 25

        //         foreach (range($startDay, $endDay) as $day) {
        //             $tanggal = Carbon::createFromDate($tahunEnd, $bulanEnd, $day);

        //             if (!$tanggal->between($cutoffStart, $cutoffEnd)) {
        //                 continue;
        //             }

        //             $kode = $jadwalEnd->{'d' . $day} ?? null;
        //             $this->tambahHitunganKode(
        //                 $kode,
        //                 $izin,
        //                 $cuti,
        //                 $izinSetengahHari,
        //                 $izinSetengahHariPagi,
        //                 $izinSetengahHariSiang
        //             );
        //         }
        //     }
        // }

        $jadwal = M_Jadwal::where('karyawan_id', $id)
            ->where('bulan_tahun', $this->bulanTahun) // bulanTahun sudah di-set di mount
            ->first();
        // dd($jadwal);
        $izin = 0;
        $cuti = 0;
        $izinSetengahHari = 0;
        $izinSetengahHariPagi = 0;
        $izinSetengahHariSiang = 0;

        if ($jadwal) {
            for ($i = 1; $i <= 31; $i++) {
                $kolom = 'd' . $i;
                $val = $jadwal->$kolom ?? null;

                if ($val == 3) {
                    $izin++;
                } elseif ($val == 2) {
                    $cuti++;
                } elseif ($val == 8) {
                    $izinSetengahHari++;
                } elseif ($val == 22) {
                    $izinSetengahHariPagi++;
                } elseif ($val == 23) {
                    $izinSetengahHariSiang++;
                }
            }
        }

        // ============ LEMBUR ============

        $dataLembur = M_Lembur::where('karyawan_id', $id)
            ->whereBetween('tanggal', [$cutoffStart, $cutoffEnd])
            ->whereNotNull('total_jam')
            ->where('status', 1)
            ->get(['tanggal', 'total_jam']);

        $totalJamLembur = $dataLembur->sum('total_jam');

        return [
            'kehadiran' => 26
                - $izin
                - $cuti
                - (0.5 * $izinSetengahHari)
                - (0.5 * $izinSetengahHariPagi)
                - (0.5 * $izinSetengahHariSiang),

            'terlambat' => $terlambat,
            'izin' => $izin,
            'cuti' => $cuti,
            'lembur' => $totalJamLembur,

            'izin setengah hari' => $izinSetengahHari,
            'izin setengah hari pagi' => $izinSetengahHariPagi,
            'izin setengah hari siang' => $izinSetengahHariSiang,

            'cutoff_start' => $cutoffStart->format('Y-m-d'),
            'cutoff_end'   => $cutoffEnd->format('Y-m-d'),
        ];
    }

    private function tambahHitunganKode($kode, &$izin, &$cuti, &$izHalf, &$izHalfPagi, &$izHalfSiang)
    {
        if ($kode == 3) $izin++;
        elseif ($kode == 2) $cuti++;
        elseif ($kode == 8) $izHalf++;
        elseif ($kode == 22) $izHalfPagi++;
        elseif ($kode == 23) $izHalfSiang++;
    }



    private function numericValue($value)
    {
        return is_numeric($value) ? (int) $value : (int) str_replace(['.', ','], '', $value);
    }

    public function hitungInovationReward()
    {
        // Ambil data inov_reward dari data_karyawan
        $inovRewardDasar = M_DataKaryawan::where('user_id', $this->karyawanId)
            ->value('inov_reward') ?? 0;
        // dd($inovRewardDasar);
        // Pastikan jumlah kehadiran terisi
        if (!isset($this->rekap['kehadiran'])) {
            $this->rekap['kehadiran'] = M_Presensi::where('user_id', $this->karyawanId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        // Hitung nilai per hari
        $inovRewardPerHari = (int) str_replace('.', '', $inovRewardDasar) / 26;
        // Set jumlah inovation reward sesuai kehadiran
        $this->inovation_reward_jumlah = (int) $this->rekap['kehadiran'];

        // Hitung total
        $this->inovation_reward =
            round((float) $inovRewardPerHari * (float) $this->inovation_reward_jumlah);
        // dd($this->inovation_reward);
    }

    public function updated($propertyName, $id = null)
    {
        // Convert nilai numeric
        $gaji = $this->numericValue($this->gaji_pokok);
        $tunjangan = $this->numericValue($this->tunjangan_jabatan);

        $this->hitungInovationReward();

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
            // dd($this->bpjs_jht_perusahaan_nominal);
        } else {
            $this->bpjs_jht_perusahaan_nominal = 0;
        }
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
        $feeSharing        = $this->numericValue($this->fee_sharing_nominal ?? 0);
        $insentif          = $this->numericValue($this->insentif ?? 0);
        // $insentifSpv       = $this->numericValue($this->insentif ?? 0);
        // $insentifSpvUgr    = $this->numericValue($this->insentif ?? 0);
        $lemburNominal     = $this->numericValue($this->lembur_nominal ?? 0);
        $lemburLiburNominal = $this->numericValue($this->lemburLibur_nominal ?? 0);
        $kasbon            = $this->numericValue($this->kasbon ?? 0);
        $churn             = $this->numericValue($this->churn ?? 0);
        $bpjsJhtPT         = $this->numericValue($this->bpjs_jht_perusahaan_nominal ?? 0);
        $bpjsPT            = $this->numericValue($this->bpjs_perusahaan_nominal ?? 0);

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
            $totalHariIzin = ($this->rekap['izin'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari pagi'] ?? 0) + 0.5 * ($this->rekap['izin setengah hari siang'] ?? 0);
            $potonganIzin = round($perHari * $totalHariIzin);
            $currentBranch = session('selected_entitas');
            if ($currentBranch === 'MC') {
                $potonganTerlambat = ($this->rekap['terlambat'] ?? 0) * 15000;
            } else {
                $potonganTerlambat = ($this->rekap['terlambat'] ?? 0) * 25000;
            }
            // dd($potonganTerlambat);
        }

        // === 7. Hitung BPJS ===
        $dasarBpjs = $gajiPokok + $tunjanganJabatan;
        $umk = 2470800;

        if ($dasarBpjs < $umk) {
            $nilaiDasarBpjs = $umk;
        } elseif ($dasarBpjs > $umk) {
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
        $totalGaji = round(
            $gajiPokok
                + $tunjanganJabatan
                + $totalTunjangan
                + $lemburNominal
                + $lemburLiburNominal
                + $insentif
                // + $insentifSpv
                // + $insentifSpvUgr
                + $tunjanganKehadiran
                + $kebudayaan
                + $feeSharing
                + $transport
                + $uangMakan
                + $inovationReward
                - $totalPotonganManual
                - $kasbon
                - $churn
                - $potonganIzin
                - $potonganTerlambat
                - $this->bpjs_nominal
                - $this->bpjs_jht_nominal
        );
        // dd(strtoupper($this->entitas), strtolower($this->jabatan));
        if (
            isset($this->entitas, $this->jabatan) &&
            strtoupper(trim($this->entitas)) === 'UNB' &&
            strtolower(trim($this->jabatan)) === 'branch manager'
        ) {
            $totalGaji -= ($bpjsJhtPT + $bpjsPT);
        }
        $this->total_gaji = $totalGaji;
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

            if ($namaDipilih === 'Tunjangan Kehadiran') {
                // Isi nominal otomatis Tunjangan Kehadiran
                $tunjangan = JenisTunjanganModel::where('nama_tunjangan', $namaDipilih)->first();
                $tunjanganKehadiran = 0;
                if (($this->rekap['terlambat'] ?? 0) == 0) {
                    $tunjanganKehadiran = ($this->rekap['kehadiran'] ?? 0) * $tunjangan->deskripsi;
                }
                $this->tunjangan[$index]['nominal'] = $tunjanganKehadiran;
            } elseif ($namaDipilih === 'Achievement') {
                // Ambil bonus dari data_karyawan
                $bonus = M_DataKaryawan::where('id', $this->user_id)->value('bonus') ?? 0;
                $this->tunjangan[$index]['nominal'] = $this->numericValue($bonus);
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
        $entitasNama = session('selected_entitas', 'UHO');

        $entitasModel = M_Entitas::where('nama', $entitasNama)->first();
        $entitasId = $entitasModel?->id;

        // Jika tidak ditemukan, fallback ke default UHO
        $entitasKode = $entitasModel?->nama ?? 'UHO';

        // Ambil periode

        $periode = now()->format('Y-m');
        $tahun = Carbon::createFromFormat('Y-m', $periode)->format('Y'); // "25"
        $bulanAngka = Carbon::createFromFormat('Y-m', $periode)->format('n'); // "6"
        $bulanRomawi = $this->toRoman($bulanAngka); // "VI"

        // Hitung jumlah slip yang sudah dicetak untuk periode tersebut (per entitas)
        // $lastSlip = PayrollModel::where('periode', $periode)
        //     ->when($entitasModel, function ($query) use ($entitasModel) {
        //         return $query->where('entitas_id', $entitasModel->id);
        //     })
        //     ->orderByDesc('id')
        //     ->first();

        $lastSlip = PayrollModel::when($entitasModel, function ($query) use ($entitasModel) {
            return $query->where('entitas_id', $entitasModel->id);
        })
            ->orderByDesc('id')
            ->first();

        if ($lastSlip) {
            // Ambil angka terakhir dari nomor slip
            preg_match('/(\d+)$/', $lastSlip->no_slip, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nomorUrut = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Format slip berdasarkan nama entitas
        switch (strtoupper($entitasKode)) {
            case 'UHO':
                return "006/DJB-UHO/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UNR':
                return "006/DJB-UNR/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UNB':
                return "006/DJB-UNB/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            case 'UGR':
                return "006/DJB-UGR/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
            default:
                return "006/DJB-{$entitasKode}/HR/{$tahun}/{$bulanRomawi}/{$nomorUrut}";
        }
    }

    public function toRoman($month)
    {
        $romawi = [
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
        ];
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
            'lembur_libur' => $this->numericValue($this->lemburLibur_nominal),
            'izin' => $this->numericValue($this->izin_nominal),
            'terlambat' => $this->numericValue($this->terlambat_nominal),
            'tunjangan' => json_encode($this->tunjangan),
            'potongan' => json_encode($this->potongan),
            'bpjs' => $this->bpjs_nominal,
            'bpjs_jht' => $this->bpjs_jht_nominal,
            'uang_makan' => $this->numericValue($this->uang_makan_total),
            'jml_uang_makan' => $this->numericValue($this->uang_makan_jumlah),
            'transport' => $this->numericValue($this->transport_total),
            'jml_transport' => $this->numericValue($this->transport_jumlah),
            'fee_sharing' => $this->numericValue($this->fee_sharing_nominal),
            'inov_reward' => $this->numericValue($this->inovation_reward),
            'insentif' => $this->numericValue($this->insentif),
            'jml_psb' => $this->jml_psb,
            'churn' => $this->churn,
            'kasbon' => $this->kasbon,
            'rekap' => json_encode($this->rekap),
            'total_gaji' => (int) $this->total_gaji,
            'periode' => $this->bulanTahun,
            'bpjs_perusahaan' => $this->bpjs_perusahaan_nominal,
            'bpjs_jht_perusahaan' => $this->bpjs_jht_perusahaan_nominal,
            'tunjangan_kebudayaan' => $this->numericValue($this->kebudayaan),
        ];
        // dd($data);

        PayrollModel::create($data);

        $this->dispatch(
            'swal',
            params: [
                'title' => 'Data Saved',
                'icon' => 'success',
                'text' => 'Data has been saved successfully',
                'showConfirmButton' => false,
                'timer' => 1500
            ]
        );
        return redirect()->route('payroll');
    }

    public function render()
    {
        return view('livewire.create-slip-gaji');
    }
}
