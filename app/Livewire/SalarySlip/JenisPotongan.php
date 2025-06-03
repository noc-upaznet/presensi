<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;
use App\Models\jenis_potongan as JenisPotonganModel;

class JenisPotongan extends Component
{
    public $jenisPotongan = [];
    public $nama_potongan, $maksimal_jumlah;

    public function mount()
    {
        $this->jenisPotongan = JenisPotonganModel::all();
    }

    public function render()
    {
        return view('livewire.salary-slip.jenis-potongan', [
            'jenisPotongan' => $this->jenisPotongan,
        ]);
    }
}
