<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_ListAnswer extends Model
{
    protected $table = 'list_answer';
    protected $fillable = [
        'question_id',
        'name',
        'is_correct',
    ];
}
