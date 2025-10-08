<div>
    <!-- Modal Jabatan -->
    <div wire:ignore.self class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalAddLabel">Tambah Role Jabatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select type="text" class="form-select" id="jabatan" wire:model="nama_jabatan">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Komisaris">Komisaris</option>
                            <option value="Direktur">Direktur</option>
                            <option value="Branch Manager">Branch Manager</option>
                            <option value="SPV">SPV</option>
                            <option value="Accounting">Accounting</option>
                            <option value="Billing">Billing</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="ASL">ASL</option>
                            <option value="Finance">Finance</option>
                            <option value="Helpdesk">Helpdesk</option>
                            <option value="Sales Marketing">Sales Marketing</option>
                            <option value="Asisten SPV Teknisi">Asisten SPV Teknisi</option>
                            <option value="Teknisi">Teknisi</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Support">Support</option>
                            <option value="Admin">Admin</option>
                            <option value="Recruitment">Recruitment</option>
                            <option value="Asisten">Asisten</option>
                            <option value="CRM">CRM</option>
                            <option value="Kepala Konter">Kepala Konter</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3" wire:model="deskripsi" placeholder="Masukkan Deskripsi"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="has_staff" class="form-label fw-semibold">Has Staff</label>
                            <div x-data="{ has_staff: @entangle('has_staff').live }" class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="has_staffSwitch" x-model="has_staff">
                                <label class="form-check-label" for="has_staffSwitch" x-text="has_staff ? 'Iya' : 'Tidak'"></label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="spv_id" class="form-label fw-semibold">SPV</label>
                            <div x-data="{ spv_id: @entangle('spv_id').live }" class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="spv_idSwitch" x-model="spv_id">
                                <label class="form-check-label" for="spv_idSwitch" x-text="spv_id ? 'Iya' : 'Tidak'"></label>
                            </div>
                        </div>
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
    <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalEditLabel">Edit Role Jabatan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <select type="text" class="form-select" id="jabatan" wire:model="nama_jabatan">
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Komisaris">Komisaris</option>
                            <option value="Direktur">Direktur</option>
                            <option value="Branch Manager">Branch Manager</option>
                            <option value="SPV">SPV</option>
                            <option value="Accounting">Accounting</option>
                            <option value="Billing">Billing</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="ASL">ASL</option>
                            <option value="Finance">Finance</option>
                            <option value="Helpdesk">Helpdesk</option>
                            <option value="Sales Marketing">Sales Marketing</option>
                            <option value="Asisten SPV Teknisi">Asisten SPV Teknisi</option>
                            <option value="Teknisi">Teknisi</option>
                            <option value="Kasir">Kasir</option>
                            <option value="Support">Support</option>
                            <option value="Admin">Admin</option>
                            <option value="Recruitment">Recruitment</option>
                            <option value="Asisten">Asisten</option>
                            <option value="CRM">CRM</option>
                            <option value="Kepala Konter">Kepala Konter</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" rows="3" wire:model="deskripsi" placeholder="Masukkan Deskripsi"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="has_staff" class="form-label fw-semibold">Has Staff</label>
                            <div x-data="{ has_staff: @entangle('has_staff').live }" class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="has_staffSwitch" x-model="has_staff">
                                <label class="form-check-label" for="has_staffSwitch" x-text="has_staff ? 'Iya' : 'Tidak'"></label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="spv_id" class="form-label fw-semibold">SPV</label>
                            <div x-data="{ spv_id: @entangle('spv_id').live }" class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="spv_idSwitch" x-model="spv_id">
                                <label class="form-check-label" for="spv_idSwitch" x-text="spv_id ? 'Iya' : 'Tidak'"></label>
                            </div>
                        </div>
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
