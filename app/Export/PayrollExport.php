<?php

namespace App\Exports;

use App\Models\PayrollModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PayrollExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    // Terima tanggal awal dan akhir dari Livewire component
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = PayrollModel::query();

        // Filter berdasarkan periode tanggal jika ada
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $payrolls = $query->get();

        return $payrolls->map(function ($payroll) {
            return [
                'nama' => $payroll->nama,
                'divisi' => $payroll->divisi,
                'bulan' => Carbon::parse($payroll->created_at)->translatedFormat('F'),
                'total' => $payroll->total,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Divisi',
            'Bulan',
            'Total',
        ];
    }
}