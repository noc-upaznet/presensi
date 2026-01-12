<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Notifikasi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    {{-- <div class="card-header d-flex justify-content-between align-items-center">
        <strong>🔔 Notifikasi</strong>

        @if (auth()->user()->unreadNotifications->count())
            <button class="btn btn-sm btn-outline-primary" wire:click="markAllAsRead">
                Tandai Semua Dibaca
            </button>
        @endif
    </div> --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" style="background-color: var(--bs-body-bg);">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Tgl Berakhir</th>
                                <th>Sisa</th>
                                <th>Waktu</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($notifications as $notif)
                                @if (($notif->data['type'] ?? '') === 'kontrak_reminder')
                                    <tr class="{{ is_null($notif->read_at) ? 'table-warning' : '' }}">

                                        <td>
                                            {{ $notif->data['nama'] }}
                                        </td>

                                        <td>
                                            <span
                                                class="badge
                                                {{ $notif->data['status'] === 'Probation' ? 'bg-warning' : 'bg-danger' }}">
                                                {{ $notif->data['status'] }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($notif->data['tgl_keluar'])->format('d M Y') }}
                                        </td>

                                        <td>
                                            <strong>{{ $notif->data['sisa_hari'] }}</strong> hari
                                        </td>

                                        <td class="text-muted">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </td>

                                        <td class="text-center">
                                            @if (is_null($notif->read_at))
                                                <button class="btn btn-primary btn-sm"
                                                    wire:click="markAsRead('{{ $notif->id }}')">
                                                    Tandai Dibaca
                                                </button>
                                            @else
                                                <span class="badge bg-success">
                                                    Sudah Dibaca
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Tidak ada notifikasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
