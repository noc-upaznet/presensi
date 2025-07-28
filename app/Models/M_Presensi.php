<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Presensi extends Model
{
    protected $table = 'presensi';
    protected $fillable = [
        'user_id',
        'tanggal',
        'clock_in',
        'clock_out',
        'lokasi',
        'lokasi_lock',
        'file',
        'status',
    ];

    protected $casts = [
        'lokasi_presensi' => 'array',
    ];

    public function getLokasisAttribute()
    {
        return RoleLokasiModel::whereIn('id', $this->lokasi_presensi ?? [])->get();
    }

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'user_id', 'id');
    }

    public function getUser()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'user_id', 'id');
    }
}
