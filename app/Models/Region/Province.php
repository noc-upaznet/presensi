<?php

namespace App\Models\Region;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $connection = 'db_custpanel';

    protected $table = 'reg_provinces';

    public function regency()
    {
        return $this->hasMany(Regency::class);
    }
}
