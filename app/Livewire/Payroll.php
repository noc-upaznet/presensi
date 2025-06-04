<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payroll as PayrollModel;
use Livewire\WithPagination;

class Payroll extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedYear;
    public $selectedMonth;
    public $periode;
    public $perPage = 10;
    public $payrollIdToDelete;


    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedMonth = now()->month;
        $this->periode = now()->format('Y-m');
    }

    public function setPeriode()
    {
        if ($this->selectedYear && $this->selectedMonth) {
            $this->periode = $this->selectedYear . '-' . str_pad($this->selectedMonth, 2, '0', STR_PAD_LEFT);
        }
    }

    public function editPayroll($id)
    {
        $payroll = PayrollModel::find($id);
        if ($payroll) {
            $this->dispatch('editPayroll', $payroll);
        }
    }
    
    public function confirmHapusPayroll($id)
    {
        $this->payrollIdToDelete = $id;
    }

    public function deletePayroll()
    {
        if ($this->payrollIdToDelete) {
            \App\Models\Payroll::find($this->payrollIdToDelete)?->delete();

            // Bisa tambahkan refresh/pagination data di sini kalau perlu
            // $this->data = Payroll::latest()->paginate($this->perPage);

            $this->dispatch('dataPayrollTerhapus');
            $this->payrollIdToDelete = null;
        }
    }

    public function render()
    {
        $data = PayrollModel::whereYear('created_at', $this->selectedYear)
            ->whereMonth('created_at', $this->selectedMonth)
            ->paginate($this->perPage);

        return view('livewire.payroll', [
            'data' => $data
        ]);
    }
}
