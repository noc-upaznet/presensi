<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0 mt-5" style="color: var(--bs-body-color);">Role Lokasi Presensi Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role Lokasi Presensi Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm p-3 mb-5 rounded" style="background-color: var(--bs-body-bg);">
            <!--begin::Container-->
            <div class="container-fluid">
                <div class="mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rolePresensiModal">
                        <i class="fa-solid fa-plus"></i>
                        Tambah
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
                        <input type="search" wire:model.live="search" class="form-control form-control-sm" placeholder="Search...">
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
                                @if ($lokasiList->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                    </tr>
                                @else
                                    @foreach($lokasiList as $roleLokasi)
                                        <tr>
                                            <td style="color: var(--bs-body-color);">{{ $roleLokasi->getKaryawan->nama_karyawan }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $roleLokasi->lock ? 'primary' : 'secondary' }} text-white">
                                                    {{ $roleLokasi->lock ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                            <td style="color: var(--bs-body-color);">
                                                {{-- {{ $roleLokasi->lokasi_presensi }} --}}
                                                @foreach ($roleLokasi->lokasis as $lokasi)
                                                    <span class="badge bg-info text-dark me-1">{{ $lokasi->nama_lokasi }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" wire:click="showEdit('{{ Crypt::encrypt($roleLokasi->id) }}')">
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
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pt-3">
                    {{ $lokasiList->links('pagination::bootstrap-5') }}
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
                    <div class="mb-3">
                        <label for="nama_karyawan" class="form-label fw-semibold">Pilih Karyawan</label>
                        <select class="form-select" wire:model="selectedKaryawan">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                            @endforeach
                        </select>
                        @error('selectedKaryawan')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lock Lokasi Presensi -->
                    <div class="mb-3">
                        <label for="lock" class="form-label fw-semibold">Lock Lokasi Presensi</label>
                        <div x-data="{ locked: @entangle('lock') }" class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="lockSwitch" x-model="locked">
                            <label class="form-check-label" for="lockSwitch" x-text="locked ? 'Aktif' : 'Nonaktif'"></label>
                        </div>
                    </div>

                    <!-- Lokasi Karyawan -->
                    <div class="mb-3" wire:ignore>
                        <label class="form-label fw-semibold">Lokasi Karyawan</label>
                        <select
                            class="form-select"
                            id="lokasiSelect"
                            multiple
                            style="width: 100%;"
                            data-placeholder="Pilih lokasi karyawan"
                        >
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        @error('lokasi_presensi')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="store" class="btn btn-primary">Simpan</button>
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
                        <select class="form-select" wire:model="selectedKaryawan">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                            @endforeach
                        </select>
                        @error('selectedKaryawan')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Lock Lokasi Presensi -->
                    <div class="mb-3">
                        <label for="lock" class="form-label fw-semibold">Lock Lokasi Presensi</label>
                        <div x-data="{ locked: @entangle('lock') }" class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="lockSwitch" x-model="locked">
                            <label class="form-check-label" for="lockSwitch" x-text="locked ? 'Aktif' : 'Nonaktif'"></label>
                        </div>
                    </div>

                    <!-- Lokasi Karyawan -->
                    <div class="mb-3" wire:ignore>
                        <label class="form-label fw-semibold">Lokasi Karyawan</label>
                        <select
                            class="form-select selectLokasi"
                            id="lokasiSelect2"
                            multiple
                            style="width: 100%;"
                            data-placeholder="Pilih lokasi karyawan"
                        >
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        @error('lokasi_presensi')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" wire:click="saveEdit">Update</button>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lokasiSelect = document.getElementById('lokasiSelect');
            const lockSwitch = document.getElementById('lockSwitch');

            // Set initial state
            lokasiSelect.disabled = !lockSwitch.checked;

            // Update saat event Livewire dikirim
            // window.addEventListener('lock-updated', event => {
            //     const isLocked = event.detail.lock;
            //     // lokasiSelect.disabled = !isLocked;
            // });

            // Optional: Untuk sinkron dengan klik langsung (jika toggle tidak trigger Livewire dengan benar)
            lockSwitch.addEventListener('change', function () {
                lokasiSelect.disabled = !this.checked;
            });
        });

        Livewire.on('rolePresensiModal', (event) => {
            $('#rolePresensiModal').modal(event.action);
        });

        Livewire.on('editRolePresensiModal', (event) => {
            $('#editRolePresensiModal').modal(event.action);
        });
        // window.addEventListener('close-modal', event => {
        //     var modal = new bootstrap.Modal(document.getElementById('rolePresensiModal'));
        //     modal.hide();
        // });

        window.addEventListener('lokasiTerhapus', event => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Lokasi berhasil dihapus.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        });

        Livewire.on('refresh', () => {
            Livewire.dispatch('refreshTable');
        });

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
        
    </script>
@endpush