<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollModel extends Model
{
    protected $table = 'payroll';
    protected $fillable = [
        'karyawan_id',
        'entitas_id',
        'titip',
        'nip_karyawan',
        'no_slip',
        'divisi',
        'gaji_pokok',
        'tunjangan_jabatan',
        'lembur',
        'lembur_libur',
        'tunjangan_kebudayaan',
        'izin',
        'terlambat',
        'tunjangan',
        'potongan',
        'bpjs',
        'bpjs_perusahaan',
        'bpjs_jht',
        'bpjs_jht_perusahaan',
        'uang_makan',
        'jml_uang_makan',
        'transport',
        'jml_transport',
        'fee_sharing',
        'inov_reward',
        'insentif',
        'jml_psb',
        'churn',
        'kasbon',
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

    public function entitas()
    {
        return $this->belongsTo(M_Entitas::class, 'entitas_id');
    }
    
}