<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;

class DataKaryawan extends Component
{
    public function showEdit()
    {
        // Dispatch event ke modal
        $this->dispatch('modal-edit-data-karyawan', action: 'show');
    }

    public function render()
    {
        return view('livewire.karyawan.data-karyawan');
    }
}
