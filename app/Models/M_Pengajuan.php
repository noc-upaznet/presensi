<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tanggal',
        'keterangan',
        'status',
    ];

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    public function getShift()
    {
        return $this->belongsTo(M_JadwalShift::class, 'shift_id');
    }

    public function getJadwal()
    {
        return $this->hasOne(M_Jadwal::class, 'id_karyawan', 'karyawan_id');
    }
}
