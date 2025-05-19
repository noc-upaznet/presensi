<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PencairanGaji as Payroll;

class PencairanGaji extends Component
{
    use WithPagination;
    public $search = '';
    public $pencairan_gaji;
   
    public function render()
    {
        $payroll = Payroll::where('nama_karyawan', 'like', '%' . $this->search . '%')->paginate(10);
        
        return view('livewire.pencairan-gaji', [
            'pencairanGaji' => $payroll,
        ]);
    }
}
