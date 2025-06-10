<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;
use App\Models\Payroll;

class SlipGajiBelumDibuat extends Component
{
    public bool $showModal = false;
    public $slipGajiList = [];

    protected $listeners = ['openSlipGajiModal' => 'showModal'];

    public function showModal(): void
    {
        $this->slipGajiList = Payroll::where('status', 'belum')->get();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->reset('showModal', 'slipGajiList');
    }

    public function render()
    {
        return view('livewire.salary-slip.slip-gaji-belum-dibuat');
    }
}
