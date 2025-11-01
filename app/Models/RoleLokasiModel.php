<?php

namespace App\Models;

use App\Models\Lokasi;
use App\Models\M_DataKaryawan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleLokasiModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'role_lokasi';
    protected $fillable = [
        'karyawan_id',
        'lock',
        'lokasi_presensi',
    ];

    // public function getKaryawan()
    // {
    //     return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    // }

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    protected $casts = [
        'lokasi_presensi' => 'array',
    ];

    public function getLokasisAttribute()
    {
        return Lokasi::whereIn('id', $this->lokasi_presensi ?? [])->get();
    }
}
