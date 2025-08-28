@push('styles')
    <style>
        @media (max-width: 425px) {
            /* Tombol Tambah dan Filter agar stack ke bawah */
            .lembur-header {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 1rem;
            }

            .lembur-header .form-select,
            .lembur-header .form-control,
            .lembur-header button {
                width: 100% !important;
            }

            /* Pagination & search */
            .lembur-table-controls {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.75rem;
            }

            /* Table scrollable */
            .table-responsive {
                overflow-x: auto;
            }

            /* Perkecil font tabel agar pas di layar kecil */
            .table th,
            .table td {
                font-size: 0.75rem;
                white-space: nowrap;
            }

            /* Badge atau tombol di action supaya nggak meluber */
            .table .btn,
            .table .badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.4rem;
            }

            /* Modal image full width */
            #modalImage {
                max-width: 100%;
                height: auto;
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
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Daftar Pengajuan Lembur</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan Lembur</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-end align-items-center mb-3 flex-wrap gap-2 lembur-header">
            @if (auth()->user()->current_role == 'spv' || auth()->user()->current_role == 'hr' || auth()->user()->current_role == 'user')
                <button class="btn btn-primary" wire:click="showAdd">
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
                </div>
            @endif
            @if (auth()->user()->current_role == 'admin')
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
            @endif
        </div>
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
                                @if (auth()->user()->current_role == 'admin' || auth()->user()->current_role == 'hr' || auth()->user()->current_role == 'spv')
                                    <th>Nama Karyawan</th>
                                @endif
                                <th>Jenis Lembur</th>
                                <th>Waktu Lembur</th>
                                <th>Keterangan</th>
                                <th>File</th>
                                <th>Approve</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pengajuanLembur->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                </tr>
                            @else
                                @foreach ($pengajuanLembur as $key)
                                    <tr>
                                        <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                                        {{-- <td style="color: var(--bs-body-color);">{{ $key->created_at }}</td> --}}
                                        @if (auth()->user()->current_role == 'admin' || auth()->user()->current_role == 'hr' || auth()->user()->current_role == 'spv')
                                            <td style="color: var(--bs-body-color);">{{ $key->getKaryawan->nama_karyawan }}</td>
                                        @endif
                                        <td style="color: var(--bs-body-color);">
                                            @if ($key->jenis == 1)
                                                Hari Biasa
                                            @elseif ($key->jenis == 2)
                                                Hari Libur
                                            @endif
                                        </td>
                                        <td style="color: var(--bs-body-color);">{{ $key->waktu_mulai }} - {{ $key->waktu_akhir }}</td>
                                        <td style="color: var(--bs-body-color);">{{ $key->keterangan }}</td>
                                        <td style="color: var(--bs-body-color);">
                                            @if ($key->file_bukti)
                                                <img src="{{ asset('storage/' . $key->file_bukti) }}"
                                                    alt="Bukti Lembur"
                                                    style="max-width: 100px; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalGambar"
                                                    onclick="setModalImage('{{ asset('storage/' . $key->file_bukti) }}')">
                                            @else
                                                -
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
                                            @endif
                                        </td>
                                        
                                        <td style="color: var(--bs-body-color);">
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
                                                    <button class="btn btn-sm btn-warning mb-2" disabled wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                                </td>
                                            @endif
                                        @endif
                                        {{-- @if (auth()->user()->current_role != 'user')
                                            <td class="text-center" style="color: var(--bs-body-color);">
                                            <!--<button class="btn btn-sm btn-info mb-2" wire:click="showEdit({{ $key->id }})"><i class="fa fa-eye"></i></button>-->
        
                                                @if (auth()->user()->current_role == 'spv')
                                                    @if ($key->status == 0)
                                                        @if ($key->approve_spv == null)
                                                            <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)"><i class="bi bi-check-square"></i></button>
                                                            <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)"><i class="bi bi-x-square"></i></button>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if (auth()->user()->current_role == 'hr')
                                                    @if ($key->status == 0)
                                                        @if ($key->approve_hr == null)
                                                            <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)"><i class="bi bi-check-square"></i></button>
                                                            <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)"><i class="bi bi-x-square"></i></button>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if (auth()->user()->current_role == 'admin')
                                                    <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endif --}}
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
                                                @endif

                                                @if ($key->canBeDeletedBySPV())
                                                    <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete', { id: '{{ Crypt::encrypt($key->id) }}', action: 'show' })">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
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
                                                            <button class="btn btn-sm btn-danger" wire:click="$dispatch('modal-confirm-delete', { id: '{{ Crypt::encrypt($key->id) }}', action: 'show' })">
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
                </div>
            </div>
            {{ $pengajuanLembur->links() }}
        </div>
        <livewire:karyawan.pengajuan.modal-pengajuan-lembur />
    </div>
    <div class="modal fade" id="modalGambar" tabindex="-1" aria-labelledby="modalGambarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGambarLabel">Bukti Lembur</h5>
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
        Livewire.on('modalTambahPengajuanLembur', (event) => {
            $('#modalTambahPengajuanLembur').modal(event.action);
        });

        Livewire.on('modalEditPengajuanLembur', (event) => {
            $('#modalEditPengajuanLembur').modal(event.action);
        });

        // Livewire.on('modalEditJadwal', (event) => {
        //     $('#modalEditJadwal').modal(event.action);
        // });
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
