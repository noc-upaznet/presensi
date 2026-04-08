<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Daftar Karyawan Absen</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Karyawan Absen</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                @role('admin|hr')
                    <select class="form-select" wire:model.lazy="filterDivisi" style="width: 150px;">
                        <option value="">Pilih Divisi</option>
                        @foreach ($divisiList as $divisi)
                            <option value="{{ $divisi->nama }}">{{ $divisi->nama }}</option>
                        @endforeach
                    </select>
                @endrole
                <select class="form-select" wire:model.live="mode" style="width: 220px;">
                    <option value="all">Tanpa Keterangan</option>
                    <option value="pengajuan">Pengajuan</option>
                </select>
            </div>
        </div>
        <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>Show <select class="form-select form-select-sm d-inline-block w-auto"
                                wire:model.live="perPage">
                                <option selected>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select> entries per page</label>
                    </div>
                    <div>
                        <input type="text" class="form-control form-control-sm rounded-end-0"
                            placeholder="Nama Karyawan" wire:model.live="search">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Pengajuan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($datas as $item)
                                <tr>
                                    <td>{{ $item->nama_karyawan }}</td>
                                    <td>{{ $item->jabatan ?? '-' }}</td>
                                    <td>
                                        @php
                                            $punyaPengajuan = \App\Models\M_Pengajuan::where('karyawan_id', $item->id)
                                                ->whereIn('status', [0, 1])
                                                ->whereDate('tanggal', now())
                                                ->exists();
                                        @endphp

                                        @if ($punyaPengajuan)
                                            <span
                                                class="badge bg-warning">{{ $item->pengajuanHariIni?->getShift?->nama_shift ?? '-' }}</span>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $punyaPengajuan = \App\Models\M_Pengajuan::where('karyawan_id', $item->id)
                                                ->whereIn('status', [0, 1])
                                                ->whereDate('tanggal', now())
                                                ->exists();
                                        @endphp

                                        @if ($punyaPengajuan)
                                            <span>{{ $item->pengajuanHariIni?->keterangan ?? '-' }}</span>
                                        @else
                                            <span>Belum Hadir/Alpha/Libur</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        Tidak ada data.
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
    </div>
</div>
