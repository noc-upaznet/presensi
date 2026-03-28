<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Entitas;
use App\Models\M_Presensi;
use App\Models\M_Pengajuan;
use App\Models\PayrollModel;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;

class Dashboard extends Component
{
    public $shiftKategori = [];
    public $labels = [];
    public $tepatWaktu = [];
    public $terlambat = [];
    public $tidakAbsen = [];
    public $total_gaji;
    public $total_gaji_titip;
    public $bpjs_kes_pt;
    public $bpjs_jht_pt;
    public $note_total_gaji;
    public $note_total_gaji_titip;
    public $note_bpjs_kes;
    public $note_bpjs_jht;

    public function mount()
    {
        $shifts = M_JadwalShift::whereIn('nama_shift', [
            '07.00-15.00',
            '15.00-23.00',
            '23.00-07.00',
            '08.00-16.00',
            '08.00-13.00'
        ])->get();

        $this->shiftKategori = [
            'Helpdesk' => [],
            'Karyawan Lainnya' => []
        ];

        foreach ($shifts as $shift) {
            $isSaturday = Carbon::now()->isSaturday();

            $jamMasuk = $shift->jam_masuk;
            $jamPulang = $shift->jam_pulang;

            if ($isSaturday && $shift->nama_shift == '08.00-16.00') {
                $jamPulang = '13:00:00';
            }

            $jam = Carbon::parse($jamMasuk)->format('H:i') . ' - ' . Carbon::parse($jamPulang)->format('H:i');

            if ($shift->nama_shift == '07.00-15.00') {
                $this->shiftKategori['Helpdesk'][] = ['label' => 'Pagi', 'jam' => $jam];
            } elseif ($shift->nama_shift == '15.00-23.00') {
                $this->shiftKategori['Helpdesk'][] = ['label' => 'Sore', 'jam' => $jam];
            } elseif ($shift->nama_shift == '23.00-07.00') {
                $this->shiftKategori['Helpdesk'][] = ['label' => 'Malam', 'jam' => $jam];
            } elseif ($shift->nama_shift == '08.00-16.00') {
                $this->shiftKategori['Karyawan Lainnya'][] = ['label' => 'Pagi', 'jam' => $jam];
            }
        }

        $year = now()->year;

        for ($month = 1; $month <= 12; $month++) {
            $start = Carbon::create($year, $month)->startOfMonth();
            $end = Carbon::create($year, $month)->endOfMonth();

            $totalHariKerja = $start->diffInWeekdays($end) + 1;

            $tepat = M_Presensi::whereBetween('tanggal', [$start, $end])
                ->where('status', 0)
                ->count();

            $telat = M_Presensi::whereBetween('tanggal', [$start, $end])
                ->where('status', 1)
                ->count();

            // Ambil entitas dari session atau default ke 'UHO'
            $entitas = session('selected_entitas', 'UHO');
            $entitasModel = M_Entitas::where('nama', $entitas)->first();
            $karyawanQuery = M_DataKaryawan::where('entitas', $entitasModel?->id);

            $totalUser = $karyawanQuery->count();
            $totalSeharusnya = $totalUser * $totalHariKerja;
            $absen = $totalSeharusnya - ($tepat + $telat);

            $this->labels[] = $start->format('M');
            $this->tepatWaktu[] = $tepat;
            $this->terlambat[] = $telat;
            $this->tidakAbsen[] = max($absen, 0);
        }

        $this->countGaji();
    }

    public function countGaji()
    {
        $now = Carbon::now();

        $start = $now->copy()->startOfMonth(); // tgl 1
        $end   = $now->copy()->endOfMonth();   // tgl 30/31

        // bulan sebelumnya
        $lastStart = $now->copy()->subMonth()->startOfMonth();
        $lastEnd   = $now->copy()->subMonth()->endOfMonth();

        $entitas = session('selected_entitas', 'UHO');
        $karyawanIds = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        // =========================
        // FUNCTION HITUNG GAJI
        // =========================
        $hitungGaji = function ($items) {
            return $items->sum(function ($item) {

                $tunjanganArray = collect(json_decode($item->tunjangan, true) ?? []);
                $potonganArray  = collect(json_decode($item->potongan, true) ?? []);

                $pendapatan =
                    ($item->gaji_pokok ?? 0)
                    + ($item->tunjangan_jabatan ?? 0)
                    + (($item->lembur ?? 0) + ($item->lembur_libur ?? 0))
                    + ($item->tunjangan_kebudayaan ?? 0)
                    + ($item->transport ?? 0)
                    + ($item->uang_makan ?? 0)
                    + ($item->fee_sharing ?? 0)
                    + ($item->insentif ?? 0)
                    + ($item->inov_reward ?? 0);

                foreach ($tunjanganArray as $t) {
                    $pendapatan += (int) ($t['nominal'] ?? 0);
                }

                $potongan = ($item->izin ?? 0);

                $excludePotongan = ['pph 21', 'pph21', 'potongan kebudayaan'];

                foreach ($potonganArray as $p) {
                    if (in_array(strtolower($p['nama'] ?? ''), $excludePotongan)) {
                        continue;
                    }
                    $potongan += (int) ($p['nominal'] ?? 0);
                }

                return $pendapatan - $potongan;
            });
        };

        // =========================
        // TOTAL GAJI (TIDAK TITIP)
        // =========================
        $currentGaji = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->whereBetween('created_at', [$start, $end])
                ->where('titip', 0)
                ->get()
        );

