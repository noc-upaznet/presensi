<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakSlipGaji extends Component
{
    public function downloadSlip()
{
    $url = route('slip-gaji.download');
    $this->dispatchBrowserEvent('redirect-download', ['url' => $url]);
}

    public function render()
    {
        return view('livewire.cetak-slip-gaji');
    }
}
