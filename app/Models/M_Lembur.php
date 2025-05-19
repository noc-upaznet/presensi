<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Lembur extends Model
{
    protected $table = 'lembur';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'waktu_mulai',
        'waktu_akhir',
        'keterangan',
        'file_bukti',
        'status',
    ];

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    public function getJadwal()
    {
        return $this->hasOne(M_Jadwal::class, 'id_karyawan', 'karyawan_id');
    }
}
