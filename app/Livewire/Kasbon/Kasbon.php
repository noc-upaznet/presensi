<?php

namespace App\Livewire\Kasbon;

use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Kasbon extends Component
{
    public $karyawans;
    public function mount()
    {
        $entitas = session('selected_entitas', 'UHO');
        $user = Auth::user();
        $this->karyawans = M_DataKaryawan::where('entitas', $entitas)
            // ->whereNotIn('id', $jadwalId)
            ->orderBy('nama_karyawan')
            ->get();
    }
    public function render()
    {
        return view('livewire.kasbon.kasbon');
    }
}
