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
        'uang_makan',
        'transport',
        'fee_sharing',
        'insentif',
        'jml_psb',
        'rekap',
        'total_gaji',
        'periode',
    ];

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    public function getJabatan()
    {
        return $this->getKaryawan()->first()->jabatan();
    }

    public function getNamaJabatan()
    {
        return $this->getKaryawan()->first()->getJabatan()->first()->nama_jabatan ?? null;
    }
    
}