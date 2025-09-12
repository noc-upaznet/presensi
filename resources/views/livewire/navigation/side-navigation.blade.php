@php
    $user = auth()->user();
@endphp

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="" class="brand-link">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo"
                 class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light"><b>Sistem Presensi</b></span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                {{-- Dashboard --}}
                @role('admin')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                           <i class="bi bi-speedometer"></i>
                           <p>Dashboard</p>
                        </a>
                    </li>
                @endrole
                @role('hr')
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-speedometer"></i>
                            <p>Dashboard<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="margin-left: 20px;">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i>
                                <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('clock-in') }}"
                                class="nav-link {{ request()->routeIs('clock-in') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i>
                                <p>Dashboard</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                @hasanyrole('user|spv')
                    <li class="nav-item">
                        <a href="{{ route('clock-in') }}"
                           class="nav-link {{ request()->routeIs('clock-in') ? 'active' : '' }}">
                           <i class="bi bi-speedometer"></i>
                           <p>Dashboard</p>
                        </a>
                    </li>
                @endhasanyrole

                {{-- Data Master khusus Admin --}}
                @hasanyrole('admin|hr')
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

                    {{-- Lokasi --}}
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-map"></i>
                            <p>Lokasi<i class="nav-arrow bi bi-chevron-right"></i></p>
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
                        <a href="{{ route('data-user') }}"
                           class="nav-link {{ request()->routeIs('data-user') ? 'active' : '' }}">
                           <i class="bi bi-person"></i>
                           <p>Data User</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('data-masters') }}"
                           class="nav-link {{ request()->routeIs('data-masters') ? 'active' : '' }}">
                           <i class="bi bi-person-fill-gear"></i>
                           <p>Data Master</p>
                        </a>
                    </li>

                    {{-- Payroll --}}
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-clipboard-plus"></i>
                            <p>Payroll<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="margin-left: 20px;">
                            <li class="nav-item">
                                <a href="{{ route('payroll') }}"
                                class="nav-link {{ request()->routeIs('payroll') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Slip Gaji</p>
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
                @endhasanyrole

                {{-- Riwayat Presensi (semua role) --}}
                <li class="nav-item">
                    <a href="{{ route('riwayat-presensi') }}"
                       class="nav-link {{ request()->routeIs('riwayat-presensi') ? 'active' : '' }}">
                       <i class="bi bi-list-task"></i>
                       <p>Riwayat Presensi</p>
                    </a>
                </li>

                @hasanyrole('spv-teknisi|spv-helpdesk')
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
                @endhasanyrole

                {{-- Approval untuk hr, spv, admin --}}
                @hasanyrole('hr|spv|admin')
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-clipboard-plus"></i>
                            <p>Approval<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="margin-left: 20px;">
                            <li class="nav-item">
                                <a href="{{ route('pengajuan') }}"
                                   class="nav-link {{ request()->routeIs('pengajuan') ? 'active' : '' }}">
                                   <i class="nav-icon bi bi-circle"></i>
                                   <p>Pengajuan Cuti/Izin</p>
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
                @endhasanyrole
                
                {{-- Extra untuk SPV --}}
                @role('spv')
                    <li class="nav-item">
                        <a href="{{ route('riwayat-presensi-staff') }}"
                           class="nav-link {{ request()->routeIs('riwayat-presensi-staff') ? 'active' : '' }}">
                           <i class="bi bi-list-task"></i>
                           <p>Riwayat Presensi Staff</p>
                           @if ($PresensiMenungguCount > 0)
                               <span class="badge bg-danger ms-2">{{ $PresensiMenungguCount }}</span>
                           @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('slip-gaji') }}"
                           class="nav-link {{ request()->routeIs('slip-gaji') ? 'active' : '' }}">
                           <i class="bi bi-box-arrow-left"></i>
                           <p>Slip Gaji</p>
                        </a>
                    </li>
                @endrole

                {{-- Pengajuan khusus user --}}
                @role('user')
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="bi bi-clipboard-plus"></i>
                            <p>Pengajuan<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview" style="margin-left: 20px;">
                            <li class="nav-item">
                                <a href="{{ route('pengajuan') }}"
                                   class="nav-link {{ request()->routeIs('pengajuan') ? 'active' : '' }}">
                                   <i class="nav-icon bi bi-circle"></i>
                                   <p>Pengajuan Cuti/Izin</p>
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
                        <a href="{{ route('slip-gaji') }}"
                           class="nav-link {{ request()->routeIs('slip-gaji') ? 'active' : '' }}">
                           <i class="bi bi-box-arrow-left"></i>
                           <p>Slip Gaji</p>
                        </a>
                    </li>
                @endrole
                @role('admin')
                    @can('manage-user')
                        <li class="nav-item">
                            <a href="{{ route('manage-user') }}"
                            class="nav-link {{ request()->routeIs('manage-user') ? 'active' : '' }}">
                            <i class="bi bi-person-fill-gear"></i>
                            <p>Manage User</p>
                            </a>
                        </li>
                    @endcan
                @endrole

                {{-- Ganti Password --}}
                <li class="nav-item">
                    <a href="{{ route('ganti-password') }}"
                       class="nav-link {{ request()->routeIs('ganti-password') ? 'active' : '' }}">
                       <i class="bi bi-key"></i>
                       <p>Ganti Password</p>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                       class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}">
                       <i class="bi bi-box-arrow-left"></i>
                       <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
