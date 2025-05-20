<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'no_gaji',
        'nama',
        'jabatan',
        'bulan',
        'tahun',
        'kasbon',
        'total',
        'status',
    ];
}
