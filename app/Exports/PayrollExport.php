<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PayrollExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function headings(): array
    {
        $bulanTahun = Carbon::createFromFormat('Y-m', $this->periode)
        ->locale('id')
        ->translatedFormat('F Y');
        
        return [
            ['Data Slip Gaji Periode ' . $bulanTahun], // Baris 1
            ['No Slip', 'Nama Karyawan', 'NIP', 'Divisi', 'Periode', 'Total Gaji'], // Baris 2
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Styling Judul (baris 1)
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Merge kolom A1 - F1 untuk judul
                $event->sheet->mergeCells('A1:F1');

                // Styling Heading Kolom (baris 2)
                $event->sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFEFEFEF'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);
            },
        ];
    }

    public function collection()
    {
        return DB::table('payroll')
            ->join('data_karyawan', 'payroll.karyawan_id', '=', 'data_karyawan.id')
            ->where('payroll.periode', $this->periode)
            ->select(
                'payroll.no_slip',
                'data_karyawan.nama_karyawan',
                'data_karyawan.nip_karyawan as nip_karyawan',
                'payroll.divisi',
                'payroll.periode',
                'payroll.total_gaji'
            )->get();
    }
}
