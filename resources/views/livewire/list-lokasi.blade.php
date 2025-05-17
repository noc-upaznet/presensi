<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Lokasi Presensi Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lokasi Presensi Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm p-4 mb-5 bg-white rounded">
            <form wire:submit.prevent="simpanLokasi">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama-lokasi" class="form-label fw-bold">Nama Lokasi</label>
                        <input type="text" class="form-control" id="nama-lokasi" wire:model="nama_lokasi"
                            placeholder="Head Office (HO)">
                    </div>
                    <div class="col-md-6">
                        <label for="koordinat" class="form-label fw-bold">Koordinat</label>
                        <button type="button" class="btn btn-primary"
                            style="font-size: 10px; padding: 4px 8px; border-radius: 4px; margin-left: 8px;">Google
                            Maps</button>
                        <div class="input-group">
                            <input type="text" class="form-control" id="koordinat" wire:model="koordinat"
                                placeholder="-8.3489739">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control" id="alamat" wire:model="alamat" rows="3"
                            placeholder="Jl. Ngantru - Srengat No.550, Dermosari, Pinggirsari, Kec. Ngantru, Kabupaten Tulungagung, Jawa Timur 66252"></textarea>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div>
                            <label class="form-label fw-bold">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" wire:model="status" checked>
                                <label class="form-check-label" for="status">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-5">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                            Tambah Lokasi</button>
                    </div>
                </div>
            </form>

            <div class="card shadow-sm p-4 bg-white rounded">
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
                            <input type="search" class="form-control form-control-sm" placeholder="Search...">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Lokasi</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lokasi_list as $lokasi)
                            <tr>
                                <td>{{ $lokasi->nama_lokasi }}</td>
                                <td>{{ $lokasi->alamat }}</td>
                                <td>
                                    <span class="badge bg-{{ $lokasi->status ? 'info' : 'secondary' }}">
                                        {{ $lokasi->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button wire:click.prevent="editLokasi({{ $lokasi->id }})"
                                        class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editLokasiModal">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Tombol Buka Modal -->
                                    <button wire:click.prevent="confirmHapusLokasi({{ $lokasi->id }})"
                                        class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusLokasiModal">
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

        <!-- Modal Edit Lokasi -->
        <div wire:ignore.self class="modal fade" id="editLokasiModal" tabindex="-1"
            aria-labelledby="editLokasiModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                    <div class="modal-header" style="border-bottom: none; padding: 1rem 1.5rem;">
                        <h5 class="modal-title fw-bold text-primary" id="editLokasiModalLabel">Edit Lokasi Presensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updateLokasi">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="namaLokasi" class="form-label fw-semibold">Nama Lokasi</label>
                                    <input type="text" id="namaLokasi" wire:model="nama_lokasi" class="form-control"
                                        placeholder="Head Office (HO)" style="border-radius: 8px;">
                                </div>
                                <div class="col-md-6">
                                    <label for="koordinat" class="form-label fw-semibold">Koordinat</label>
                                    <button type="button" class="btn btn-primary"
                                        style="font-size: 10px; padding: 4px 8px; border-radius: 4px; margin-left: 8px;">Google
                                        Maps</button>
                                    <div class="input-group">
                                        <input type="text" id="koordinat" wire:model="koordinat" class="form-control"
                                            placeholder="-8.3489739" style="border-radius: 8px 0 0 8px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" wire:model="status">
                                        <label class="form-check-label" for="status">{{ $status ? 'Aktif' :
                                            'Nonaktif'
                                            }}</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="alamat" class="form-label fw-semibold">Lokasi Karyawan</label>
                                    <textarea id="alamat" wire:model="alamat" class="form-control" rows="3"
                                        placeholder="Jl. Ngantru - Srengat No.550, Dermosari, Pinggirsari, Kec. Ngantru, Kabupaten Tulungagung, Jawa Timur 66252"
                                        style="border-radius: 8px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer text-end" style="padding: 10px 20px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Kembali</button>
                        <button type="button" class="btn btn-primary" wire:click="updateLokasi"
                            style="border-radius: 8px;">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Lokasi -->
    <div wire:ignore.self class="modal fade" id="hapusLokasiModal" tabindex="-1" aria-labelledby="hapusLokasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #d51a1a; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="hapusLokasiModalLabel">Hapus Lokasi Presensi
                        Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus lokasi presensi ini?</p>
                    <p><strong>{{ $nama_lokasi }}</strong></p>
                    <p>Lokasi ini akan dihapus secara permanen.</p>
                    <p>Jika lokasi ini sudah digunakan, maka data presensi karyawan yang menggunakan lokasi ini
                        akan
                        hilang.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteLokasi" style="border-radius: 8px;"
                        data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert2 untuk notifikasi
        window.addEventListener('lokasiTerupdate', function (event) {
            Swal.fire({
                title: 'Berhasil!',
                text: event.detail.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Pastikan modal tertutup setelah alert ditutup
                const modalElement = document.getElementById('editLokasiModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        });

        // Manual close modal handler
        window.addEventListener('closeModal', function () {
            const modalElement = document.getElementById('editLokasiModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    });
    window.addEventListener('lokasiTerhapus', event => {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Lokasi berhasil dihapus.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
});

</script>