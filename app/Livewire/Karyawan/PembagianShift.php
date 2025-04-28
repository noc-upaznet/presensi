<?php

namespace App\Livewire\Karyawan;

use App\Models\M_JadwalShift;
use Livewire\Component;

class PembagianShift extends Component
{
    public function render()
    {
        $datas = M_JadwalShift::orderBy('id', 'desc')->get();
        return view('livewire.karyawan.pembagian-shift', [
            'datas' => $datas,
        ]);
    }
}
