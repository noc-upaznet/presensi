<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mt-5">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Riwayat Presensi</h3>
                </div>
                <!--<div class="col-sm-6">-->
                <!--    <ol class="breadcrumb float-sm-end">-->
                <!--        <li class="breadcrumb-item"><a href="#">Home</a></li>-->
                <!--        <li class="breadcrumb-item active" aria-current="page">Lokasi Presensi Karyawan</li>-->
                <!--    </ol>-->
                <!--</div>-->
            </div>
        </div>
    </div>

    <div class="container">
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
                        <input type="search" class="form-control form-control-sm" placeholder="Search..." wire:model.live="search">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                @if (auth()->user()->role != 'user')
                                    <th>Nama Karyawan</th>
                                @endif
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>File</th>
                                <th>Status</th>
                                @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hr')
                                    <th>Action</th>
                                @endif
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
                                        @if (auth()->user()->role != 'user')
                                            <td style="color: var(--bs-body-color);">{{ $key->getUser->nama_karyawan }}</td>
                                        @endif
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_in }}</td>
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_out }}</td>
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
                                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hr')
                                            <td>
                                                <button class="btn btn-warning btn-sm" wire:click="showModal('{{ Crypt::encrypt($key->id) }}')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
        
                                                <!-- Tombol Buka Modal -->
                                                <button wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"
                                                    class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#hapusPresensiModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
        
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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