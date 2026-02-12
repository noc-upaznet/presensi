<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasbonDetails extends Model
{
    protected $table = 'kasbon_details';

    protected $fillable = [
        'kasbon_id',
        'periode',
        'nominal_potong',
    ];

    public function kasbon()
    {
        return $this->belongsTo(KasbonModel::class, 'kasbon_id');
    }
}
