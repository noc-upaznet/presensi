<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Jenis Potongan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jenis Potongan</li>
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
                            data-bs-target="#tambahJenisPotonganModal">
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
                                    <th scope="col">Jenis Potongan</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jenisPotongan as $item)
                                    <tr>
                                        <td>{{ $item->nama_potongan }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td>
                                            <button wire:click.prevent="editPotongan({{ $item->id }})"
                                                class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editTunjanganModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button wire:click.prevent="delete({{ $item->id }})"
                                                class="btn btn-danger btn-sm">
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
    <!-- Modal Tambah Jenis Potongan -->
    <div wire:ignore.self class="modal fade" id="tambahJenisPotonganModal" tabindex="-1"
        aria-labelledby="tambahJenisPotonganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="tambahJenisPotonganModalLabel">Tambah Jenis Potongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_potongan" class="form-label">Nama Potongan</label>
                        <input type="text" class="form-control" id="nama_potongan" wire:model="nama_potongan">
                        @error('nama_potongan')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Nominal</label>
                        <input type="number" class="form-control" id="deskripsi" wire:model="deskripsi">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click="store" wire:loading.attr="disabled" class="btn btn-primary"
                        style="border-radius: 8px;">
                        <span wire:loading wire:target="store" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="fa-solid fa-save"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jenis Potongan -->
    <div wire:ignore.self class="modal fade" id="editPotonganModal" tabindex="-1"
        aria-labelledby="editPotonganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editPotonganModalLabel">Edit Jenis Potongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_potongan" class="form-label">Nama Potongan</label>
                        <input type="text" class="form-control" id="nama_potongan" wire:model="nama_potongan"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input type="number" class="form-control" id="deskripsi" wire:model="deskripsi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click="update" wire:loading.attr="disabled" class="btn btn-primary"
                        style="border-radius: 8px;">
                        <span wire:loading wire:target="update" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="fa-solid fa-save"></i>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Jenis Potongan -->
    <div wire:ignore.self class="modal fade" id="hapusTunjanganModal" tabindex="-1"
        aria-labelledby="hapusTunjanganModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #dc3545; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="hapusTunjanganModalLabel">Hapus Jenis Potongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus jenis potongan ini?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click.prevent="deletePotongan" class="btn btn-danger"
                        style="border-radius: 8px;">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('tambahJenisPotonganModal', (event) => {
            $('#tambahJenisPotonganModal').modal(event.action);
        });

        Livewire.on('editPotonganModal', (event) => {
            $('#editPotonganModal').modal(event.action);
        });

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>
@endpush
