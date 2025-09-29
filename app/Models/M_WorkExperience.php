<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_WorkExperience extends Model
{
    protected $table = 'work_experience';
    protected $fillable = [
        'karyawan_id',
        'company',
        'employment_period',
    ];
}
