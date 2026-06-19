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

    <div class="container-fluid">

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
                        <input type="search" class="form-control form-control-sm" placeholder="Search..."
                            wire:model.live="search">
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

                                        @if (($report->ticket->is_gangguan ?? 0) == 1)
                                            <span class="badge bg-danger">
                                                Gangguan
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
                                            $ticketCreated = \Carbon\Carbon::parse($report->ticket->created_at);
                                            $reportCreated = \Carbon\Carbon::parse($report->created_at);

                                            $diff = $ticketCreated->diff($reportCreated);
                                        @endphp

                                        {{ $diff->format('%a Hari %h Jam %i Menit') }}
                                    </td>

                                    <td>
                                        {{ $report->team->name ?? '-' }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $report->teknisi }}
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
                            <input type="search" class="form-control form-control-sm" placeholder="Search..."
                                wire:model.live="search">
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

                                            @if (($report->ticket->is_gangguan ?? 0) == 1)
                                                <span class="badge bg-danger">
                                                    Gangguan
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
                                                $ticketCreated = \Carbon\Carbon::parse($report->ticket->created_at);
                                                $reportCreated = \Carbon\Carbon::parse($report->created_at);

                                                $diff = $ticketCreated->diff($reportCreated);
                                            @endphp

                                            {{ $diff->format('%a Hari %h Jam %i Menit') }}
                                        </td>

                                        <td>
                                            {{ $report->team->name ?? '-' }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $report->teknisi }}
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
                </div>
            </div>
