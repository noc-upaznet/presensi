<?php

namespace App\Livewire\Karyawan;

use App\Models\M_DataKaryawan;
use Livewire\Component;

class DetailDataKaryawan extends Component
{
    public $data;

    public function mount($id)
    {
        $this->data = M_DataKaryawan::find($id);
    }
    public function render()
    {
        return view('livewire.karyawan.detail-data-karyawan');
    }
}
