@push('styles')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endpush
<div>
    <div class="app-content-header mb-4">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="app-content" id="tab-role-users">
                <!--begin::Container-->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="background-color: var(--bs-body-bg);">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'users') active @endif"
                            wire:click='setTab("users")' id="users-tab" data-bs-toggle="tab"
                            data-bs-target="#users" data-tab-name="users" type="button" role="tab"
                            aria-controls="users" aria-selected="true">Users</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'permissions') active @endif"
                            wire:click='setTab("permissions")' id="permissions-tab" data-bs-toggle="tab"
                            data-bs-target="#permissions" data-tab-name="permissions" type="button" role="tab"
                            aria-controls="permissions" aria-selected="true">Permissions</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold @if ($tab == 'pills-roles') active @endif"
                            wire:click='setTab("pills-roles")' id="pills-roles-tab" data-bs-toggle="tab"
                            data-bs-target="#pills-roles" data-tab-name="pills-roles" type="button" role="tab"
                            aria-controls="pills-roles" aria-selected="true">Roles</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if ($tab == 'users') active show @endif " id="users"
                            role="tabpanel" aria-labelledby="users-tab">

                            @if ($tab == 'users')
                                <livewire:user.users />
                            @endif

                        </div>

                        <div class="tab-pane fade @if ($tab == 'permissions') active show @endif " id="permissions"
                            role="tabpanel" aria-labelledby="permissions-tab">

                            @if ($tab == 'permissions')
                                <livewire:user.permissions />
                            @endif

                        </div>

                        <div class="tab-pane fade @if ($tab == 'pills-roles') active show @endif " id="pills-roles"
                            role="tabpanel" aria-labelledby="pills-roles-tab">

                            @if ($tab == 'pills-roles')
                                <livewire:user.roles />
                            @endif

                        </div>
                    </div>
                <!--end::Row-->
            </div>
        </div>
        <!--end::Container-->
    </div>
</div>
@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
@endpush
