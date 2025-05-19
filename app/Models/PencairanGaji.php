<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PencairanGaji extends Model
{
    protected $table = 'pencairan_gaji';
    protected $fillable = ['nama_karyawan', 'jabatan', 'pendapatan', 'tunjangan', 'bonus', 'potongan', 'total_gaji'];
}
