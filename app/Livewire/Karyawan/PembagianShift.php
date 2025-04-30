<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_JadwalShift;
use Illuminate\Support\Facades\Crypt;

class PembagianShift extends Component
{
    protected $listeners = ['refreshTable' => '$refresh'];

    public function showEdit($id)
    {
        $jadwal = M_JadwalShift::findOrFail(Crypt::decrypt($id));
        // dd($jadwal);
        $this->dispatch('edit-data', data: $jadwal->toArray());

        $this->dispatch('modal-edit-shift', action: 'show');
    }
    
    public function render()
    {
        $datas = M_JadwalShift::orderBy('id', 'desc')->get();
        return view('livewire.karyawan.pembagian-shift', [
            'datas' => $datas,
        ]);
    }
}
