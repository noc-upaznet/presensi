<?php

namespace App\Models\Region;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $connection = 'db_custpanel';

    protected $table = 'reg_villages';

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
