<div>
    <!-- Modal Users -->
    <div wire:ignore.self class="modal fade" id="modalAddUsers" tabindex="-1" aria-labelledby="modalAddUsersLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h1 class="modal-title fs-5" id="modalAddUsersLabel">Tambah Users</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" wire:model="name" placeholder="Masukkan Users">
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" wire:model="email" placeholder="Masukkan Email">
                        @error('email')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" wire:model="password" >
                        @error('password')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select
                            class="form-select selectRole"
                            id="roleSelect"
                            name="role_id[]"
                            multiple
                            style="width: 100%;"
                            data-placeholder="Pilih Role"
                        >
                            @foreach($roles as $role)
                                
                                <option value="{{ $role->id }}">{{ $role->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="storeUsers">Save changes</button>
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

@push('scripts')
    <script>
        $('#modalAddUsers').on('shown.bs.modal', function () {
            const $select = $('#roleSelect');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modalAddUsers')
                });

                const wireId = $select.closest('[wire\\:id]').attr('wire:id');
                const livewireInstance = Livewire.find(wireId);

                if (livewireInstance) {
                    // Ambil nilai awal dari Livewire dan isi ke Select2
                    const selected = livewireInstance.get('selectedRoles');
                    $select.val(selected).trigger('change');

                    // Sync perubahan dari Select2 ke Livewire
                    $select.on('change', function () {
                        const selectedValues = ($(this).val() || []).map(Number);
                        livewireInstance.set('selectedRoles', selectedValues);
                        console.log('Dikirim ke Livewire:', selectedValues);
                    });
                }
            }
      });
    </script>    
@endpush
