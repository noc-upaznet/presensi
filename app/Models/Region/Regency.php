<?php

namespace App\Models\Region;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $connection = 'db_custpanel';

    protected $table = 'reg_regencies';

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id');
    }
}
