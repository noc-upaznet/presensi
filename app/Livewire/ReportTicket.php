<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Tickets\ReportKunjungan;
use App\Models\Tickets\ReportTm;
use App\Models\Tickets\TicketKunjungan;
use Livewire\Attributes\Url;
use Livewire\Component;

class ReportTicket extends Component
{
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $filterStatus = '';
    public $tableSearch = '';

    public $table = [];

    // public $tableDetail =[];
    public $reportKunjungan = [];
    public $reportTm = [];
    public $ticketKunjungan = [];

    public $team;
    public $branchId = '';
    public $branches;
    public $tableLength = 10;
    public $workDuration = '';

    public $repeatDetails = [];
    public $repeatTitle = '';

    #[Url(as: 'tab')]
    public $tab = 'ReportTicket';

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function resetSearch()
    {
        $this->filterStartDate = date('Y-m-d');
        $this->filterEndDate = date('Y-m-d');
    }

    public function mount()
    {
        $this->branches = Branch::orderBy('name')->get();

        $this->filterEndDate = ($this->filterEndDate != '') ? $this->filterEndDate : date('Y-m-d');
        $this->filterStartDate = ($this->filterStartDate != '') ? $this->filterStartDate : date('Y-m') . '-01';
    }

    public function showRepeatDetail($customerId, $type)
    {
        $query = TicketKunjungan::with([
            'customer',
            'team'
        ])
            ->where('customer_id', $customerId)
            ->where('is_gangguan', 1);

        if ($type == 'month') {
            $query->where('created_at', '>=', now()->subMonth());
            $this->repeatTitle = 'Riwayat Gangguan 1 Bulan';
        }

        if ($type == 'week') {
            $query->where('created_at', '>=', now()->subWeek());
            $this->repeatTitle = 'Riwayat Gangguan 1 Minggu';
        }

        $this->repeatDetails = $query
            ->orderBy('created_at', 'desc')
            ->get();

        $this->dispatch('show-repeat-detail');
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

        $this->ticketKunjungan = TicketKunjungan::query()
            ->join(
                'customers',
                'ticket_kunjungan.customer_id',
                '=',
                'customers.id'
            )
            ->leftJoin(
                'report_kunjungan',
                'ticket_kunjungan.id',
                '=',
                'report_kunjungan.ticket_id'
            )
            ->select('ticket_kunjungan.*')
            ->distinct()
            ->where('ticket_kunjungan.deleted_at', null)
            ->where('ticket_kunjungan.is_gangguan', 1);


        if ($this->branchId) {
            $this->ticketKunjungan->where(
                'ticket_kunjungan.branch_id',
                $this->branchId
            );
        }

        $this->ticketKunjungan->whereBetween(
            'ticket_kunjungan.created_at',
            [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ]
        );

        if ($this->tableSearch) {
            $this->ticketKunjungan->where(function ($query) {
                $query->where(
                    'ticket_kunjungan.ticket_number',
                    'like',
                    '%' . $this->tableSearch . '%'
                )
                    ->orWhere(
                        'ticket_kunjungan.description',
                        'like',
                        '%' . $this->tableSearch . '%'
                    )
                    ->orWhere(
                        'ticket_kunjungan.additional',
                        'like',
                        '%' . $this->tableSearch . '%'
                    )
                    ->orWhere(
                        'customers.name',
                        'like',
                        '%' . $this->tableSearch . '%'
                    )
                    ->orWhere(
                        'customers.registration_number',
                        'like',
                        '%' . $this->tableSearch . '%'
                    );
            });
        }

        $this->ticketKunjungan->addSelect([

            // Total gangguan
            'repeat_count' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at'),

            // Gangguan 1 bulan terakhir
            'repeat_month' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at')
                ->where('tk2.created_at', '>=', now()->subMonth()),

            // Gangguan 1 minggu terakhir
            'repeat_week' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at')
                ->where('tk2.created_at', '>=', now()->subWeek()),
        ]);

        $this->ticketKunjungan = $this->ticketKunjungan
            ->with(['customer', 'team'])
            ->limit($this->tableLength)
            ->get();

        return view('livewire.report-ticket');
    }
}
