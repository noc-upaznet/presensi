<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Tickets\ReportKunjungan;
use App\Models\Tickets\ReportTm;
use Livewire\Component;

class ReportTicket extends Component
{
    public $filterStartDate = '';
    public $filterEndDate = '';

    public $table = [];

    // public $tableDetail =[];
    public $reportPsb = [];
    public $reportKunjungan = [];
    public $reportDev = [];
    public $reportTm = [];
    public $reportJasa = [];

    public $team;
    public $branchId = '';
    public $branches;
    public $tableLength = 10;
    public $workDuration = '';
    public function resetSearch()
    {
        $this->filterStartDate = date('Y-m-d');
        $this->filterEndDate = date('Y-m-d');
    }

    public function mount()
    {
        $this->branches = Branch::orderBy('name')->get();

        $this->filterStartDate = date('Y-m-d');
        $this->filterEndDate = date('Y-m-d');
    }
    public function render()
    {
        $this->reportKunjungan = ReportKunjungan::with([
            'team',
            'ticket.customer',
            'ticket.branch',
        ])
            ->whereHas('ticket', function ($q) {
                $q->where('is_gangguan', 1);
            })
            ->when($this->branchId, function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('branch_id', $this->branchId);
                });
            })
            ->whereBetween('created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ])
            ->latest()
            ->limit($this->tableLength)
            ->get();
        if ($this->workDuration == 1) {

            $this->reportKunjungan = $this->reportKunjungan->filter(function ($item) {
                return $item->ticket->created_at->diffInHours($item->created_at) > 4;
            });
        } elseif ($this->workDuration == 2) {

            $this->reportKunjungan = $this->reportKunjungan->filter(function ($item) {
                return $item->ticket->created_at->diffInHours($item->created_at) <= 4;
            });
        }

        $this->reportTm = ReportTm::with([
            'team',
            'ticket.branch',
        ])
            ->whereHas('ticket', function ($q) {
                $q->where('is_gangguan', 1);
            })
            ->when($this->branchId, function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('branch_id', $this->branchId);
                });
            })
            ->whereBetween('created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ])
            ->latest()
            ->limit($this->tableLength)
            ->get();
        if ($this->workDuration == 1) {

            $this->reportTm = $this->reportTm->filter(function ($item) {
                return $item->ticket->created_at->diffInHours($item->created_at) > 4;
            });
        } elseif ($this->workDuration == 2) {

            $this->reportTm = $this->reportTm->filter(function ($item) {
                return $item->ticket->created_at->diffInHours($item->created_at) <= 4;
            });
        }

        return view('livewire.report-ticket');
    }
}
