<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">List Presensi</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Presensi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Hadir Karyawan</h5>
                <div class="card-tools">
                    <span class="text-muted">{{ now()->format('l, d M Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3 text-center">
                    @foreach ([['Total Karyawan', 104], ['Tepat Waktu', 87], ['Terlambat', 10], ['Izin', 4], ['Cuti',
                    2], ['Lupa Absen', 0]] as [$label, $value])
                    <div class="col-md-2">
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h4>{{ $value }}</h4>
                                <p class="text-muted">{{ $label }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group w-25">
                        <input type="date" class="form-control" wire:model="tanggal">
                        <button class="btn btn-primary" wire:click="applyFilter">Apply Filter</button>
                    </div>
                    <div class="input-group w-25">
                        <input type="text" class="form-control" placeholder="Search..."
                            wire:model.debounce.500ms="search">
                    </div>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Karyawan</th>
                            <th>Status</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Additional Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawanList as $karyawan)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="./assets/img/user4-128x128.jpg" class="img-circle elevation-1 me-2"
                                        width="35" height="35">
                                    <div>
                                        <strong>{{ $karyawan['nama'] }}</strong><br>
                                        <small class="text-muted">{{ $karyawan['divisi'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                $color = match($karyawan->status) {
                                'Tepat Waktu' => 'success',
                                'Terlambat' => 'danger',
                                'Izin' => 'info',
                                'Cuti' => 'primary',
                                'Lupa Absen' => 'warning',
                                default => 'secondary',
                                };
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ $karyawan->status }}</span>
                            </td>

                            <td>{{ $karyawan['clock_in'] }}</td>
                            <td>{{ $karyawan['clock_out'] }}</td>
                            <td>{{ $karyawan['additional'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $karyawanList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>