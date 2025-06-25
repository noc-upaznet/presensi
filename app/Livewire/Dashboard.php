<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\M_Presensi;
use App\Models\M_Pengajuan;
use App\Models\PayrollModel;
use App\Models\M_DataKaryawan;

class Dashboard extends Component
{
    public function mount()
    {
        $totalKaryawan = M_DataKaryawan::count();
    }
    public function render()
    {
        $totalKaryawan = M_DataKaryawan::count();
        $totalGaji = PayrollModel::sum('total_gaji');
        $totalIzinCuti = M_Pengajuan::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->whereMonth('tanggal', date('m'))
            ->count();
        $totalPresensi = M_Presensi::whereDate('tanggal', date('Y-m-d'))->count();
        // dd($totalPresensi);
        // Nanti bisa ganti ini pakai data dari DB
        return view('livewire.dashboard', [
            'totalPegawai' => $totalKaryawan,
            'totalGaji' => $totalGaji,
            'kenaikanGaji' => -5,
            'izinCuti' => $totalIzinCuti,
            'totalPresensi' => $totalPresensi,
            'statusKaryawan' => [
                'Tetap' => 91,
                'Kontrak' => 9,
                'Probation' => 4,
            ],
            'pendidikan' => [
                'SMK' => 45,
                'D3' => 23,
                'S1' => 29,
                'S2' => 7,
            ],
        ]);
    }
}