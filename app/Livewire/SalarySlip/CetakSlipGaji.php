<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;


class CetakSlipGaji extends Component
{
    protected $listeners = ['downloadSlip'];

    public function downloadSlip($id)
    {
        $url = route('slip-gaji.download', ['id' => $id]);
        $this->dispatchBrowserEvent('redirect-download', ['url' => $url]);
    }


    public function render()
    {
        return view('livewire.salary-slip.cetak-slip-gaji');
    }
}
