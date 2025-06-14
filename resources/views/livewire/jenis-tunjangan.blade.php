<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Jenis Tunjangan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jenis Tunjangan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#tambahJenisTunjanganModal">
                            <i class="fa-solid fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <label>Show <select class="form-select form-select-sm d-inline-block w-auto">
                                        <option>5</option>
                                        <option>10</option>
                                        <option>20</option>
                                    </select> entries per page</label>
                            </div>
                            <div>
                                <input type="search" class="form-control form-control-sm" placeholder="Search...">
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Jenis Tunjangan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Dibuat Pada</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisTunjangan as $item)
                                <tr>
                                    <td>{{ $item->nama_tunjangan }}</td>
                                    <td>{{ $item->maksimal_jumlah }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <button wire:click.prevent="editTunjangan({{ $item->id }})"
                                            class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editTunjanganModal">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button wire:click.prevent="confirmHapusTunjangan({{ $item->id }})"
                                            class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#hapusTunjanganModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Jenis Tunjangan -->
    <div wire:ignore.self class="modal fade" id="tambahJenisTunjanganModal" tabindex="-1"
        aria-labelledby="tambahJenisTunjanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="tambahJenisTunjanganModalLabel">Tambah Jenis Tunjangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_tunjangan" class="form-label">Nama Tunjangan</label>
                        <input type="text" class="form-control" id="nama_tunjangan" wire:model="nama_tunjangan">
                    </div>
                    @error('nama_tunjangan')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" wire:model="deskripsi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click="store" class="btn btn-primary" style="border-radius: 8px;">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jenis Tunjangan -->
    <div wire:ignore.self class="modal fade" id="editTunjanganModal" tabindex="-1"
        aria-labelledby="editTunjanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #ffc107; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editTunjanganModalLabel">Edit Jenis Tunjangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateTunjangan">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_tunjangan_edit" class="form-label">Nama Tunjangan</label>
                            <input type="text" class="form-control" id="nama_tunjangan_edit" wire:model="nama_tunjangan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="maksimal_jumlah_edit" class="form-label">Maksimal Jumlah</label>
                            <input type="number" class="form-control" id="maksimal_jumlah_edit"
                                wire:model="maksimal_jumlah" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn btn-warning" style="border-radius: 8px;">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Jenis Tunjangan -->
    <div wire:ignore.self class="modal fade" id="hapusTunjanganModal" tabindex="-1"
        aria-labelledby="hapusTunjanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #d51a1a; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="hapusTunjanganModalLabel">Hapus Jenis Tunjangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jenis tunjangan ini?</p>
                    <p><strong>{{ $nama_tunjangan }}</strong></p>
                    <p>Jenis tunjangan ini akan dihapus secara permanen.</p>
                    <p>Jika jenis tunjangan ini sudah digunakan, maka data karyawan yang menggunakan jenis tunjangan ini
                        akan
                        hilang.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteTunjangan"
                        style="border-radius: 8px;" data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('tambahJenisTunjanganModal', (event) => {
            $('#tambahJenisTunjanganModal').modal(event.action);
        });

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>    
@endpush