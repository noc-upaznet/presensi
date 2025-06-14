<div>
    <!-- Modal Divisi -->
    <div wire:ignore.self class="modal fade" id="modalAddDivisi" tabindex="-1" aria-labelledby="modalAddDivisiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalAddDivisiLabel">Tambah Divisi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Divisi</label>
                        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan Divisi">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3" wire:model="deskripsi" placeholder="Masukkan Deskripsi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="storeDivisi">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="modalEditDivisi" tabindex="-1" aria-labelledby="modalEditDivisiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalEditDivisiLabel">Edit Role Divisi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Divisi</label>
                        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan Divisi">
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
