<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Riwayat Presensi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Presensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
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

                    <input type="month" class="form-control" style="width: 150px;" placeholder="Bulan" wire:model.lazy="filterBulan">

                    <input type="date" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Tanggal" wire:model.lazy="filterTanggal">
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>Show 
                        <select class="form-select form-select-sm d-inline-block w-auto">
                            <option>5</option>
                            <option>10</option>
                            <option>20</option>
                        </select> entries per page</label>
                    </div>
                    <div>
                        <input type="search" class="form-control form-control-sm" placeholder="Search..." wire:model.live="search">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                @role('admin')
                                    <th>Nama Karyawan</th>
                                @endrole
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                @role('admin')
                                <th>Lokasi</th>
                                <th>Old Status</th>
                                @endrole
                                <th>File</th>
                                <th>Status</th>
                                @role('admin')
                                    <th>Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @if ($datas->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                </tr>
                            @else
                                @foreach($datas as $key)
                                    <tr>
                                        <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                                        @role('admin')
                                            <td style="color: var(--bs-body-color);">{{ $key->getUser->nama_karyawan }}</td>
                                        @endrole
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_in }}</td>
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_out }}</td>
                                        @role('admin')
                                            <td>
                                                <span>Clock-In :</span> 
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($key->lokasi_final) }}" 
                                                target="_blank" 
                                                class="badge bg-primary text-decoration-none">
                                                    {{ $key->lokasi_final }}
                                                </a>
                                                <br>
                                                <span>Clock-Out :</span> 
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($key->lokasi_clock_out_final) }}" 
                                                target="_blank" 
                                                class="badge bg-danger text-decoration-none">
                                                    {{ $key->lokasi_clock_out_final }}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($key->previous_status == "0")
                                                    <span class="badge bg-success">Tepat Waktu</span>
                                                @elseif ($key->previous_status == "1")
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @elseif ($key->previous_status == "2")
                                                    <span class="badge bg-primary">Dispensasi</span>
                                                @else
                                                    <span class="badge bg-secondary">Unknown</span>
                                                @endif
                                            </td>
                                        @endrole
                                        <td style="color: var(--bs-body-color);">
                                            <img src="{{ asset('storage/' . $key->file) }}" style="max-width: 100px;" alt="Selfie" class="img-fluid" />
                                            {{-- {{ $key->file }} --}}
                                        </td>
                                        <td>
                                            @if ($key->status == "0")
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @elseif ($key->status == "1")
                                                <span class="badge bg-danger">Terlambat</span>
                                            @elseif ($key->status == "2")
                                                <span class="badge bg-primary">Dispensasi</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        @role('admin')
                                            <td>
                                                @can('presensi-edit')
                                                    <button class="btn btn-warning btn-sm" wire:click="showModal('{{ Crypt::encrypt($key->id) }}')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endcan
                                            </td>
                                        @endrole
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $datas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3 container">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" wire:model="status">
                            @foreach($statusList as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" wire:click="updateStatus" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm-delete" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus shift ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete"
                        wire:loading.attr="disabled">
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    Livewire.on('editModal', (event) => {
        $('#editModal').modal(event.action);
    });

    Livewire.on('modal-confirm-delete', (event) => {
        $('#modal-confirm-delete').modal(event.action);
        $('#btn-confirm-delete').attr('wire:click', 'delete("' + event.id + '")');
        $('#modal-confirm-delete').modal('hide');
    });

  Livewire.on('swal', (e) => {
      Swal.fire(e.params);
  });
</script>
    
@endpush