<?php

namespace App\Livewire\SalarySlip;

use Livewire\Component;
use App\Models\SalarySlip;
use Illuminate\Validation\Rule;
use App\Models\jenis_tunjangan as JenisTunjangan;
use App\Models\jenis_potongan as JenisPotongan;

class CreateSalarySlip extends Component
{
    public $employee_name, $position, $period;
    public $basic_salary = 0, $allowance = 0, $deduction = 0;
    public $jenis_tunjangan;
    public $jenis_potongan;

    public function getTotalSalaryProperty()
    {
        return ($this->basic_salary + $this->allowance) - $this->deduction;
    }

    public function save()
    {
        $this->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'period' => 'required|date_format:Y-m',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'required|numeric|min:0',
            'deduction' => 'required|numeric|min:0',
        ]);

        SalarySlip::create([
            'employee_name' => $this->employee_name,
            'position' => $this->position,
            'period' => $this->period,
            'basic_salary' => $this->basic_salary,
            'allowance' => $this->allowance,
            'deduction' => $this->deduction,
            'total_salary' => $this->total_salary,
        ]);

        session()->flash('success', 'Slip gaji berhasil disimpan.');
        $this->reset(); // Kosongkan form
    }
    public function mount()
    {
        $this->jenis_tunjangan = JenisTunjangan::all();
        $this->jenis_potongan = JenisPotongan::all();
    }

    public function render()
    {
        return view('livewire.salary-slip.create-salary-slip', [
            'total_salary' => $this->total_salary,
        ]);
    }
}
