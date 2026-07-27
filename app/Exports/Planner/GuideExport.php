<?php

namespace App\Exports\Planner;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuideExport implements FromArray, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        return [
            ['PANDUAN IMPORT JADWAL'],
            [''],
            ['1. Jangan mengubah nama kolom pada template.'],
            ['2. Pastikan Shift sudah di atur di menu "Pembagian Shift".'],
            ['3. Jangan menghapus baris karyawan yang sudah tersedia.'],
            ['4. Kolom tanggal hanya boleh diisi dengan kode shift.'],
            ['5. Pastikan tidak ada sel yang digabung (merge cell).'],
            ['6. Simpan file dalam format .xlsx sebelum diimport.'],
            ['7. Jika terdapat kesalahan format, proses import akan gagal.'],
            [''],
            ['FORMAT KODE JAM KERJA'],
            ['8. Jadwal "08.00-16.00" ditulis menjadi "0816".'],
            ['9. Jadwal "07.30-12.30" ditulis menjadi "07301230".'],
            ['10. Jadwal "13.00-21.00" ditulis menjadi "1321".'],
            ['11. Jadwal "20.00-08.00" ditulis menjadi "2008".'],
            ['12. Semua kode jam ditulis tanpa titik (.), tanda minus (-), spasi, atau tanda kutip.'],
            ['13. Jika terdapat pertanyaan atau kendala saat import, silakan hubungi Administrator.'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],
        ];
    }
}
