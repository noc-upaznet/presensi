<?php

namespace App\Http\Controllers;

use App\Models\PayrollModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class PayrollController extends Controller
{
    public function html($id)
    {
        $data = PayrollModel::with('getKaryawan')->findOrFail(Crypt::decrypt($id));
        return view('payroll.preview-slip-gaji', compact('data'));
    }

    public function download($id)
    {
        $data = PayrollModel::with('getKaryawan')->findOrFail(Crypt::decrypt($id));
        $pdf = Pdf::loadView('payroll.exportPdfSlip', compact('data'));
        $cleanNoSlip = preg_replace('/[\/\\\\]/', '-', $data->no_slip);
        $namaKaryawan = $data->getKaryawan->nama_karyawan ?? 'karyawan';
        return $pdf->download($namaKaryawan . '-' . $cleanNoSlip . '.pdf');
    }
}
