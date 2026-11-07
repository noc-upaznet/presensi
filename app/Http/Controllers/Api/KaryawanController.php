<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\M_DataKaryawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $employees = M_DataKaryawan::with('villageDomisili.district.regency.province')
            ->where('status_karyawan', '!=', 'NONAKTIF')
            ->select('id', 'nama_karyawan', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'divisi', 'level', 'entitas', 'alamat_domisili', 'village_id_domisili')
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
