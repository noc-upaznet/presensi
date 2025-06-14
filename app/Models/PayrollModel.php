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
        'tunjangan',
        'potongan',
        'bpjs',
        'bpjs_jht',
        'rekap',
        'total_gaji',
        'periode',
    ];
}