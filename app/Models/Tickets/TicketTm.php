<?php

namespace App\Models\Tickets;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketTm extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custpanel';
    protected $table = 'ticket_tm';
    protected $fillable = [
        'branch_id',
        'ticket_number',
        'is_gangguan',
        'team_id',
        'description',
        'analysis',
        'status',
        'created_by',
        'closed_at',
    ];

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateTicketNumber()
    {
        $latestTicket = self::where('created_at', 'like', date('Y-m') . '%')->orderBy('ticket_number', 'desc')->first();
        if ($latestTicket) {
            preg_match('/.(\d*)/', $latestTicket->ticket_number, $output_array);
            $newTicketNumber = 'T' . ($output_array[1] + 1);
        } else {
            $newTicketNumber = 'T' . date('ym') . '00001';
        }
        return $newTicketNumber;
    }

    public function allReportApproved()
    {
        $reports = ReportTm::where('ticket_id', $this->id)->get();
        $approved = true;
        if ($this->hasReport()) {
            foreach ($reports as $report) {
                if ($report->approve_teknis != 1 || $report->approve_noc != 1 || $report->approve_stock != 1) {
                    $approved = false;
                }
            }
        } else {
            $approved = false;
        }
        return $approved;
    }
    public function checkApprovalReport()
    {
        $reports = ReportTm::where('ticket_id', $this->id)->get();
        $approval = [
            'approve_teknis' => true,
            'approve_noc' => true,
            'approve_stock' => true,
        ];
        foreach ($reports as $report) {
            if ($report->approve_teknis != 1) {
                $approval['approve_teknis'] = false;
            }
            if ($report->approve_noc != 1) {
                $approval['approve_noc'] = false;
            }
            if ($report->approve_stock != 1) {
                $approval['approve_stock'] = false;
            }
        }
        return $approval;
    }
    public function hasReport()
    {
        $reports = ReportTm::where('ticket_id', $this->id)->count();
        return $reports > 0 ? true : false;
    }
}
