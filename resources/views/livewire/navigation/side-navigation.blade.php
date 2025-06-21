<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="" class="brand-link">
        <!--begin::Brand Image-->
        <img
            src="{{ asset('assets/img/logo.png') }}"
            alt="AdminLTE Logo"
            class="brand-image opacity-75 shadow"
        />
        <!--end::Brand Image-->
        <!--begin::Brand Text-->
        <span class="brand-text fw-light">Sistem Presensi</span>
        <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
        <!--begin::Sidebar Menu-->
        <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="false"
        >
             @if (auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endif
            @if (auth()->user()->role == 'user' || auth()->user()->role == 'hr' || auth()->user()->role == 'spv')
                <li class="nav-item">
                    <a href="{{ route('clock-in') }}" 
                    class="nav-link {{ request()->routeIs('clock-in') ? 'active' : '' }}">
                    <i class="bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
            @endif
            
            @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hr' || auth()->user()->role == 'spv')
                <li class="nav-item">
                    <a href="{{ route('data-karyawan') }}" 
                    class="nav-link {{ request()->routeIs('data-karyawan') ? 'active' : '' }}">
                    <i class="bi bi-person-add"></i>
                        <p>Data Karyawan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('template-mingguan') }}" 
                    class="nav-link {{ request()->routeIs('template-mingguan') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-plus"></i>
                        <p>Template Mingguan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pembagian-shift') }}" 
                    class="nav-link {{ request()->routeIs('pembagian-shift') ? 'active' : '' }}">
                    <i class="bi bi-calendar2-plus"></i>
                        <p>Pembagian Shift</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('jadwal-shift') }}" 
                    class="nav-link {{ request()->routeIs('jadwal-shift') ? 'active' : '' }}">
                    <i class="bi bi-calendar-range"></i>
                        <p>Jadwal Shift</p>
                    </a>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="bi bi-map"></i>
                        <p>
                        Lokasi
                        <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="margin-left: 20px;">
                        <li class="nav-item">
                            <a href="{{ route('list-lokasi') }}" 
                            class="nav-link {{ request()->routeIs('list-lokasi') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                                <p>List Lokasi</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('role-lokasi') }}" 
                            class="nav-link {{ request()->routeIs('role-lokasi') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                                <p>Role Lokasi Presensi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('role-users') }}" 
                        class="nav-link {{ request()->routeIs('role-users') ? 'active' : '' }}">
                        <i class="bi bi-person-fill-gear"></i>
                            <p>Role Users</p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="bi bi-clipboard-plus"></i>
                        <p>
                            Slip Gaji
                        <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="margin-left: 20px;">
                        <li class="nav-item">
                            <a href="{{ route('payroll') }}" 
                            class="nav-link {{ request()->routeIs('payroll') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                                <p>Payroll</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jenis-tunjangan') }}" 
                            class="nav-link {{ request()->routeIs('jenis-tunjangan') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                                <p>Jenis Tunjangan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jenis-potongan') }}" 
                            class="nav-link {{ request()->routeIs('jenis-potongan') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-circle"></i>
                                <p>Jenis Potongan</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('riwayat-presensi') }}" 
                   class="nav-link {{ request()->routeIs('riwayat-presensi') ? 'active' : '' }}">
                   <i class="bi bi-list-task"></i>
                    <p>Riwayat Presensi</p>
                </a>
            </li>

            <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                    <i class="bi bi-clipboard-plus"></i>
                    <p>
                    Pengajuan
                    <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="margin-left: 20px;">
                    <li class="nav-item">
                        <a href="{{ route('pengajuan') }}" 
                        class="nav-link {{ request()->routeIs('pengajuan') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                            <p>
                                Pengajuan Cuti/Izin
                            </p>
                            @if ($pengajuanMenungguCount > 0)
                                <span class="badge bg-danger ms-2">{{ $pengajuanMenungguCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('pengajuan-lembur') }}" 
                        class="nav-link {{ request()->routeIs('pengajuan-lembur') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                            <p>Pengajuan Lembur</p>
                            @if ($lemburMenungguCount > 0)
                                <span class="badge bg-danger ms-2">{{ $lemburMenungguCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile') }}" 
                   class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                   <i class="bi bi-person-circle"></i>
                    <p>Profile</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('logout') }}" 
                   class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}">
                   <i class="bi bi-box-arrow-left"></i>
                    <p>Logout</p>
                </a>
            </li>

        </ul>
        <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>