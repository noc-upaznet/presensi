<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;

class ModalSlipGaji extends Component
{
    public $payroll;
    public $show = false;

    protected $listeners = ['showSlipGaji'];

    public function showSlipGaji($payroll)
    {
        $this->payroll = $payroll;
        $this->show = true;
    }
    public function closeModal()
    {
        $this->show = false;
        $this->payroll = null;
    }

    public function render()
    {
        return view('livewire.salary-slip.modal-slip-gaji');
    }
}


