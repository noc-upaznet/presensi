<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Kasbon</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kasbon</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" wire:click="showAdd">
                <i class="bi bi-plus"></i> Tambah
            </button>

            {{-- <div class="d-flex gap-2">
                <select class="form-select" style="width: 150px;" wire:model="filterKaryawan"
                    wire:change="filterByKaryawan($event.target.value)">
                    <option value="" selected>Pilih Karyawan</option>
                    @foreach ($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                    @endforeach
                </select>

                <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan"
                    wire:model.lazy="filterBulan">
            </div> --}}
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover" style="background-color: var(--bs-body-bg);">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Total & Sisa</th>
                        <th>Progress Angsuran</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kasbons as $item)
                        <tr wire:key="kasbon-{{ $item->id }}">
                            <!-- BULAN MULAI -->
                            <td style="color: var(--bs-body-color);">
                                {{ \Carbon\Carbon::parse($item->mulai_potong)->translatedFormat('M Y') }}
                            </td>

                            <!-- NAMA KARYAWAN -->
                            <td style="color: var(--bs-body-color);">
                                {{ $item->karyawan->nama_karyawan ?? '-' }}
                            </td>

                            <!-- TOTAL & SISA -->
                            <td>
                                <div class="small text-muted">
                                    Total: Rp {{ number_format($item->total_kasbon, 0, ',', '.') }}
                                </div>
                                <div class="fw-semibold">
                                    Sisa: Rp {{ number_format($item->sisa_kasbon, 0, ',', '.') }}
                                </div>
                            </td>

                            <!-- PROGRESS ANGSURAN -->
                            <td style="width:200px;">
                                @php
                                    $persen =
                                        $item->total_kasbon > 0
                                            ? 100 - ($item->sisa_kasbon / $item->total_kasbon) * 100
                                            : 0;
                                @endphp

                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $persen }}%">
                                    </div>
                                </div>

                                <small class="text-muted">
                                    {{ number_format($persen, 0) }}% lunas
                                </small>
                            </td>

                            <!-- STATUS -->
                            <td>
                                @if ($item->status === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Aktif</span>
                                @endif
                            </td>

                            <!-- ACTION -->
                            <td class="text-center">
                                <button class="btn btn-sm btn-info text-white"
                                    wire:click="showDetail({{ $item->id }})">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" wire:click="showEdit({{ $item->id }})">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $item->id }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data kasbon
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{-- {{ $jadwals->links() }} --}}
        </div>
    </div>

    <!-- MODAL FORM KASBON -->
    <div class="modal fade" id="modal-add-edit" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEdit ? 'Edit Kasbon' : 'Form Kasbon' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- INFORMASI KARYAWAN -->
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Informasi Karyawan</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Pilih Karyawan</label>
                                <select class="form-select" wire:model.live="form.karyawan_id">
                                    <option value="">-- pilih --</option>
                                    @foreach ($karyawans as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_karyawan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($selectedKaryawan)
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Jabatan</small>
                                        <div class="fw-semibold">{{ $selectedKaryawan->jabatan }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Gaji Pokok</small>
                                        @php
                                            $gajiPokok =
                                                $selectedKaryawan->gaji_pokok + $selectedKaryawan->tunjangan_jabatan;
                                        @endphp
                                        <div class="fw-semibold">
                                            Rp {{ number_format($gajiPokok, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- DETAIL KASBON -->
                    <div class="card mb-3">
                        <div class="card-header fw-bold">Detail Kasbon</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Total Kasbon</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" oninput="formatRupiah(this)"
                                        wire:model.lazy="form.total_kasbon">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jumlah Angsuran (bulan)</label>
                                    <input type="number" class="form-control" min="1"
                                        wire:model.lazy="form.jml_angsuran">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kasbon Perbulan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" wire:model="form.kasbon_perbulan"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Kasbon</label>
                                    <input type="date" class="form-control" wire:model="form.tanggal_kasbon">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mulai Potong Payroll</label>
                                    <input type="month" class="form-control" wire:model="form.mulai_potong">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- KETERANGAN -->
                    <div class="card">
                        <div class="card-header fw-bold">Keterangan</div>
                        <div class="card-body">
                            <textarea class="form-control" rows="3" wire:model="form.keterangan"></textarea>
                        </div>
                    </div>

                    <!-- RINGKASAN -->
                    @if ($form['total_kasbon'] && $form['kasbon_perbulan'])
                        <div class="alert alert-info mt-3">
                            <div><strong>Ringkasan:</strong></div>
                            <div>Total Kasbon: Rp {{ $form['total_kasbon'] }}</div>
                            <div>Angsuran: {{ $form['jml_angsuran'] }} bulan</div>
                            <div>Potongan/bulan: Rp {{ $form['kasbon_perbulan'] }}</div>
                        </div>
                    @endif

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeForm">Batal</button>
                    <button class="btn btn-primary" wire:click="save">
                        {{ $isEdit ? 'Update' : 'Simpan' }}
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL DETAIL KASBON -->
    <div class="modal fade" id="modal-detail" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">Detail Kasbon</h5>
                    <button type="button" class="btn-close"data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    @if ($detailKasbon)

                        <!-- INFO UTAMA -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Nama Karyawan</small>
                                <div class="fw-semibold">
                                    {{ $detailKasbon->karyawan->nama_karyawan ?? '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted">Status</small><br>
                                @if ($detailKasbon->status === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning text-dark">Aktif</span>
                                @endif
                            </div>
                        </div>

                        <!-- NOMINAL -->
                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted">Total Kasbon</small>
                                        <div class="fw-semibold">
                                            Rp {{ number_format($detailKasbon->total_kasbon, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted">Kasbon Perbulan</small>
                                        <div class="fw-semibold">
                                            Rp {{ number_format($detailKasbon->kasbon_perbulan, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted">Sisa Kasbon</small>
                                        <div class="fw-semibold text-danger">
                                            Rp {{ number_format($detailKasbon->sisa_kasbon, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- PROGRESS -->
                        @php
                            $persen =
                                $detailKasbon->total_kasbon > 0
                                    ? 100 - ($detailKasbon->sisa_kasbon / $detailKasbon->total_kasbon) * 100
                                    : 0;
                        @endphp

                        <div class="mb-3">
                            <div class="progress" style="height:10px;">
                                <div class="progress-bar" style="width: {{ $persen }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format($persen, 0) }}% lunas</small>
                        </div>

                        <!-- RIWAYAT POTONGAN -->
                        <h6 class="fw-bold mt-3">Riwayat Potongan</h6>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Nominal Potong</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detailRiwayat as $row)
                                        <tr>
                                            <td>
                                                {{ \Carbon\Carbon::parse($row->periode)->translatedFormat('M Y') }}
                                            </td>
                                            <td>
                                                Rp {{ number_format($row->nominal_potong, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">
                                                Belum ada potongan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    @endif
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeDetail">Tutup</button>
                </div>

            </div>
        </div>
    </div>

    @if ($showDetails)
        <div class="modal fade"></div>
    @endif


</div>

@push('scripts')
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        Livewire.on('modal-add-edit', (event) => {
            $('#modal-add-edit').modal(event.action);
        });

        Livewire.on('modal-detail', (event) => {
            $('#modal-detail').modal(event.action);
        });
    </script>
@endpush
