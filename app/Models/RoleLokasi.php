<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleLokasi extends Model
{
    use HasFactory;
    protected $table = 'role_lokasi';
    protected $fillable = [
        'nama_karyawan',
        'lock',
        'lokasi_presensi',
    ];
}
