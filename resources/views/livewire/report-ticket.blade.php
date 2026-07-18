<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Laporan Tiket</h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Laporan Tiket
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="background-color: var(--bs-body-bg);">
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold @if ($tab == 'ReportTicket') active @endif"
                        wire:click='setTab("ReportTicket")' id="ReportTicket-tab" data-bs-toggle="tab"
                        data-bs-target="#ReportTicket" data-tab-name="ReportTicket" type="button" role="tab"
                        aria-controls="ReportTicket" aria-selected="true">Laporan Tiket</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold @if ($tab == 'TicketKunjunganRepeat') active @endif"
                        wire:click='setTab("TicketKunjunganRepeat")' id="TicketKunjunganRepeat-tab" data-bs-toggle="tab"
                        data-bs-target="#TicketKunjunganRepeat" data-tab-name="TicketKunjunganRepeat" type="button"
                        role="tab" aria-controls="TicketKunjunganRepeat" aria-selected="true">Tiket Kunjungan
                        Berulang</button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade @if ($tab == 'ReportTicket') active show @endif " id="ReportTicket"
                    role="tabpanel" aria-labelledby="ReportTicket-tab">

                    @if ($tab == 'ReportTicket')
                        <button wire:click="exportReportTicket" class="btn btn-danger mb-3"><i
                                class="fa-solid fa-file-excel"></i>
                            Export</button>
                        {{-- Filter --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3">
                                        <label class="form-label">Branch</label>
                                        <select class="form-select" wire:model.live="branchId">
                                            <option value="">-- Pilih Branch --</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Lama Pengerjaan</label>
                                        <select class="form-select" wire:model.live="workDuration">
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Lebih Dari 4 Jam</option>
                                            <option value="2">Kurang Dari 4 Jam</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Awal</label>
                                        <input type="date" class="form-control" wire:model.live="filterStartDate">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" wire:model.live="filterEndDate">
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- KUNJUNGAN --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Kunjungan</h5>
                            </div>

                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <label>Show
                                            <select class="form-select form-select-sm d-inline-block w-auto"
                                                wire:model.live="tableLength">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="250">250</option>
                                            </select> entries per page</label>
                                    </div>
                                    <div>
                                        <input type="search" class="form-control form-control-sm"
                                            placeholder="Search..." wire:model.live="search">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No. Tiket</th>
                                                <th>ID | Nama Pelanggan</th>
                                                <th>Tiket Dibuat</th>
                                                <th>Waktu</th>
                                                <th>Lama Pengerjaan</th>
                                                <th>Team</th>
                                                <th>Detail Pengerjaan</th>
                                                <th>Data Yang Berubah</th>
                                                <th>Barang Yang Digunakan</th>
                                                <th>Biaya</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($reportKunjungan as $report)
                                                <tr>
                                                    <td>
                                                        {{ $report->ticket->ticket_number ?? '-' }}
                                                        <br>

                                                        @if (($report->ticket->is_gangguan ?? 0) == 1)
                                                            <span class="badge bg-danger">
                                                                Gangguan
                                                            </span>
                                                        @endif
                                                        @if (($report->is_dispensation ?? 0) == 1)
                                                            <span class="badge bg-success">
                                                                Dispensasi
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{ $report->ticket->customer->registration_number ?? '-' }}
                                                        |
                                                        {{ $report->ticket->customer->name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $report->ticket->created_at }}
                                                    </td>

                                                    <td>
                                                        {{ $report->created_at }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $ticketCreated = \Carbon\Carbon::parse(
                                                                $report->ticket->created_at,
                                                            );
                                                            $reportCreated = \Carbon\Carbon::parse($report->created_at);

                                                            $diff = $ticketCreated->diff($reportCreated);
                                                        @endphp

                                                        {{ $diff->format('%a Hari %h Jam %i Menit') }}
                                                    </td>

                                                    <td>
                                                        {{ $report->team->name ?? '-' }}
                                                        <br>
                                                        <small>
                                                            ({{ ' ' . $report->teknisi . ' ' }})
                                                        </small>
                                                    </td>

                                                    <td>
                                                        {!! nl2br(e($report->detail_report)) !!}
                                                    </td>

                                                    <td>
                                                        {!! nl2br(e($report->changed_data)) !!}
                                                    </td>

                                                    <td>
                                                        {!! nl2br(e($report->goods)) !!}
                                                    </td>

                                                    <td class="text-end">
                                                        Rp {{ number_format($report->bill ?? 0, 0, ',', '.') }}
                                                    </td>

                                                    <td>
                                                        @switch($report->status)
                                                            @case(1)
                                                                <span class="badge bg-success">
                                                                    Done
                                                                </span>
                                                            @break

                                                            @case(2)
                                                                <span class="badge bg-warning">
                                                                    Lost Time
                                                                </span>
                                                            @break

                                                            @case(3)
                                                                <span class="badge bg-secondary">
                                                                    Pending
                                                                </span>
                                                            @break

                                                            @default
                                                                <span class="badge bg-dark">
                                                                    Unknown
                                                                </span>
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted">
                                                            Tidak ada data kunjungan
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-3 p-2">
                                    {{ $reportKunjungan->links() }}
                                </div>
                            </div>

                            {{-- T&M --}}
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">T&M</h5>
                                </div>

                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <label>Show
                                                <select class="form-select form-select-sm d-inline-block w-auto"
                                                    wire:model.live="tableLength">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                    <option value="250">250</option>
                                                </select> entries per page</label>
                                        </div>
                                        <div>
                                            <input type="search" class="form-control form-control-sm"
                                                placeholder="Search..." wire:model.live="search">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No. Tiket</th>
                                                    <th>Tiket Dibuat</th>
                                                    <th>Waktu</th>
                                                    <th>Lama Pengerjaan</th>
                                                    <th>Team</th>
                                                    <th>Detail Pengerjaan</th>
                                                    <th>Barang Yang Digunakan</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse ($reportTm as $report)
                                                    <tr>
                                                        <td>
                                                            {{ $report->ticket->ticket_number ?? '-' }}
                                                            <br>
                                                            @if (($report->ticket->is_gangguan ?? 0) == 1)
                                                                <span class="badge bg-danger">
                                                                    Gangguan
                                                                </span>
                                                            @endif

                                                            @if (($report->is_dispensation ?? 0) == 1)
                                                                <span class="badge bg-success">
                                                                    Dispensasi
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $report->ticket->created_at }}
                                                        </td>

                                                        <td>
                                                            {{ $report->created_at }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $ticketCreated = \Carbon\Carbon::parse(
                                                                    $report->ticket->created_at,
                                                                );
                                                                $reportCreated = \Carbon\Carbon::parse(
                                                                    $report->created_at,
                                                                );

                                                                $diff = $ticketCreated->diff($reportCreated);
                                                            @endphp

                                                            {{ $diff->format('%a Hari %h Jam %i Menit') }}
                                                        </td>

                                                        <td>
                                                            {{ $report->team->name ?? '-' }}
                                                            <br>
                                                            <small>
                                                                ({{ ' ' . $report->teknisi . ' ' }})
                                                            </small>
                                                        </td>

                                                        <td>
                                                            {!! nl2br(e($report->detail_report)) !!}
                                                        </td>

                                                        <td>
                                                            {!! nl2br(e($report->goods)) !!}
                                                        </td>

                                                        <td>
                                                            @switch($report->status)
                                                                @case(1)
                                                                    <span class="badge bg-success">
                                                                        Done
                                                                    </span>
                                                                @break

                                                                @case(2)
                                                                    <span class="badge bg-warning">
                                                                        Lost Time
                                                                    </span>
                                                                @break

                                                                @case(3)
                                                                    <span class="badge bg-secondary">
                                                                        Pending
                                                                    </span>
                                                                @break

                                                                @default
                                                                    <span class="badge bg-dark">
                                                                        Unknown
                                                                    </span>
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted">
                                                                Tidak ada data T&M
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-3 p-2">
                                        {{ $reportTm->links() }}
                                    </div>
                                </div>
                        </div>
                        @endif
                    </div>

                </div>

                <div class="tab-pane fade @if ($tab == 'TicketKunjunganRepeat') active show @endif " id="TicketKunjunganRepeat"
                    role="tabpanel" aria-labelledby="TicketKunjunganRepeat-tab">
                    <button wire:click="exportTicketKunjunganRepeat" class="btn btn-danger mb-3"><i
                            class="fa-solid fa-file-excel"></i>
                        Export</button>
                    {{-- Filter --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <label class="form-label">Branch</label>
                                    <select class="form-select" wire:model.live="branchId">
                                        <option value="">-- Pilih Branch --</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Awal</label>
                                    <input type="date" class="form-control" wire:model.live="filterStartDate">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" wire:model.live="filterEndDate">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Kunjungan</h5>
                        </div>

                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label>Show
                                        <select class="form-select form-select-sm d-inline-block w-auto"
                                            wire:model.live="tableLength">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="250">250</option>
                                        </select> entries per page</label>
                                </div>
                                <div>
                                    <input type="search" class="form-control form-control-sm" placeholder="Search..."
                                        wire:model.live="search">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2">No. Tiket</th>
                                            <th rowspan="2">Waktu</th>
                                            <th rowspan="2">ID Pelanggan</th>
                                            <th rowspan="2">Nama Pelanggan</th>
                                            <th rowspan="2">Keterangan</th>
                                            <th rowspan="2">Keterangan Tambahan</th>
                                            <th rowspan="2">Team</th>
                                            <th rowspan="2">Status</th>
                                            <th rowspan="2">Approval</th>

                                            <th colspan="2" class="text-center">
                                                Kunjungan Berulang
                                            </th>
                                        </tr>

                                        <tr>
                                            <th class="text-center">1 Bulan</th>
                                            <th class="text-center">1 Minggu</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($ticketKunjungan as $ticket)
                                            @php
                                                $id = Crypt::encrypt($ticket->id);
                                                $status = [
                                                    '0' => '',
                                                    '1' => 'ON-PROGRESS',
                                                    '2' => 'PENDING',
                                                    '3' => 'CANCEL',
                                                    '4' => 'REPORTED',
                                                    '5' => 'LOST-TIME',
                                                    '6' => 'CHECK',
                                                    '7' => 'RESCHEDULE',
                                                    '8' => 'CONFIRM',
                                                    '9' => 'DONE',
                                                ];

                                                $approve_role = [
                                                    'approve_teknis' => 'SPV Teknisi',
                                                    'approve_noc' => 'NOC',
                                                    'approve_stock' => 'ASL',
                                                    'approve_billing' => 'Billing',
                                                    'approve_data' => 'CS',
                                                ];
                                                $user = Auth::user();
                                            @endphp
                                            <tr wire:key='{{ $id }}'>
                                                <td>{{ $ticket->ticket_number }}
                                                    @if ($ticket->is_gangguan)
                                                        <span class="badge bg-danger">
                                                            Gangguan
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $ticket->created_at }}</td>
                                                <td>{{ $ticket->customer->registration_number ?? 'Terhapus' }}
                                                    @if ($ticket->customer->media_connection ?? 'Terhapus')
                                                        @switch($ticket->customer->media_connection?? 'Terhapus')
                                                            @case('1')
                                                                <span class="badge bg-info">GPON</span>
                                                            @break

                                                            @case('2')
                                                                <span class="badge bg-info">Wireless</span>
                                                            @break

                                                            @case('3')
                                                                <span class="badge bg-info">Tarik</span>
                                                            @break

                                                            @case('4')
                                                                <span class="badge bg-info">Titip</span>
                                                            @break

                                                            @default
                                                        @endswitch
                                                    @endif
                                                </td>
                                                <td>{{ $ticket->customer->name ?? 'Terhapus' }}</td>
                                                <td>{!! nl2br($ticket->description) !!}</td>
                                                <td>{!! nl2br($ticket->additional) !!}</td>
                                                <td>{{ $ticket->team->name ?? '' }}</td>
                                                <td>{{ $status[$ticket->status] }}</td>
                                                <td>

                                                    @php
                                                        $approve['ticket'] =
                                                            '<span class="badge rounded-pill bg-warning text-dark">Menunggu Di-Approve</span>';
                                                        if ($ticket->approve_billing) {
                                                            $approve['ticket'] =
                                                                '<span class="badge rounded-pill bg-success">Sudah Di-Approve</span>';
                                                        } else {
                                                            if ($user->hasRole(['Billing'])) {
                                                                if (!$ticket->approve_data) {
                                                                    if ($user->hasRole('CS')) {
                                                                        $approve['ticket'] =
                                                                            '<span class="badge rounded-pill bg-danger">Butuh Di-Approve</span>';
                                                                    }
                                                                }
                                                            }
                                                        }

                                                    @endphp
                                                    Ticket: {!! $approve['ticket'] !!}<br />
                                                    @if ($ticket->hasReport())
                                                        @php
                                                            $approve['report'] =
                                                                '<span class="badge rounded-pill bg-warning text-dark">Menunggu Di-Approve</span>';
                                                            if ($ticket->allReportApproved()) {
                                                                $approve['report'] =
                                                                    '<span class="badge rounded-pill bg-success">Sudah Di-Approve</span>';
                                                            } else {
                                                                if (
                                                                    $user->hasRole([
                                                                        'SPV Teknisi',
                                                                        'Billing',
                                                                        'CS',
                                                                        'NOC',
                                                                        'ASL',
                                                                    ])
                                                                ) {
                                                                    $report_approve = $ticket->checkApprovalReport();

                                                                    foreach ($report_approve as $key => $value) {
                                                                        if (!$value) {
                                                                            if ($user->hasRole($approve_role[$key])) {
                                                                                $approve['report'] =
                                                                                    '<span class="badge rounded-pill bg-danger">Butuh Di-Approve</span>';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        Laporan: {!! $approve['report'] !!}
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="fw-bold">
                                                            {{ $ticket->repeat_month }}
                                                        </span>

                                                        <button class="btn btn-sm btn-info"
                                                            wire:click="showRepeatDetail({{ $ticket->customer_id }}, 'month')"
                                                            title="Lihat Detail">
                                                            <i class="bi bi-list-task"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="fw-bold">
                                                            {{ $ticket->repeat_week }}
                                                        </span>

                                                        <button class="btn btn-sm btn-info"
                                                            wire:click="showRepeatDetail({{ $ticket->customer_id }}, 'week')"
                                                            title="Lihat Detail">
                                                            <i class="bi bi-list-task"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                            <div class="mt-3 p-2">
                                {{ $ticketKunjungan->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="repeatDetailModal" tabindex="-1">

                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $repeatTitle }}
                            </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <table class="table table-bordered">
                                <thead>
                                    @php
                                        $status = [
                                            '0' => '',
                                            '1' => 'ON-PROGRESS',
                                            '2' => 'PENDING',
                                            '3' => 'CANCEL',
                                            '4' => 'REPORTED',
                                            '5' => 'LOST-TIME',
                                            '6' => 'CHECK',
                                            '7' => 'RESCHEDULE',
                                            '8' => 'CONFIRM',
                                            '9' => 'DONE',
                                        ];
                                    @endphp
                                    <tr>
                                        <th>No Tiket</th>
                                        <th>Tanggal</th>
                                        <th>ID Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Keterangan</th>
                                        <th>Keterangan Tambahan</th>
                                        <th>Team</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($repeatDetails as $item)
                                        <tr>
                                            <td>{{ $item->ticket_number }}</td>

                                            <td>{{ $item->created_at }}</td>

                                            <td>
                                                {{ $item->customer->registration_number ?? 'Terhapus' }}

                                                @if ($item->customer?->media_connection)
                                                    @switch($item->customer->media_connection)
                                                        @case('1')
                                                            <span class="badge bg-info">GPON</span>
                                                        @break

                                                        @case('2')
                                                            <span class="badge bg-info">Wireless</span>
                                                        @break

                                                        @case('3')
                                                            <span class="badge bg-info">Tarik</span>
                                                        @break

                                                        @case('4')
                                                            <span class="badge bg-info">Titip</span>
                                                        @break
                                                    @endswitch
                                                @endif
                                            </td>

                                            <td>
                                                {{ $item->customer->name ?? 'Terhapus' }}
                                            </td>

                                            <td>
                                                {!! nl2br(e($item->description)) !!}
                                            </td>

                                            <td>
                                                {!! nl2br(e($item->additional)) !!}
                                            </td>

                                            <td>
                                                {{ $item->team->name ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $status[$item->status] ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
            <script>
                window.addEventListener('show-repeat-detail', () => {
                    $('#repeatDetailModal').modal('show');
                });
            </script>
        @endpush
