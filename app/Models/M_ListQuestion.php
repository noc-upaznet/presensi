<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_ListQuestion extends Model
{
    protected $table = 'list_question';
    protected $fillable = [
        'name',
    ];

    public function answers()
    {
        return $this->hasMany(M_ListAnswer::class, 'question_id');
    }
}