        $lastGaji = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->whereBetween('created_at', [$lastStart, $lastEnd])
                ->where('titip', 0)
                ->get()
        );

        $this->total_gaji = $currentGaji;
        $this->note_total_gaji = $this->hitungPersen($currentGaji, $lastGaji);

        // =========================
        // GAJI TITIP
        // =========================
        $currentTitip = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->whereBetween('created_at', [$start, $end])
                ->where('titip', 1)
                ->get()
        );

        $lastTitip = $hitungGaji(
            PayrollModel::whereIn('karyawan_id', $karyawanIds)
                ->whereBetween('created_at', [$lastStart, $lastEnd])
                ->where('titip', 1)
                ->get()
        );

        $this->total_gaji_titip = $currentTitip;
        $this->note_total_gaji_titip = $this->hitungPersen($currentTitip, $lastTitip);

        // =========================
        // BPJS KES
        // =========================
        $currentBpjsKes = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('created_at', [$start, $end])
            ->sum('bpjs_perusahaan');

        $lastBpjsKes = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->sum('bpjs_perusahaan');

        $this->bpjs_kes_pt = $currentBpjsKes;
        $this->note_bpjs_kes = $this->hitungPersen($currentBpjsKes, $lastBpjsKes);

        // =========================
        // BPJS JHT
        // =========================
        $currentBpjsJht = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('created_at', [$start, $end])
            ->sum('bpjs_jht_perusahaan');

        $lastBpjsJht = PayrollModel::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->sum('bpjs_jht_perusahaan');

        $this->bpjs_jht_pt = $currentBpjsJht;
        $this->note_bpjs_jht = $this->hitungPersen($currentBpjsJht, $lastBpjsJht);
    }

    private function hitungPersen($current, $last)
    {
        if ($last > 0) {
            $diff = $current - $last;
            $percent = round(($diff / $last) * 100, 2);
            $isUp = $percent >= 0;

            return ($isUp ? '▲ +' : '▼ ') . abs($percent) . '% dari bulan sebelumnya';
        }

        return 'Data bulan lalu tidak tersedia';
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO'); // default fallback

        $karyawanIds = M_DataKaryawan::where('entitas', $entitas)->pluck('id');

        // dd($totalBpjskes);
        $totalIzinCuti = M_Pengajuan::whereIn('karyawan_id', $karyawanIds)
            ->where('status', 1)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $totalPresensi = M_Presensi::whereIn('user_id', $karyawanIds)
            ->whereDate('tanggal', now()->toDateString())
            ->where(function ($q) {
                $q->whereHas('getKaryawan', function ($sub) {
                    $sub->whereNotIn('level', ['SPV', 'Manajer']);
                })
                    ->where(function ($q2) {
                        $q2->where(function ($q3) {
                            $q3->where('lokasi_lock', 0)
                                ->where('approve', 1);
                        })
                            ->orWhere(function ($q3) {
                                $q3->where('lokasi_lock', 1)
                                    ->where('approve', 0);
                            });
                    })
                    ->orWhereHas('getKaryawan', function ($sub) {
                        $sub->whereIn('level', ['SPV', 'Manajer']);
                    });
            })
            ->count();

        $statusKaryawan = M_DataKaryawan::whereIn('id', $karyawanIds)
            ->selectRaw('status_karyawan, COUNT(*) as total')
            ->groupBy('status_karyawan')
            ->pluck('total', 'status_karyawan')
            ->toArray();

        return view('livewire.dashboard', [
            'totalPegawai' => $karyawanIds->count(),

            'totalGaji' => $this->total_gaji,
            'noteTotalGajiTetap' => $this->note_total_gaji,

            'totalGajiTitip' => $this->total_gaji_titip,
            'noteTotalGajiTitip' => $this->note_total_gaji_titip,

            'izinCuti' => $totalIzinCuti,
            'totalPresensi' => $totalPresensi,
            'statusKaryawan' => $statusKaryawan,

            'totalBpjskes' => $this->bpjs_kes_pt,
            'noteTotalBpjskes' => $this->note_bpjs_kes,

            'totalBpjsJht' => $this->bpjs_jht_pt,
            'noteTotalBpjsJht' => $this->note_bpjs_jht,

            'pendidikan' => [
                'SMK' => 45,
                'D3' => 23,
                'S1' => 29,
                'S2' => 7,
            ],
        ]);
    }
}
