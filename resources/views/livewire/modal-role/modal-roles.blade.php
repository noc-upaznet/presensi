<div>
    <!-- Modal Users -->
    <div wire:ignore.self class="modal fade" id="modalAddRoles" tabindex="-1" aria-labelledby="modalAddRolesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalAddRolesLabel">Tambah Roles User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Nama</label>
                        <select type="text" class="form-select" id="user_id" wire:model="user_id">
                            <option value="">Pilih Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select type="text" class="form-select" id="role" wire:model="role">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="hr">HR</option>
                            <option value="spv">SPV</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" wire.click="store" class="btn btn-primary" wire:click="store">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="modalEditUsers" tabindex="-1" aria-labelledby="modalEditUsersLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalEditUsersLabel">Edit Role Users</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan Users">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3" wire:model="deskripsi" placeholder="Masukkan Deskripsi"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="update">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>