<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Menentukan Lokasi Presensi Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menentukan Lokasi Presensi Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm p-3 mb-5 bg-white rounded">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rolePresensiModal">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Role Presensi
                    </button>
                </div>

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
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Lock</th>
                                    <th>Lokasi Presensi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lokasiList as $roleLokasi)
                                <tr>
                                    <td>{{ $roleLokasi->nama_karyawan }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $roleLokasi->lock ? 'primary' : 'secondary' }} text-white">
                                            {{ $roleLokasi->lock ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                    <td>{{ $roleLokasi->lokasi_presensi }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editRolePresensiModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click.prevent="confirmHapusLokasi({{ $roleLokasi->id }})"
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

                <div class="card-footer d-flex justify-content-between align-items-center bg-white p-3">
                    <span>Showing {{ $lokasiList->firstItem() }} to {{ $lokasiList->lastItem() }} of {{
                        $lokasiList->total() }} entries</span>
                    {{ $lokasiList->links() }}
                </div>
            </div>
            <!-- End Card Wrapper -->
        </div>
    </div>

    <!-- Modal Tambah Role Presensi -->
    <div wire:ignore.self class="modal fade" id="rolePresensiModal" tabindex="-1" aria-labelledby="rolePresensiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header" style="border-bottom: none; padding: 1rem 1.5rem;">
                    <h5 class="modal-title fw-bold text-primary" id="rolePresensiLabel">Tambah Role Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pilih Karyawan -->
                    <div class="mb-3">
                        <label for="nama_karyawan" class="form-label fw-semibold">Pilih Karyawan</label>
                        <select class="form-select">
                            <option value="">-- Pilih Karyawan --</option>
                            <option value="1">Putri Fanisa</option>
                            <option value="2">Nadia Ramadhani</option>
                            <option value="3">Rizky Ananda</option>
                            <option value="4">Aditya Fajar</option>
                            <option value="5">Citra Ayu</option>
                        </select>
                    </div>

                    <!-- Lock Lokasi Presensi -->
                    <div class="mb-3">
                        <label for="lock" class="form-label fw-semibold">Lock Lokasi Presensi</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="lockSwitch" checked>
                            <label class="form-check-label" for="lockSwitch">
                                Aktif
                            </label>
                        </div>
                    </div>

                    <!-- Lokasi Karyawan -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label fw-semibold">Lokasi Karyawan</label>
                        <select class="form-select">
                            <option value="">-- Pilih Lokasi Presensi --</option>
                            <option value="Head Office">Head Office</option>
                            <option value="UNB">UNB</option>
                            <option value="UNR Selatan">UNR Selatan</option>
                            <option value="UNR Utara">UNR Utara</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Role Presensi -->
    <div wire:ignore.self class="modal fade" id="editRolePresensiModal" tabindex="-1"
        aria-labelledby="editRolePresensiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header" style="border-bottom: none; padding: 1rem 1.5rem;">
                    <h5 class="modal-title fw-bold text-primary" id="editLokasiModalLabel">Edit Role Lokasi Presensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pilih Karyawan -->
                    <div class="mb-3">
                        <label for="nama_karyawan" class="form-label fw-semibold">Pilih Karyawan</label>
                        <select class="form-select" wire:model="karyawan">
                            <option value="">-- Pilih Karyawan --</option>
                            <option value="1">Putri Fanisa</option>
                            <option value="2">Nadia Ramadhani</option>
                            <option value="3">Rizky Ananda</option>
                            <option value="4">Aditya Fajar</option>
                            <option value="5">Citra Ayu</option>
                        </select>
                    </div>

                    <!-- Lock Lokasi Presensi -->
                    <div class="mb-3">
                        <label for="lock" class="form-label fw-semibold">Lock Lokasi Presensi</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="lockSwitch" wire:model="lock">
                            <label class="form-check-label" for="lockSwitch">
                                {{ $lock ? 'Aktif' : 'Tidak Aktif' }}
                            </label>
                        </div>
                    </div>

                    <!-- Lokasi Karyawan -->
                    <div class="mb-3">
                        <label for="lokasi" class="form-label fw-semibold">Lokasi Karyawan</label>
                        <select class="form-select" wire:model="lokasi" {{ $lock ? 'disabled' : '' }}>
                            <option value="">-- Pilih Lokasi Presensi --</option>
                            <option value="Head Office">Head Office</option>
                            <option value="UNB">UNB</option>
                            <option value="UNR Selatan">UNR Selatan</option>
                            <option value="UNR Utara">UNR Utara</option>
                        </select>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" wire:click="update">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div wire:ignore.self class="modal fade" id="hapusLokasiModal" tabindex="-1" aria-labelledby="hapusLokasiLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #dc3545; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="hapusLokasiLabel">Hapus Role Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus role presensi ini?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
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


<script>
    window.addEventListener('close-modal', event => {
        var modal = new bootstrap.Modal(document.getElementById('rolePresensiModal'));
        modal.hide();
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