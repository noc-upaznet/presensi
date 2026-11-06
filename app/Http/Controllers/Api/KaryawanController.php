<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\M_DataKaryawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $employees = M_DataKaryawan::with('villageKtp.district.regency.province')
            ->select('id', 'nama_karyawan', 'tempat_lahir', 'tanggal_lahir', 'divisi', 'level', 'entitas', 'alamat_ktp', 'village_id_ktp')
            ->get();
            
        return response()->json($employees);
    }

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
