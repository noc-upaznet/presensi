<?php

namespace App\Livewire;

use App\Exports\ReportTicketExport;
use App\Exports\TicketKunjunganRepeatExport;
use App\Models\Branch;
use App\Models\Tickets\ReportKunjungan;
use App\Models\Tickets\ReportTm;
use App\Models\Tickets\TicketKunjungan;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReportTicket extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $filterStartDate = '';
    public $filterEndDate = '';
    public $filterStatus = '';
    public $tableSearch = '';

    public $table = [];

    // public $tableDetail =[];
    // public $reportKunjungan = [];
    // public $reportTm = [];
    // public $ticketKunjungan = [];

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

    public function mount()
    {
        $this->branches = Branch::orderBy('name')->where('id', '!=', 10)->get();

        $this->filterEndDate = date('Y-m-d');
        $this->filterStartDate = date('Y-m-d');
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

    public function exportReportTicket()
    {
        $reportKunjungan = $this->getReportKunjunganQuery()->get();

        $reportTm = $this->getReportTmQuery()->get();

        return Excel::download(
            new ReportTicketExport(
                $reportKunjungan,
                $reportTm
            ),
            'Report_Ticket_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportTicketKunjunganRepeat()
    {
        $tickets = $this->getTicketKunjunganRepeatQuery()
            ->get();

        return Excel::download(
            new TicketKunjunganRepeatExport($tickets),
            'Ticket_Kunjungan_Berulang_' .
                now()->format('Ymd_His') .
                '.xlsx'
        );
    }

    private function getReportKunjunganQuery()
    {
        $query = ReportKunjungan::with([
            'team',
            'ticket.customer',
            'ticket.branch',
        ])
            ->join(
                'ticket_kunjungan',
                'ticket_kunjungan.id',
                '=',
                'report_kunjungan.ticket_id'
            )
            ->where('ticket_kunjungan.is_gangguan', 1)
            ->when($this->branchId, function ($query) {
                $query->where('ticket_kunjungan.branch_id', $this->branchId);
            })
            ->whereBetween('report_kunjungan.created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ]);

        if ($this->workDuration == 1) {
            $query->whereRaw("
            TIMESTAMPDIFF(
                HOUR,
                ticket_kunjungan.created_at,
                report_kunjungan.created_at
            ) > 4
        ");
        }

        if ($this->workDuration == 2) {
            $query->whereRaw("
            TIMESTAMPDIFF(
                HOUR,
                ticket_kunjungan.created_at,
                report_kunjungan.created_at
            ) <= 4
        ");
        }

        return $query
            ->select('report_kunjungan.*')
            ->latest('report_kunjungan.created_at');
    }
    private function getReportTmQuery()
    {
        $query = ReportTm::with([
            'team',
            'ticket.branch',
        ])
            ->join(
                'ticket_tm',
                'ticket_tm.id',
                '=',
                'report_tm.ticket_id'
            )
            ->where('ticket_tm.is_gangguan', 1)
            ->when($this->branchId, function ($query) {
                $query->where('ticket_tm.branch_id', $this->branchId);
            })
            ->whereBetween('report_tm.created_at', [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ]);

        if ($this->workDuration == 1) {
            $query->whereRaw("
            TIMESTAMPDIFF(
                HOUR,
                ticket_tm.created_at,
                report_tm.created_at
            ) > 4
        ");
        }

        if ($this->workDuration == 2) {
            $query->whereRaw("
            TIMESTAMPDIFF(
                HOUR,
                ticket_tm.created_at,
                report_tm.created_at
            ) <= 4
        ");
        }

        return $query
            ->select('report_tm.*')
            ->latest('report_tm.created_at');
    }
    private function getTicketKunjunganRepeatQuery()
    {
        $query = TicketKunjungan::query()
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
            ->whereNull('ticket_kunjungan.deleted_at')
            ->where('ticket_kunjungan.is_gangguan', 1)

            ->whereIn('ticket_kunjungan.id', function ($sub) {
                $sub->selectRaw('MAX(id)')
                    ->from('ticket_kunjungan')
                    ->whereNull('deleted_at')
                    ->where('is_gangguan', 1)
                    ->groupBy('customer_id');
            });

        if ($this->branchId) {
            $query->where(
                'ticket_kunjungan.branch_id',
                $this->branchId
            );
        }

        $query->whereBetween(
            'ticket_kunjungan.created_at',
            [
                $this->filterStartDate . ' 00:00:00',
                $this->filterEndDate . ' 23:59:59',
            ]
        );

        if ($this->tableSearch) {
            $query->where(function ($query) {
                $query->where(
                    'ticket_kunjungan.ticket_number',
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

        $query->addSelect([
            'repeat_count' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at'),

            'repeat_month' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at')
                ->where('tk2.created_at', '>=', now()->subMonth()),

            'repeat_week' => TicketKunjungan::from('ticket_kunjungan as tk2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('tk2.customer_id', 'ticket_kunjungan.customer_id')
                ->where('tk2.is_gangguan', 1)
                ->whereNull('tk2.deleted_at')
                ->where('tk2.created_at', '>=', now()->subWeek()),
        ]);

        return $query
            ->havingRaw('repeat_count > 1')
            ->with(['customer', 'team']);
    }

    public function render()
    {
        $reportKunjungan = $this->getReportKunjunganQuery()
            ->paginate($this->tableLength, ['*'], 'kunjunganPage');

        $reportTm = $this->getReportTmQuery()
            ->paginate($this->tableLength, ['*'], 'tmPage');

        $ticketKunjungan = $this->getTicketKunjunganRepeatQuery()
            ->paginate(
                $this->tableLength,
                ['*'],
                'ticketPage'
            );

        return view('livewire.report-ticket', [
            'reportKunjungan' => $reportKunjungan,
            'reportTm' => $reportTm,
            'ticketKunjungan' => $ticketKunjungan,
        ]);
    }
}
