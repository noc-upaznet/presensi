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
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Daftar Pengajuan Dispensasi</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan Dispensasi</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 lembur-header">
            @hasanyrole('user|spv|hr|branch-manager')
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
            @endhasanyrole
            @role('admin')
                <div class="d-flex gap-2">
                    {{-- <select class="form-select" wire:model.lazy="selectedKaryawan" style="width: 200px;">
                        <option value="">Pilih Karyawan</option>
                        @foreach($karyawanList as $karyawan)
                            <option value="{{ $karyawan->nama_karyawan }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select> --}}
                    <select class="form-select" wire:model.lazy="filterPengajuan" style="width: 150px;">
                        <option value="">Pilih Status</option>
                        <option value="0">Menunggu</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                    </select>

                    <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan" wire:model.lazy="filterBulan">
                </div>
            @endrole
        </div>
        <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>
                            Show 
                            <select wire:model="perPage" class="form-select form-select-sm d-inline-block w-auto">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select> 
                            entries per page
                        </label>
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
                                @hasanyrole('admin|hr')
                                    <th>Nama Karyawan</th>
                                @endhasanyrole
                                <th>Keterangan</th>
                                <th>File</th>
                                <th>Approve</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pengajuanDispens->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                </tr>
                            @else
                                @foreach ($pengajuanDispens as $key)
                                    <tr>
                                        <td style="color: var(--bs-body-color);">{{ $key->date }}</td>
                                        @hasanyrole('admin|hr')
                                            <td style="color: var(--bs-body-color);">{{ $key->getKaryawan->nama_karyawan }}</td>
                                        @endhasanyrole
                                        <td style="color: var(--bs-body-color);">{{ $key->description }}</td>
                                        <td style="color: var(--bs-body-color);">
                                            @if ($key->file)
                                                <img src="{{ asset('storage/' . $key->file) }}"
                                                    alt="Bukti Lembur"
                                                    style="max-width: 100px; cursor: pointer;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalGambar"
                                                    onclick="setModalImage('{{ asset('storage/' . $key->file) }}')">
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="color: var(--bs-body-color);">
                                            @if ($key->approve_hr == 1)
                                                <span class="badge bg-success">HRD</span>
                                            @elseif ($key->approve_hr == 2)
                                                <span class="badge bg-danger">HRD</span>
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
                                        <td class="text-center" style="color: var(--bs-body-color);">
                                            @role('hr')
                                                @if ($key->canBeApprovedByHr())
                                                    <button class="btn btn-sm btn-primary text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">
                                                        <i class="bi bi-check-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">
                                                        <i class="bi bi-x-square"></i>
                                                    </button>
                                                    {{-- <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete', { id: '{{ Crypt::encrypt($key->id) }}', action: 'show' })">
                                                        <i class="bi bi-trash"></i>
                                                    </button> --}}
                                                @endif
                                            @endrole
                                            @can('pengajuan-edit')
                                                @if ($key->approve_spv == 0 && $key->approve_hr == 0)
                                                    <button class="btn btn-sm btn-warning mb-2" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                                @else
                                                    <button class="btn btn-sm btn-warning mb-2" disabled><i class="fa-solid fa-pen-to-square"></i></button>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                {{ $pengajuanDispens->links() }}
            </div>
        </div>
        
        <div wire:ignore.self class="modal fade" id="modalTambahPengajuan" tabindex="-1" aria-labelledby="modalTambahPengajuanLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Dispensasi Keterlambatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <form>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label fw-semibold">Tanggal <small class="text-danger">*</small></label>
                                <input type="date" class="form-control" id="tanggal" wire:model="form.date">
                                @error('form.date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="keterangan" placeholder="Contoh: Ban bocor" wire:model="form.description">
                                @error('form.description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label fw-semibold">File</label>
                                <input type="file" class="form-control" id="file" wire:model="file" accept=".jpg,.jpeg,.png">
                                <small class="text-danger">
                                    @if (session()->has('error'))
                                        {{ session('error') }}
                                    @else
                                        Ukuran maksimal file: 2MB
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary  w-100 w-md-auto" wire:click='store' wire:loading.attr="disabled" wire:target="store">
                                <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span wire:loading.remove wire:target="store"><i class="fa fa-save"></i> Simpan</span>
                                <span wire:loading wire:target="store">Loading...</span>
                            </button>
                            <button type="button" class="btn btn-secondary w-100 w-md-auto"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalEditPengajuan" tabindex="-1" aria-labelledby="modalEditPengajuanLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="modalEditPengajuanLabel">Pengajuan Dispensasi Keterlambatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <form>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label fw-semibold">Tanggal <small class="text-danger">*</small></label>
                                <input type="date" class="form-control" id="tanggal" wire:model="form.date">
                                @error('form.date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan <small class="text-danger">*</small></label>
                                <input type="text" class="form-control" id="keterangan" placeholder="Contoh: Ban bocor" wire:model="form.description">
                                @error('form.description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label fw-semibold">File</label>
                                <input type="file" class="form-control" id="file" wire:model="file" accept=".jpg,.jpeg,.png">
                                <small class="text-danger">
                                    @if (session()->has('error'))
                                        {{ session('error') }}
                                    @else
                                        Ukuran maksimal file: 2MB
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary  w-100 w-md-auto" wire:click='saveEdit' wire:loading.attr="disabled" wire:target="saveEdit">
                                <div wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span wire:loading.remove wire:target="saveEdit"><i class="fa fa-save"></i> Simpan</span>
                                <span wire:loading wire:target="saveEdit">Loading...</span>
                            </button>
                            <button type="button" class="btn btn-secondary w-100 w-md-auto"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
        Livewire.on('modalTambahPengajuan', (event) => {
            $('#modalTambahPengajuan').modal(event.action);
        });

        Livewire.on('modalEditPengajuan', (event) => {
            $('#modalEditPengajuan').modal(event.action);
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

