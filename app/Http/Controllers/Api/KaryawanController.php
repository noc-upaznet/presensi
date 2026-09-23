<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\M_DataKaryawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function ulangTahun()
    {
        $karyawan = M_DataKaryawan::query()
            ->select([
                'id',
                'nama_karyawan',
                'tanggal_lahir',
                'divisi',
                'entitas',
            ])
            ->whereNotNull('tanggal_lahir')
            ->orderByRaw('MONTH(tanggal_lahir)')
            ->orderByRaw('DAY(tanggal_lahir)')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $karyawan,
        ]);
    }
}
