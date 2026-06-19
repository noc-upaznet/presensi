<?php

namespace App\Models\Tickets;

use App\Models\Team;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketKunjungan extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custpanel';
    protected $table = 'ticket_kunjungan';
    protected $fillable = [
        'branch_id',
        'ticket_number',
        'is_gangguan',
        'team_id',
        'customer_id',
        'description',
        'additional',
        'status',
        'reported_spv',
        'approve_billing',
        'created_by',
        'komplain_id',
        'closed_at',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
            $newTicketNumber = 'K' . ($output_array[1] + 1);
        } else {
            $newTicketNumber = 'K' . date('ym') . '00001';
        }
        return $newTicketNumber;
    }
    public function allReportApproved()
    {
        $reports = ReportKunjungan::where('ticket_id', $this->id)->get();
        $approved = true;
        if ($this->hasReport()) {
            foreach ($reports as $report) {
                if ($report->approve_teknis != 1 || $report->approve_noc != 1 || $report->approve_stock != 1 || $report->approve_billing != 1 || $report->approve_data != 1) {
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
        $reports = ReportKunjungan::where('ticket_id', $this->id)->get();
        $approval = [
            'approve_teknis' => true,
            'approve_noc' => true,
            'approve_stock' => true,
            'approve_billing' => true,
            'approve_data' => true,
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
            if ($report->approve_billing != 1) {
                $approval['approve_billing'] = false;
            }
            if ($report->approve_data != 1) {
                $approval['approve_data'] = false;
            }
        }
        return $approval;
    }

    public function hasReport()
    {
        $reports = ReportKunjungan::where('ticket_id', $this->id)->count();
        return $reports > 0 ? true : false;
    }
    public function approveBillingBy()
    {
        return $this->belongsTo(User::class, 'approve_billing_by');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id');
    }
}
