<div>
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Users</h4>
    </div>
    @can('user-create')
        <button class="btn btn-sm btn-primary" wire:click="showCreate"><i class="bi bi-plus-circle me-1"></i> Tambah
            User</button>
    @endcan
    <div class="d-flex justify-content-between my-2">
        <div class="d-flex align-items-center">
            <select class="form-select form-select-sm w-auto me-2" wire:model.live='tableLength'>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>entries per page</span>
        </div>
        <input type="text" class="form-control form-control-sm w-25" name="search" placeholder="Search..."
            autocomplete="none" wire:model.live="tableSearch" style="text-security: disc;" wire.ignore>
    </div>

    <table class="table ">
        <thead>
            <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Cabang</th>
                <th scope="col">Role</th>
                <th scope="col">Permission</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $user)
                @php
                    $id = Crypt::encrypt($user->id);
                @endphp
                <tr wire:key="{{ $id }}">
                    {{-- <th scope="row">{{ $key }}</th> --}}
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach ($user->branches() as $branch)
                            <label class="badge text-bg-success">{{ $branch->nama }}</label>
                        @endforeach
                    </td>
                    <td>
                        @if (!@empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $role)
                                <label class="badge text-bg-success">{{ $role }}</label>
                            @endforeach

                        @endempty
                    </td>
                    <td>
                        @if ($user->getPermissionNames()->isNotEmpty())
                            @foreach ($user->getPermissionNames() as $permission)
                                <label class="badge text-bg-primary">{{ $permission }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @can('user-edit')
                            <button class="btn btn-warning btn-sm  mt-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="Edit" wire:click="showEdit('{{ $id }}')"><i
                                    class="bi bi-pencil-square"></i></button>
                        @endcan
                        @can('user-edit')
                            <button class="btn btn-warning btn-sm  mt-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="Edit" wire:click="showEditPassword('{{ $id }}')"><i
                                    class="bi bi-pencil-square"></i> Password</button>
                        @endcan
                        @can('user-delete')
                            <button class="btn btn-sm btn-danger  mt-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="Delete"
                                wire:click="$dispatch('modal-confirm-delete',{id:'{{ $id }}',action:'show'})"><i
                                    class="bi bi-trash"></i></button>
                        @endcan
                    </td>
                </tr>
            @endforeach

        </tbody>

    </table>
    {{ $users->links() }}


    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-create-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama:</label>
                        <input type="text" name="name" placeholder="Name" wire:model="form.name"
                            class="form-control @error('form.name') is-invalid @enderror">
                        @error('form.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" placeholder="Email" wire:model="form.email"
                            class="form-control @error('form.email') is-invalid @enderror">
                        @error('form.email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" placeholder="Password" wire:model="form.password"
                            class="form-control @error('form.password') is-invalid @enderror">
                        @error('form.password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password:</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password "
                            wire:model="form.password_confirmation"
                            class="form-control @error('form.password_confirmation') is-invalid @enderror">
                        @error('form.password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role:</label>
                        <div wire:ignore>
                            <select name="roles[]" wire:model='form.user_roles' id="select-roles-modal-create-user"
                                class="w-100 form-select  @error('form.user_roles') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($user_roles as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permission:</label>
                        <div wire:ignore>
                            <select name="permissions[]" wire:model='form.user_permissions' id="select-permissions-modal-create-user"
                                class="w-100 form-select  @error('form.user_permissions') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($user_permissions as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Branch:</label>
                        <div wire:ignore>
                            <select name="branches[]" wire:model='form.user_branches'
                                id="select-branches-modal-create-user"
                                class="w-100 form-select @error('form.user_branches') is-invalid @enderror"
                                multiple="multiple">
                                
                                @foreach ($user_branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_branches')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click='store' wire:loading.attr="disabled"
                        wire:target='store'>
                        <div wire:target='store' wire:loading class="spinner-border spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:target='store' wire:loading.remove>Simpan</span>
                        <span wire:target='store' wire:loading>Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-edit-password">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" placeholder="Password" wire:model="form.password"
                            class="form-control @error('form.password') is-invalid @enderror">
                        @error('form.password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password:</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password "
                            wire:model="form.password_confirmation"
                            class="form-control @error('form.password_confirmation') is-invalid @enderror">
                        @error('form.password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click='editPassword'
                        wire:loading.attr="disabled" wire:target='editPassword'>
                        <div wire:target='editPassword' wire:loading class="spinner-border spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:target='editPassword' wire:loading.remove>Simpan</span>
                        <span wire:target='editPassword' wire:loading>Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-edit-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama:</label>
                        <input type="text" name="name" placeholder="Nama" wire:model="form.name"
                            class="form-control @error('form.name') is-invalid @enderror">
                        @error('form.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" placeholder="Email" wire:model="form.email"
                            class="form-control @error('form.email') is-invalid @enderror">
                        @error('form.email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role:</label>
                        <div wire:ignore>
                            <select name="roles[]" wire:model='form.user_roles' id="select-roles-modal-edit-user"
                                class="w-100 form-select  @error('form.user_roles') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($user_roles as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permission:</label>
                        <div wire:ignore>
                            <select name="permissions[]" wire:model='form.user_permissions' id="select-permissions-modal-edit-user"
                                class="w-100 form-select  @error('form.user_permissions') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($user_permissions as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_permissions')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cabang:</label>
                        <div wire:ignore>
                            <select name="branches[]" wire:model='form.user_branches'
                                id="select-branches-modal-edit-user"
                                class="w-100 form-select @error('form.user_branches') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($user_branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.user_branches')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click='edit' wire:loading.attr="disabled"
                        wire:target='edit'>
                        <div wire:target='edit' wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:target='edit' wire:loading.remove>Simpan</span>
                        <span wire:target='edit' wire:loading>Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm-delete" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus user ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete"
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

    <div class="modal fade" tabindex="-1" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false"
        id="modal-edit-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama:</label>
                        <input type="text" name="name" placeholder="Nama" wire:model="form.name"
                            class="form-control @error('form.name') is-invalid @enderror">
                        @error('form.name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" placeholder="Email" wire:model="form.email"
                            class="form-control @error('form.email') is-invalid @enderror">
                        @error('form.email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role:</label>
                        <select name="roles[]" wire:model='form.user_roles'
                            class="form-select @error('form.user_roles') is-invalid @enderror" multiple="multiple">
                            @foreach ($user_roles as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('form.user_roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click='edit' wire:loading.attr="disabled">
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading>Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-confirm-delete" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus user ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete"
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
        $('#modal-create-user').on('shown.bs.modal', function () {
            const $select = $('#select-roles-modal-create-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-create-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_roles');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []);
                    livewireInstance.set('form.user_roles', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        $('#modal-create-user').on('shown.bs.modal', function () {
            const $select = $('#select-branches-modal-create-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-create-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_branches');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []).map(Number);
                    livewireInstance.set('form.user_branches', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        $('#modal-create-user').on('shown.bs.modal', function () {
            const $select = $('#select-permissions-modal-create-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-create-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_permissions');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []);
                    livewireInstance.set('form.user_permissions', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        //edit

        $('#modal-edit-user').on('shown.bs.modal', function () {
            const $select = $('#select-roles-modal-edit-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-edit-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_roles');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []);
                    livewireInstance.set('form.user_roles', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        $('#modal-edit-user').on('shown.bs.modal', function () {
            const $select = $('#select-branches-modal-edit-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-edit-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_branches');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []).map(Number);
                    livewireInstance.set('form.user_branches', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        $('#modal-edit-user').on('shown.bs.modal', function () {
            const $select = $('#select-permissions-modal-edit-user');

            if ($select.length) {
                $select.select2({
                    dropdownParent: $('#modal-edit-user')
                });

                const livewireInstance = Livewire.find($select.closest('[wire\\:id]').attr('wire:id'));
                if (livewireInstance) {
                    const selected = livewireInstance.get('form.user_permissions');
                    $select.val(selected).trigger('change');
                }

                $select.on('change', function () {
                    const selectedValues = ($(this).val() || []);
                    livewireInstance.set('form.user_permissions', selectedValues);
                    console.log('Dikirim ke Livewire:', selectedValues);
                });
               
            }
        });

        // $('#select-roles-modal-edit-user').select2({
        //     dropdownParent: $('#modal-edit-user'),

        // });
        // $('#select-roles-modal-edit-user').on('change', function() {
        //     @this.set('form.user_roles', $(this).val())
        // })

        // $('#select-branches-modal-edit-user').select2({
        //     dropdownParent: $('#modal-edit-user'),
        // });
        // $('#select-branches-modal-edit-user').on('change', function() {
        //     @this.set('form.user_branches', $(this).val())
        // })

        Livewire.on('modal-add-user', (event) => {
            if (event.action == 'show') {
                $('#select-roles-modal-create-user').val('').trigger('change');

                $('#select-branches-modal-create-user').val('').trigger('change');
            }
            $('#modal-create-user').modal(event.action);
        });
        Livewire.on('modal-edit-user', (event) => {
            $('#select-roles-modal-edit-user').val(event.user_roles).trigger('change');
            $('#select-branches-modal-edit-user').val(event.user_branches).trigger('change');
            $('#modal-edit-user').modal(event.action);
        });

        Livewire.on('modal-edit-password', (e) => {
            $('#modal-edit-password').modal(e.action);
        })
        Livewire.on('modal-confirm-delete', (event) => {
            $('#modal-confirm-delete').modal(event.action);
            $('#btn-confirm-delete').attr('wire:click', 'delete("' + event.id + '")');
        });
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        })
    </script>
@endpush
