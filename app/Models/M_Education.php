<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Education extends Model
{
    protected $table = 'education';
    protected $fillable = [
        'karyawan_id',
        'level_of_education',
        'institution',
        'start_date',
        'end_date',
        'major',
        'nilai',
    ];
}
