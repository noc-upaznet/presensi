<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payroll as PayrollModel;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PayrollExport;
use App\Imports\PayrollImport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;


class Payroll extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $search = '';
    public $selectedYear;
    public $selectedMonth;
    public $periode;
    public $perPage = 10;
    public $payrollIdToDelete;
    public $startDate;
    public $endDate;
    public $file;
    

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

        $this->dispatch('dataPayrollEdited', $id);
    }

    public function confirmHapusPayroll($id)
    {
        $this->payrollIdToDelete = $id;
    }

    public function deletePayroll()
    {
        if ($this->payrollIdToDelete) {
            \App\Models\Payroll::find($this->payrollIdToDelete)?->delete();
            $this->dispatch('dataPayrollDeleted');
            $this->payrollIdToDelete = null;
        }
    }

    public function downloadSlip()
    {
        $data = [
            'nama' => 'Nadia Safira Khairunnisa',
            'jabatan' => 'Admin HR',
            'periode' => 'Maret 2025',
            'gaji_pokok' => 2000000,
            'tunjangan_jabatan' => 500000,
            'uang_makan' => 260000,
            'bpjs_kesehatan' => 20000,
            'bpjs_tk_jht' => 40000,
            'bpjs_tk_jp' => 30000,
            'pph21' => 25000,
            'gaji_bersih' => 2655000,
        ];

        // Gunakan view 'cetak-slip-gaji' di sini
        $pdf = Pdf::loadView('livewire.salary-slip.cetak-slip-gaji', $data);

        // Kirim file PDF ke browser untuk diunduh
        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="slip-gaji.pdf"',
        ]);
    }

    public function exportExcel()
    {
        // Validasi tanggal (optional)
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        return Excel::download(new PayrollExport($this->startDate, $this->endDate), 'payroll.xlsx');
        $this->dispatch('dataPayrollExported');
    }

    public function importExcel()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new PayrollImport, $this->file->getRealPath());

        session()->flash('success', 'Data payroll berhasil diimport.');

        // Reset input & close modal jika perlu
        $this->reset('file');
        $this->dispatch('dataPayrollImported');
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
