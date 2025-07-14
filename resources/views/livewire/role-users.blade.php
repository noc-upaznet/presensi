<div>
    <div class="app-content-header mb-4">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            {{-- <div class="row mt-5 mb-4">
                <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Role Users</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Role Users</li>
                    </ol>
                </div>
            </div> --}}

            <div class="app-content" id="tab-role-users">
                <!--begin::Container-->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="background-color: var(--bs-body-bg);">
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-user') active @endif"
                            wire:click='setTab("pills-user")' id="pills-user-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-user" data-tab-name="pills-user" type="button" role="tab"
                            aria-controls="pills-user" aria-selected="true">Users</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-roles') active @endif"
                            wire:click='setTab("pills-roles")' id="pills-roles-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-roles" data-tab-name="pills-roles" type="button" role="tab"
                            aria-controls="pills-roles" aria-selected="true">Roles</button>
                        </li> --}}

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-jabatan') active @endif"
                            wire:click='setTab("pills-jabatan")' id="pills-jabatan-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-jabatan" data-tab-name="pills-jabatan" type="button" role="tab"
                            aria-controls="pills-jabatan" aria-selected="true">Jabatan</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-divisi') active @endif"
                            wire:click='setTab("pills-divisi")' id="pills-divisi-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-divisi" data-tab-name="pills-divisi" type="button" role="tab"
                            aria-controls="pills-divisi" aria-selected="true">Divisi</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-entitas') active @endif"
                            wire:click='setTab("pills-entitas")' id="pills-entitas-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-entitas" data-tab-name="pills-entitas" type="button" role="tab"
                            aria-controls="pills-entitas" aria-selected="true">Entitas</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- <div class="tab-pane fade @if ($tab == 'pills-user') active show @endif " id="pills-user"
                            role="tabpanel" aria-labelledby="pills-user-tab">

                            @if ($tab == 'pills-user')
                                <livewire:users />
                            @endif

                        </div>

                        <div class="tab-pane fade @if ($tab == 'pills-roles') active show @endif " id="pills-roles"
                            role="tabpanel" aria-labelledby="pills-roles-tab">

                            @if ($tab == 'pills-roles')
                                <livewire:roles />
                            @endif

                        </div> --}}

                        <div class="tab-pane fade @if ($tab == 'pills-jabatan') active show @endif " id="pills-jabatan"
                            role="tabpanel" aria-labelledby="pills-jabatan-tab">

                            @if ($tab == 'pills-jabatan')
                                <livewire:jabatan />
                            @endif

                        </div>

                        <div class="tab-pane fade @if ($tab == 'pills-divisi') active show @endif " id="pills-divisi"
                            role="tabpanel" aria-labelledby="pills-divisi-tab">

                            @if ($tab == 'pills-divisi')
                                <livewire:divisi />
                            @endif

                        </div>
                        <div class="tab-pane fade @if ($tab == 'pills-entitas') active show @endif " id="pills-entitas"
                            role="tabpanel" aria-labelledby="pills-entitas-tab">

                            @if ($tab == 'pills-entitas')
                                <livewire:entitas />
                            @endif
                        </div>
                    </div>
                    
                {{-- </div> --}}
                <!--end::Row-->
            </div>
        </div>
        <!--end::Container-->
    </div>

    <livewire:modal-role.modal-users />

    <livewire:modal-role.modal-roles />

    <livewire:modal-role.modal-jabatan />

    <livewire:modal-role.modal-divisi />

    <livewire:modal-role.modal-entitas />

</div>

@push('scripts')
<script>
    Livewire.on('modalAdd', (event) => {
        $('#modalAdd').modal(event.action);
    });

    Livewire.on('modalAddDivisi', (event) => {
        $('#modalAddDivisi').modal(event.action);
    });

    Livewire.on('modalAddEntitas', (event) => {
        $('#modalAddEntitas').modal(event.action);
    });

    Livewire.on('modalAddRoles', (event) => {
        $('#modalAddRoles').modal(event.action);
    });

    Livewire.on('modalEdit', (event) => {
        $('#modalEdit').modal(event.action);
    });

    Livewire.on('modalEditDivisi', (event) => {
        $('#modalEditDivisi').modal(event.action);
    });

    Livewire.on('modalEditEntitas', (event) => {
        $('#modalEditEntitas').modal(event.action);
    });

    Livewire.on('refresh', () => {
        Livewire.dispatch('refreshTable');
    });

    Livewire.on('swal', (e) => {
        Swal.fire(e.params);
    });
</script>
@endpush
