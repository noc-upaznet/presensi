<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardAdmin extends Component
{
    public function render()
    {
        // Nanti bisa ganti ini pakai data dari DB
        return view('livewire.dashboard-admin', [
            'totalPegawai' => 104,
            'totalGaji' => 75985069,
            'kenaikanGaji' => -5,
            'izinCuti' => 6,
            'masuk' => 98,
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
