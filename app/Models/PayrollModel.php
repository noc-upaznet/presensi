<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollModel extends Model
{
    protected $table = 'payroll';
    protected $fillable = [
        'karyawan_id',
        'nip_karyawan',
        'no_slip',
        'divisi',
        'gaji_pokok',
        'tunjangan_jabatan',
        'lembur',
        'izin',
        'terlambat',
        'tunjangan',
        'potongan',
        'bpjs',
        'bpjs_jht',
        'rekap',
        'total_gaji',
        'periode',
    ];

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }
}