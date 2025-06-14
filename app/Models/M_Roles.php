<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Roles extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'nama',
        'permission',
    ];

    public function getUsers()
    {
        return $this->hasMany(User::class, 'role');
    }
}
