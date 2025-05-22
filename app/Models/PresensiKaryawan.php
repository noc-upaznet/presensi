<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiKaryawan extends Model
{
    protected $table = 'presensi_karyawan';
    protected $guarded = [];
    protected $casts = [
        'clock_in' => 'datetime:H:i:s',
        'clock_out' => 'datetime:H:i:s',
        'tanggal' => 'date',
    ];
}
