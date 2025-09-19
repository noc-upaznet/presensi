<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_AdditionalDataEmployee extends Model
{
    protected $table = 'additional_data_employee';
    protected $fillable = [
        'karyawan_id',
        'dress_size',
        'shoe_size',
        'height',
        'weight',
        'nip',
        'start_date',
        'personality',
        'iq',
        'parent_address',
        'inlaw_address',
        'history_of_illness',
        'name_father_in_law',
        'name_mother_in_law',
    ];
}
