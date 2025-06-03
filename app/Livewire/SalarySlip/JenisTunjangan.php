<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;
use App\Models\jenis_tunjangan as JenisTunjanganModel;

class JenisTunjangan extends Component
{
    public $jenisTunjangan = [];
    public $nama_tunjangan, $maksimal_jumlah;

    public function mount()
    {
        $this->jenisTunjangan = JenisTunjanganModel::all();
    }

    public function render()
    {
        return view('livewire.salary-slip.jenis-tunjangan', [
            'jenisTunjangan' => $this->jenisTunjangan,
        ]);
    }
}
