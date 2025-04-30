<?php

namespace App\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class SlipGaji extends Component
{
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
        $pdf = Pdf::loadView('livewire.cetak-slip-gaji', $data);

        // Kirim file PDF ke browser untuk diunduh
        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="slip-gaji.pdf"',
        ]);
    }

    public function render()
    {
        return view('livewire.slip-gaji');
    }
}
