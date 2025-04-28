<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakSlipGaji extends Component
{
    public function downloadSlip()
    {
        $data = [
            'nama' => 'Nadia Safira Khairunnisa',
            'jabatan' => 'Admin HR',
            'periode' => 'April 2025',
            'slip_number' => 'SLIP/HR/2025/04/001',
            'gaji_pokok' => 3000000,
            'tunjangan_jabatan' => 500000,
            'uang_makan' => 400000,
            'bpjs_kesehatan' => 50000,
            'bpjs_tk_jht' => 75000,
            'bpjs_tk_jp' => 35000,
            'pph21' => 40000,
            'gaji_bersih' => 3900000,
        ];

        $pdf = Pdf::loadView('exports.slip-gaji-pdf', $data);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'slip-gaji.pdf');
    }

    public function render()
    {
        return view('livewire.cetak-slip-gaji');
    }
}
