<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class SlipGajiController extends Controller
{
    public function download()
    {
        $data = [
            'nama' => 'Nadia Safira Khairunnisa',
            'jabatan' => 'Admin HR',
            'periode' => 'April 2025',
            'gaji_pokok' => 3000000,
            'tunjangan_jabatan' => 500000,
            'uang_makan' => 400000,
            'bpjs_kesehatan' => 50000,
            'bpjs_tk_jht' => 75000,
            'bpjs_tk_jp' => 35000,
            'pph21' => 40000,
            'gaji_bersih' => 3900000,
        ];

        $pdf = Pdf::loadView('livewire.cetak-slip-gaji', $data);

        $pdf->setPaper('A4', 'portrait'); // Atur ukuran kertas
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true); // Aktifkan HTML5 parser
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);

        return $pdf->download('slip-gaji.pdf');
    }
}
