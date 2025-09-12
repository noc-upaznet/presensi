@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/treejs/dist/treejs.css">
@endpush
<div>
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Roles</h4>
        <button class="btn btn-sm btn-primary" wire:click="showAdd"><i class="bi bi-plus-circle me-1"></i> Tambah
        Role</button>
            <!-- /.card-header -->
    </div>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm mt-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Edit" wire:click="showEdit('{{ Crypt::encrypt($role->id) }}')"><i
                                class="bi bi-pencil-square"></i></button>

                        <button class="btn btn-sm btn-danger mt-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            data-bs-title="Delete"
                            wire:click='$dispatch("modal-confirm-delete-role",{ id:"{{ Crypt::encrypt($role->id) }}",action:"show"})'><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $roles->links() }}
    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-role">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" wire:ignore>
                    <h5 class="modal-title">Tambah Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama:</label>
                        <input type="text" name="name" placeholder="Nama" wire:model="roleName"
                            class="form-control @error('roleName') is-invalid @enderror">
                        @error('roleName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @error('selectedPermissions')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="mb-3">
                        <label class="form-label">Permission:</label>
                        <div id="tree">
                            <ul class="list-unstyled">
                                @foreach ($permission_categories as $key => $category)
                                    <li class="mb-2">
                                        <label>
                                            <input type="checkbox" class="parent-checkbox" data-category="{{ $category }}">
                                            <strong>{{ Str::ucfirst($category) }}</strong>
                                        </label>
                                        <ul class="ms-4 mt-2">
                                            @foreach ($permissions[$category] as $permission)
                                                <li>
                                                    <label>
                                                        <input type="checkbox"
                                                            class="child-checkbox"
                                                            wire:model="selectedPermissions"
                                                            value="{{ $permission->name }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click='store' wire:target="store"
                        wire:ignore.self id="btn-save-role" wire:loading.attr="disabled">
                        <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove wire:target="store">Simpan</span>
                        <span wire:loading wire:target="store">Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm-delete-role" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus role ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete-role"
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

@push('scripts')
<script>
    Livewire.on('modal-role', (event) => {
        if (event.method == 'edit') {
            $('#modal-role').find('.modal-title').text('Edit Role');
            $('#btn-save-role').attr('wire:click', 'edit("' + event.id + '")');
        } else {
            $('#modal-role').find('.modal-title').text('Tambah Role');
            $('#btn-save-role').attr('wire:click', 'store');
        }
        $('#modal-role').modal(event.action);

        if (event.action == 'hide') {
            $('#tree input[type=checkbox]').prop({
                indeterminate: false,
                checked: false
            });
        }
    });

    // Parent → Child
    $("#tree").on("change", ".parent-checkbox", function() {
        let checked = $(this).is(":checked");
        $(this).closest("li").find(".child-checkbox").prop("checked", checked).trigger("change");
        updateSelectedPermissions();
    });

    // Child → Parent
    $("#tree").on("change", ".child-checkbox", function() {
        let parentLi = $(this).closest("li").parent().closest("li");
        let parentCb = parentLi.find("> label > .parent-checkbox");

        let all = parentLi.find(".child-checkbox").length;
        let checked = parentLi.find(".child-checkbox:checked").length;

        if (checked === 0) {
            parentCb.prop("indeterminate", false).prop("checked", false);
        } else if (checked === all) {
            parentCb.prop("indeterminate", false).prop("checked", true);
        } else {
            parentCb.prop("indeterminate", true).prop("checked", false);
        }

        updateSelectedPermissions();
    });

    function updateSelectedPermissions() {
        let data = $("#tree").find(".child-checkbox:checked")
            .map(function() { return this.value; })
            .get();
        @this.set('selectedPermissions', data);
    }

    Livewire.on('modal-confirm-delete-role', (event) => {
        $('#modal-confirm-delete-role').modal(event.action);
        $('#btn-confirm-delete-role').attr('wire:click', 'delete("' + event.id + '")');
    });
</script>
@endpush
