<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Rekap Gamifikasi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rekap Gamifikasi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
            <div class="mb-4">
                <h5>Total Teknisi Staff {{ session('selected_entitas', 'UHO') }} : {{ $totalKaryawan }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Karyawan</th>
                            {{-- <th>Rekap</th> --}}
                            <th>Poin Gamifikasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $index => $key)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $key->nama_karyawan }}</td>
                                {{-- <td>{{ $key->total_tepat_waktu }}</td> --}}
                                <td>{{ $key->poin }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm"
                                        wire:click="showEditModal('{{ Crypt::encrypt($key->id) }}')">
                                        <i class="fas fa-edit"></i>
                                        Kelola Poin
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        Kelola Poin Gamifikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mode Poin</label>

                        <select class="form-select" wire:model.lazy="modePoin">
                            <option value="tambah">Tambah Poin</option>
                            <option value="kurang">Kurangi Poin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="poin" class="form-label">
                            Jumlah Poin
                        </label>

                        <input type="number" class="form-control" id="poin" wire:model="poin" min="1"
                            step="1" placeholder="Masukkan jumlah poin">

                        @error('poin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="alert" class="{{ $modePoin === 'kurang' ? 'alert-warning' : 'alert-info' }}">
                        @if ($modePoin === 'kurang')
                            <i class="fas fa-minus-circle"></i>
                            Poin karyawan akan dikurangi.
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Poin karyawan akan ditambahkan.
                        @endif
                    </div> --}}
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" wire:click="updatePoin"
                        class="btn {{ $modePoin === 'kurang' ? 'btn-danger' : 'btn-primary' }}">
                        <i class="fas {{ $modePoin === 'kurang' ? 'fa-minus' : 'fa-plus' }}"></i>
                        {{ $modePoin === 'kurang' ? 'Kurangi Poin' : 'Tambah Poin' }}
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
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
