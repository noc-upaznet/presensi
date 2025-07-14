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
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $shiftKategori = [];
    public $labels = [];
    public $tepatWaktu = [];
    public $terlambat = [];
    public $tidakAbsen = [];
    
    public function mount()
    {
        if (Auth::user()?->current_role !== 'admin') {
            // Bisa redirect atau abort
            return redirect()->route('clock-in');
            // abort(403, 'Access Denied');
        }
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
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO'); // default fallback
        $entitasModel = M_Entitas::where('nama', $entitas)->first();
        // Ambil ID entitas jika valid
        if ($entitasModel) {
            $karyawanIds = M_DataKaryawan::where('entitas', $entitas)->pluck('id');
            // dd($entitas, $karyawanIds);
        } else {
            $karyawanIds = M_DataKaryawan::pluck('id'); // fallback ke semua
        }

        // Filter berdasarkan ID karyawan dari entitas tersebut
        $totalGaji = PayrollModel::whereIn('karyawan_id', $karyawanIds)->sum('total_gaji');
        $totalIzinCuti = M_Pengajuan::whereIn('karyawan_id', $karyawanIds)
            ->where('status', 1)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $totalPresensi = M_Presensi::whereIn('user_id', $karyawanIds)
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        $statusKaryawan = M_DataKaryawan::whereIn('id', $karyawanIds)
            ->selectRaw('status_karyawan, COUNT(*) as total')
            ->groupBy('status_karyawan')
            ->pluck('total', 'status_karyawan')
            ->toArray();

        return view('livewire.dashboard', [
            'totalPegawai' => $karyawanIds->count(),
            'totalGaji' => $totalGaji,
            'kenaikanGaji' => -5,
            'izinCuti' => $totalIzinCuti,
            'totalPresensi' => $totalPresensi,
            'statusKaryawan' => $statusKaryawan,
            'pendidikan' => [
                'SMK' => 45,
                'D3' => 23,
                'S1' => 29,
                'S2' => 7,
            ],
        ]);
    }

}