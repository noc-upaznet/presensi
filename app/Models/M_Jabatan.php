<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = [
        'nama_jabatan',
        'deskripsi',
        'has_staff',
        'spv_id',
    ];

    // public function staff()
    // {
    //     return $this->hasMany(M_Staff::class, 'jabatan_id', 'id');
    // }

    // public function supervisor()
    // {
    //     return $this->belongsTo(M_Jabatan::class, 'spv_id', 'id');
    // }
}
