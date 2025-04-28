<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_JadwalShift extends Model
{
    protected $table = 'shift';
    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_pulang',
    ];

    public function getJadwal()
    {
        return $this->hasMany(M_TemplateWeek::class, 'nama_template');
    }
}
