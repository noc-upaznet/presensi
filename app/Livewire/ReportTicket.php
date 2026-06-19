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
            ->when($this->branchId, function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('branch_id', $this->branchId)
                        ->where('is_gangguan', 1);
                });
            })
            ->whereBetween('created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ])
            ->latest()
            ->limit($this->tableLength)
            ->get();

        $this->reportTm = ReportTm::with([
            'team',
            'ticket.branch',
        ])
            ->when($this->branchId, function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('branch_id', $this->branchId)
                        ->where('is_gangguan', 1);
                });
            })
            ->whereBetween('created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ])
            ->latest()
            ->limit($this->tableLength)
            ->get();

        return view('livewire.report-ticket');
    }
}
