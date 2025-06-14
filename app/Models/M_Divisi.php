<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    // Uncomment if you have relationships
    // public function staff()
    // {
    //     return $this->hasMany(M_Staff::class, 'divisi_id', 'id');
    // }
}
