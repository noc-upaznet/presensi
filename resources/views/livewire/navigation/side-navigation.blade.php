<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('assets/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
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
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                    </ul>

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

                <li class="nav-item">
                    <a href="{{ route('pengajuan') }}"
                        class="nav-link {{ request()->routeIs('pengajuan') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-plus"></i>
                        <p>Pengajuan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('list-lokasi') }}"
                        class="nav-link {{ request()->routeIs('list-lokasi') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i>
                        <p>Lokasi Presensi Karyawan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('role-lokasi') }}"
                        class="nav-link {{ request()->routeIs('role-lokasi') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i>
                        <p>Role Lokasi Presensi Karyawan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pencairan-gaji') }}"
                        class="nav-link {{ request()->routeIs('pencairan-gaji') ? 'active' : '' }}">
                        <i class="bi bi-cash coin"></i>
                        <p>Pencairan Gaji Karyawan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('payroll') }}"
                        class="nav-link {{ request()->routeIs('payroll') ? 'active' : '' }}">
                        <i class="bi bi-cash coin"></i>
                        <p>Payroll</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('presensi-karyawan') }}"
                        class="nav-link {{ request()->routeIs('presensi-karyawan') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <p>List Presensi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dashboard-admin') }}"
                        class="nav-link {{ request()->routeIs('dashboard-admin') ? 'active' : '' }}">
                        <i class="bi bi-speedometer"></i>
                        <p>Dashboard Admin</p>
                    </a>
                </li>

            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>