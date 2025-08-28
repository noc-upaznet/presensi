@push('styles')
    <style>
        @media (max-width: 425px) {
            /* Header breadcrumb stack */
            .app-content-header .row {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            /* Tombol tambah dan filter stack */
            .pengajuan-toolbar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem;
            }

            .pengajuan-toolbar button,
            .pengajuan-toolbar select,
            .pengajuan-toolbar input {
                width: 100% !important;
                font-size: 0.8rem;
                padding: 0.4rem 0.5rem;
            }

            /* Tabel scrollable & text kecil */
            .table-responsive {
                overflow-x: auto;
            }

            .table th,
            .table td {
                font-size: 0.75rem;
                padding: 0.4rem 0.5rem;
                white-space: nowrap;
            }

            /* Badge dan button lebih kecil */
            .badge,
            .btn-sm {
                font-size: 0.65rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            /* Pagination dan search input stack */
            .pengajuan-table-controls {
                flex-direction: column !important;
                gap: 0.5rem;
                align-items: stretch !important;
            }
        }
    </style>
@endpush
<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Daftar Pengajuan</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

    <div class="container mt-4">
        @if (auth()->user()->current_role == 'user' || auth()->user()->current_role == 'spv' || auth()->user()->current_role == 'hr')
            <div class="d-flex justify-content-between align-items-center mb-3 pengajuan-table-controls flex-wrap">
                <button class="btn btn-primary mb-2 me-2" wire:click="showAdd">
                    <i class="bi bi-plus"></i> Tambah
                </button>
                <div class="d-flex gap-2" align="right">
                    <select class="form-select" wire:model.lazy="filterPengajuan" style="width: 150px;">
                        <option value="">Pilih Status</option>
                        <option value="0">Menunggu</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                    </select>
    
                    <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan" wire:model.lazy="filterBulan">
    
                    {{-- <button class="btn btn-light">Pilih Waktu</button> --}}
                </div>
            </div>
        @endif
        @if (auth()->user()->current_role == 'admin')
            <div class="d-flex justify-content-end align-items-center mb-3">
                <div class="d-flex gap-2">
                    <select class="form-select" wire:model.lazy="selectedKaryawan" style="width: 200px;">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawanList as $karyawan)
                            <option value="{{ $karyawan->nama_karyawan }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select>
                    <select class="form-select" wire:model.lazy="filterPengajuan" style="width: 150px;">
                        <option value="">Pilih Status</option>
                        <option value="0">Menunggu</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                    </select>

                    <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan" wire:model.lazy="filterBulan">
                </div>
            </div>
        @endif
        <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>Show <select class="form-select form-select-sm d-inline-block w-auto">
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                            </select> entries per page</label>
                    </div>
                    <div>
                        <input type="text" class="form-control form-control-sm rounded-end-0" placeholder="Tanggal" wire:model.live="search">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                {{-- <th>Diajukan Pada</th> --}}
                                @if (auth()->user()->current_role != 'user')
                                    <th>Nama</th>
                                @endif
                                <th>Pengajuan</th>
                                <th>Keterangan</th>
                                <th>File</th>
                                <th>Approve</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pengajuans->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                </tr>
                            @else
                                @foreach ($pengajuans as $key)
                                    <tr>
                                        <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                                        {{-- <td style="color: var(--bs-body-color);">{{ $key->created_at }}</td> --}}
                                        @if (auth()->user()->current_role != 'user')
                                            <td style="color: var(--bs-body-color);">{{ $key->getKaryawan->nama_karyawan }}</td>
                                        @endif
                                        <td style="color: var(--bs-body-color);">{{ $key->getShift->nama_shift }}</td>
                                        <td style="color: var(--bs-body-color);">{{ $key->keterangan }}</td>
                                        <td>
                                            @if($key->file && is_string($key->file) && file_exists(public_path('storage/' . $key->file)))
                                                <img src="{{ asset('storage/' . $key->file) }}" 
                                                style="max-width: 100px; cursor: pointer;" data-bs-toggle="modal"
                                                data-bs-target="#modalGambar"
                                                onclick="setModalImage('{{ asset('storage/' . $key->file) }}')">
                                            @else
                                                <span style="color: gray;">-</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--bs-body-color);">
                                            @if ($key->approve_spv == 1)
                                                <span class="badge bg-success">SPV</span>
                                            @elseif ($key->approve_spv == 2)
                                                <span class="badge bg-danger">SPV</span>
                                            @endif
                                            @if ($key->approve_hr == 1)
                                                <span class="badge bg-success">HRD</span>
                                            @elseif ($key->approve_hr == 2)
                                                <span class="badge bg-danger">HRD</span>
                                            @endif
                                            @if ($key->approve_admin == 1)
                                                <span class="badge bg-success">ADMIN</span>
                                            @elseif ($key->approve_admin == 2)
                                                <span class="badge bg-danger">ADMIN</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--bs-body-color);">
                                            {{-- @if ($key->approve_spv == 1 && $key->approve_hr == 1)
                                                <span class="badge bg-success">Diterima</span>
                                            @endif --}}
                                            @if ($key->status == 0)
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif ($key->status == 1)
                                                <span class="badge bg-success">Diterima</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        @if (auth()->user()->current_role == 'user')
                                            @if ($key->approve_spv == 0 && $key->approve_hr == 0)
                                                <td class="text-center" style="color: var(--bs-body-color);">
                                                    <button class="btn btn-sm btn-warning mb-2" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                                </td>
                                            @else
                                                <td class="text-center" style="color: var(--bs-body-color);">
                                                    <button class="btn btn-sm btn-warning mb-2" disabled><i class="fa-solid fa-pen-to-square"></i></button>
                                                </td>
                                            @endif
                                        @endif
                                        @if (auth()->user()->current_role != 'user')
                                            <td class="text-center" style="color: var(--bs-body-color);">
                                                {{-- <button class="btn btn-sm btn-info mb-2" wire:click="showDetail('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-eye"></i></button> --}}
                                                {{-- Tombol Approve SPV --}}
                                                @if (auth()->user()->current_role == 'spv')
                                                    @if ($key->canBeApprovedBySpv())
                                                        <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">
                                                            <i class="bi bi-check-square"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">
                                                            <i class="bi bi-x-square"></i>
                                                        </button>
                                                    @endif

                                                    @if ($key->canBeDeletedBySPV())
                                                        <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete', { id: '{{ Crypt::encrypt($key->id) }}', action: 'show' })">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                                {{-- Tombol Approve HR --}}
                                                @if ($key->canBeApprovedByHr())
                                                    <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">
                                                        <i class="bi bi-check-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">
                                                        <i class="bi bi-x-square"></i>
                                                    </button>
                                                @endif

                                                {{-- Tombol Admin --}}
                                                @if (auth()->user()->current_role == 'admin')
                                                    <div class="table-action-btns">
                                                        @if ($key->canBeApprovedByAdmin())
                                                            <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">
                                                                <i class="bi bi-check-square"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">
                                                                <i class="bi bi-x-square"></i>
                                                            </button>
                                                        @endif

                                                        @if ($key->canBeDeletedByAdmin())
                                                            <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete', { id: '{{ Crypt::encrypt($key->id) }}', action: 'show' })">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                {{-- {{ $pengajuans->links() }} --}}
                </div>
                {{ $pengajuans->links()}}
            </div>
        </div>
    </div>
    <livewire:karyawan.pengajuan.modal-pengajuan />
    <div class="modal fade" id="modalGambar" tabindex="-1" aria-labelledby="modalGambarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGambarLabel">Bukti Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Bukti" class="img-fluid">
            </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('modalTambahPengajuan', (event) => {
            $('#modalTambahPengajuan').modal(event.action);
        });

        Livewire.on('modalEditPengajuan', (event) => {
            $('#modalEditPengajuan').modal(event.action);
        });

        function setModalImage(src) {
            document.getElementById('modalImage').src = src;
        }

        Livewire.on('modal-confirm-delete', (event) => {
            $('#modal-confirm-delete').modal(event.action);
            $('#btn-confirm-delete').attr('wire:click', 'delete("' + event.id + '")');
            $('#modal-confirm-delete').modal('hide');
        });

        Livewire.on('refresh', () => {
            Livewire.dispatch('refreshTable');
        });
    </script>
@endpush