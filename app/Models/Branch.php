<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custpanel';
    protected $table = 'branches';

    protected $fillable = [
        'name',
        'phone_helpdesk',
        'phone_billing',
        'region_id',
        'address',
        'village_id',
        'district',
        'code_branch',
        'final_code',
        'default_due_date',
        'code_mail',
        'latlng'
    ];
}
