<!--begin::Header-->
<nav class="navbar navbar-expand shadow-sm" style="background-color: var(--bs-body-bg); z-index: 1030;">
    <div class="container-fluid">

        <!--begin::Start Navbar links-->
        <ul class="navbar-nav align-items-center">
            {{-- Sidebar Toggle --}}
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            {{-- Nama Entitas khusus Admin --}}
            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('hr'))
                <li class="nav-item">
                    <span class="fw-bold fs-4 text-primary ms-auto">
                        {{ session('selected_entitas', 'UHO') }}
                    </span>
                </li>
            @endif
        </ul>
        <!--end::Start Navbar links-->

        <!--begin::End Navbar links-->
        <ul class="navbar-nav ms-auto">

            {{-- Dropdown Entitas khusus Admin --}}
            {{-- @hasanyrole('admin|hr|user') --}}
            @can('branches-view')
                <li class="nav-item dropdown border border-secondary rounded ms-2">
                    <button
                        class="btn btn-link nav-link py-2 px-2 dropdown-toggle d-flex align-items-center rounded text-secondary"
                        id="dropdown-entitas" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="dropdown-entitas-text">{{ $selectedEntitas ?? 'Branch' }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end border border-secondary rounded shadow"
                        aria-labelledby="dropdown-entitas" style="--bs-dropdown-min-width: 10rem; z-index:1055;">
                        @foreach ($entitasList as $entitas)
                            <li>
                                <button class="dropdown-item d-flex align-items-center text-secondary"
                                    wire:click="selectEntitas('{{ $entitas }}')">
                                    {{ $entitas }}
                                    @if ($selectedEntitas === $entitas)
                                        <i class="bi bi-check-lg ms-auto"></i>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endcan


            {{-- @endhasanyrole --}}
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown user-menu">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill me-1"></i>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp

                    @if ($unreadCount > 0)
                        <span class="navbar-badge badge text-bg-warning" style="font-size: 8px;">
                            {{ $unreadCount }}
                        </span>
                    @endif

                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

                    <span class="dropdown-item dropdown-header">
                        {{ $unreadCount }} Notifikasi
                    </span>

                    <div class="dropdown-divider"></div>

                    @forelse (auth()->user()->unreadNotifications->take(5) as $notif)
                        @if (($notif->data['type'] ?? '') === 'kontrak_reminder')
                            <div class="dropdown-item">
                                <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>

                                <strong style="font-size: 12px">
                                    {{ $notif->data['nama'] }}
                                </strong><br>

                                <small class="text-muted" style="font-size: 10px">
                                    Kontrak {{ $notif->data['status'] }} berakhir
                                    {{ $notif->data['sisa_hari'] }} hari lagi
                                </small>

                                <span class="float-end text-secondary" style="font-size: 8px">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="dropdown-divider"></div>
                        @endif
                    @empty
                        <div class="dropdown-item text-center text-muted">
                            Tidak ada notifikasi baru
                        </div>
                        <div class="dropdown-divider"></div>
                    @endforelse

                    <a href="{{ route('notifikasi') }}" class="dropdown-item dropdown-footer">
                        Lihat Semua Notifikasi
                    </a>
                </div>


            </li>
            <!--end::Notifications Dropdown Menu-->

            {{-- User Menu --}}
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle text-secondary" data-bs-toggle="dropdown"
                    role="button" aria-expanded="false">
                    <i class="bi bi-person-fill"></i> {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow rounded-3"
                    style="min-width: 6rem; padding: 0.25rem 0;">
                    <li>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Theme Switcher --}}
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <span class="theme-icon-active"><i class="my-1"></i></span>
                    <span class="d-lg-none ms-2" id="bd-theme-text"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="light">
                            <i class="bi bi-sun-fill me-2"></i> Light
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark">
                            <i class="bi bi-moon-fill me-2"></i> Dark
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="auto">
                            <i class="bi bi-circle-fill-half-stroke me-2"></i> Auto
                        </button>
                    </li>
                </ul>
            </li>
        </ul>
        <!--end::End Navbar links-->
    </div>
</nav>
<!--end::Header-->
