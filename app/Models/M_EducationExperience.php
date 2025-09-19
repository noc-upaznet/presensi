<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_EducationExperience extends Model
{
    protected $table = 'education_and_experience';
    protected $fillable = [
        'karyawan_id',
        'level_of_education',
        'institution',
        'start_date',
        'end_date',
        'major',
        'nilai',
        'company',
        'employment_period',
    ];
}
