<div>
    <!-- Modal Entitas -->
    <div wire:ignore.self class="modal fade" id="modalAddEntitas" tabindex="-1" aria-labelledby="modalAddEntitasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalAddEntitasLabel">Tambah Entitas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Entitas</label>
                        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan Entitas">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" rows="3" wire:model="alamat" placeholder="Masukkan Alamat"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="koordinat" class="form-label">Koordinat</label>
                        <input class="form-control" id="koordinat" rows="3" wire:model="koordinat" placeholder="Masukkan Koordinat">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="store">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div wire:ignore.self class="modal fade" id="modalEditEntitas" tabindex="-1" aria-labelledby="modalEditEntitasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalEditEntitasLabel">Edit Entitas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Entitas</label>
                        <input type="text" class="form-control" id="nama" wire:model="nama" placeholder="Masukkan Entitas">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" rows="3" wire:model="alamat" placeholder="Masukkan Alamat"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="koordinat" class="form-label">Koordinat</label>
                        <input class="form-control" id="koordinat" rows="3" wire:model="koordinat" placeholder="Masukkan Koordinat">
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
