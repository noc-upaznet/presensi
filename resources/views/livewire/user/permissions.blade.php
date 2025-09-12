<div>
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Permissions</h4>
        {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fa-solid fa-plus"></i> Tambah Permissions</button> --}}
        <button class="btn btn-sm btn-primary" wire:click="$dispatch('modal-permission', { action: 'show' })"><i
            class="bi bi-plus-circle me-1"></i> Tambah Permission</button>
            <!-- /.card-header -->
    </div>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Permission</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Edit" wire:click="showEdit('{{ Crypt::encrypt($permission->id) }}')"><i
                                class="bi bi-pencil-square"></i></button>

                        <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Delete"
                            wire:click="dispatch('modal-confirm-delete-permission',{action:'show',id:'{{ Crypt::encrypt($permission->id) }}'})"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $permissions->links() }}
    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-permission">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" wire:ignore>
                    <h5 class="modal-title">Tambah Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama:</label>
                        <input type="text" name="name" placeholder="Nama" wire:model="namePermission"
                            class="form-control @error('namePermission') is-invalid @enderror">
                        @error('namePermission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" wire:click='store' wire:target="store"
                            wire:ignore.self id="btn-save-permission" wire:loading.attr="disabled">
                            <div wire:loading wire:target="store" class="spinner-border spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="store">Simpan</span>
                            <span wire:loading wire:target="store">Loading...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-confirm-delete-permission" tabindex="-1" wire:ignore.self
        data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus permission ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete-permission"
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

@script
    <script>
        Livewire.on('modal-permission', (e) => {
            if (e.method == 'edit') {
                $('#modal-permission').find('.modal-title').html('Edit Permission');
                $('#btn-save-permission').attr('wire:click', 'edit("' + e.id + '")');
            } else {
                $('#modal-permission').find('.modal-title').html('Tambah Permission');
                $('#btn-save-permission').attr('wire:click', 'store');

            }

            $('#modal-permission').modal(e.action);
        });

        Livewire.on('modal-confirm-delete-permission', (e) => {
            if (e.action == 'show') {
                $('#modal-confirm-delete-permission').modal('show');
                $('#btn-confirm-delete-permission').attr('wire:click', 'delete("' + e.id + '")');
            } else {
                $('#modal-confirm-delete-permission').modal('hide');
            }
        });
    </script>
@endscript