<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'no_gaji',
        'nama',
        'divisi',
        'bulan',
        'tahun',
        'kasbon',
        'total',
        'status',
    ];
}
