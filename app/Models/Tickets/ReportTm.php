<?php

namespace App\Models\Tickets;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportTm extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custpanel';
    protected $table = 'report_tm';
    protected $fillable = [
        'ticket_id',
        'detail_report',
        'goods',
        'status',
        'team_id',
        'teknisi',
        'reported_spv',
        'approve_teknis',
        'approve_noc',
        'approve_stock',
    ];
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function ticket()
    {
        return $this->belongsTo(TicketTm::class, 'ticket_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
