<?php

namespace App\Models\Tickets;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportKunjungan extends Model
{
    use SoftDeletes;

    protected $connection = 'db_custpanel';
    protected $table = 'report_kunjungan';
    protected $fillable = [
        'customer_id',
        'team_id',
        'ticket_id',
        'detail_report',
        'changed_data',
        'goods',
        'bill',
        'status',
        'teknisi',
        'approve_teknis',
        'approve_data',
        'approve_noc',
        'approve_billing',
        'approve_stock',
    ];
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function ticket()
    {
        return $this->belongsTo(TicketKunjungan::class, 'ticket_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
