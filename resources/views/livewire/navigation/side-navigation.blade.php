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
                    <i class="nav-icon bi bi-palette"></i>
                    <p>Data Karyawan</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('jadwal-shift') }}" 
                   class="nav-link {{ request()->routeIs('jadwal-shift') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-palette"></i>
                    <p>Pembagian Shift</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('clock-in') }}" 
                   class="nav-link {{ request()->routeIs('clock-in') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-grid-fill"></i>
                    <p>Welcome</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('clock-in-selfie') }}" 
                   class="nav-link {{ request()->routeIs('clock-in-selfie') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-grid-fill"></i>
                    <p>ClockIn</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('clock-out') }}" 
                   class="nav-link {{ request()->routeIs('clock-out') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-grid-fill"></i>
                    <p>ClockOut</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('clockout') }}" 
                   class="nav-link {{ request()->routeIs('clockout') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-grid-fill"></i>
                    <p>AFTER PRESENSI</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('riwayat-presensi') }}" 
                   class="nav-link {{ request()->routeIs('riwayat-presensi') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-list-task"></i>
                    <p>Riwayat Presensi</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pengajuan-izin-cuti') }}" 
                   class="nav-link {{ request()->routeIs('pengajuan-izin-cuti') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-file-earmark-text"></i>
                    <p>Pengajuan Cuti/Izin</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pengajuan-lembur') }}" 
                   class="nav-link {{ request()->routeIs('pengajuan-lembur') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-alarm"></i>
                    <p>Pengajuan Lembur</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile-pic') }}" 
                   class="nav-link {{ request()->routeIs('profile-pic') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person"></i>
                    <p>Profile Saya</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('slip-gaji') }}" 
                   class="nav-link {{ request()->routeIs('slip-gaji') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-receipt"></i>
                    <p>Slip Gaji</p>
                </a>

            <li class="nav-item">
                <a href="{{ route('ganti-password') }}" 
                   class="nav-link {{ request()->routeIs('ganti-password') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-lock-fill"></i>
                    <p>Ganti Password</p>
                </a>
            </li>
            
        </ul>
        <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>