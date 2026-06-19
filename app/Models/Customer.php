<?php

namespace App\Models;

use App\Models\Bills\Bill;
use App\Models\Region\Village;
use App\Models\Tickets\TicketKomplain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $connection = 'db_custpanel';
    protected $table = 'customers';
    protected $fillable = [
        'branch_id',
        'registration_number',
        'name',
        'pic',
        'provider_id',
        'email',
        'phone',
        'notif_wa',
        'profession',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'nik',
        'npwp',
        'address',
        'village_id',
        'is_same_address',
        'installation_address',
        'installation_village_id',
        'latlng',
        'latitude',
        'longitude',
        'registration_date',
        'media_connection',
        'type_payment',
        'due_date',
        'due_day',
        'month_due_date',
        'status_id',
        'auto_suspend',
        'last_wa',
        'send_wa',
        'description',
        'sales_id'
    ];
}
