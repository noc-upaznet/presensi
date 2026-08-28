<div>
    <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
        <div class="mb-4">
            <div class="d-flex justify-content gap-2 flex-wrap mb-4">
                @role('admin')
                    <select class="form-select" wire:model.lazy="filterkaryawan" style="width: 150px;">
                        <option value="">Pilih Karyawan</option>
                        @foreach ($karyawanList as $karyawan)
                            <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select>
                @endrole

                <input type="month" class="form-control" style="width: 150px;" placeholder="Bulan"
                    wire:model.lazy="filterBulan">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <label>Show
                        <select class="form-select form-select-sm d-inline-block w-auto" wire:model.live="perPage">
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select> entries per page</label>
                </div>
                <div>
                    <input type="search" class="form-control form-control-sm" placeholder="Search..."
                        wire:model.live="search">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="cursor: pointer;" wire:click="sortBy('nama_karyawan')">
                                Nama Karyawan

                                @if ($sortField === 'nama_karyawan')
                                    @if ($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>

                            <th class="text-center" style="cursor: pointer;" wire:click="sortBy('jumlah_terlambat')">
                                Terlambat

                                @if ($sortField === 'jumlah_terlambat')
                                    @if ($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </th>

                            <th class="text-center">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($datas as $key)
                            <tr>
                                <td style="color: var(--bs-body-color);">
                                    {{ $key->nama_karyawan }}
                                </td>

                                <td class="text-center" style="color: var(--bs-body-color);">
                                    @if ($key->jumlah_terlambat > 0)
                                        <span class="badge bg-danger">
                                            {{ $key->jumlah_terlambat }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            0
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="detail('{{ Crypt::encrypt($key->id) }}')">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $datas->links() }}
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalDetailKeterlambatan" tabindex="-1"
        aria-labelledby="modalDetailKeterlambatanLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="modalDetailKeterlambatanLabel">
                            Detail Keterlambatan
                        </h5>

                        <small>
                            {{ $namaKaryawan }}
                        </small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Clock In</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($detailData as $data)
                                    <tr>
                                        <td>
                                            {{ $data->tanggal }}
                                        </td>

                                        <td>
                                            {{ $data->clock_in ?? '-' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-danger">
                                                Terlambat
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            Tidak ada data keterlambatan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <strong class="me-auto">
                        Total Terlambat:
                        {{ count($detailData) }}
                    </strong>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@script
    <script>
        $wire.on('modalDetailKeterlambatan', () => {
            const modalElement = document.getElementById('modalDetailKeterlambatan');

            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

            modal.show();
        });
    </script>
@endscript
