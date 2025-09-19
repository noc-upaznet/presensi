<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Dependents extends Model
{
    protected $table = 'dependents';
    protected $fillable = [
        'karyawan_id',
        'relationships',
        'name',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'education',
        'profession',
        'no_telp',
    ];
}
