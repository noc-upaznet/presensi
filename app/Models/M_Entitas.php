<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Entitas extends Model
{
    protected $table = 'entitas';

    protected $fillable = [
        'nama',
        'alamat',
        'koordinat' 
    ];

    // Uncomment if you have relationships
    // public function staff()
    // {
    //     return $this->hasMany(M_Staff::class, 'entitas_id', 'id');
    // }
}
