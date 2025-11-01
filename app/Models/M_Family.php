<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_Family extends Model
{
    use SoftDeletes;
    protected $table = 'member_family_of_kk';
    protected $fillable = [
        'karyawan_id',
        'relationships',
        'name',
        'nik',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'education',
        'marital_status',
        'wedding_date',
        'relationship_in_family',
        'citizenship',
        'father',
        'mother',
    ];

    public function karyawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }
}
