<div>
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-users fa-2x text-warning"></i>
                        </div>
                        <h6 class="card-title text-muted mb-1">TOTAL PEGAWAI</h6>
                        <h3 class="fw-bold mb-2">104</h3>
                        <a href="#" class="small text-primary">More Info <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                        <h6 class="card-title text-muted mb-1">TOTAL GAJI KARYAWAN</h6>
                        <h5 class="fw-bold mb-1">Rp. 75.985.069</h5>
                        <p class="text-danger small mb-1">▲ -5% dari bulan sebelumnya</p>
                        <a href="#" class="small text-primary">More Info <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-calendar-times fa-2x text-danger"></i>
                        </div>
                        <h6 class="card-title text-muted mb-1">IZIN/CUTI</h6>
                        <h3 class="fw-bold mb-2">6</h3>
                        <a href="#" class="small text-primary">More Info <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-user-check fa-2x text-primary"></i>
                        </div>
                        <h6 class="card-title text-muted mb-1">MASUK</h6>
                        <h3 class="fw-bold mb-2">98</h3>
                        <a href="#" class="small text-primary">More Info <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>


        {{-- Grafik Presensi & Pie Chart Pendidikan/Status --}}
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong>PRESENSI PERBULAN</strong></div>
                    <div class="card-body">
                        <canvas id="grafikPresensi"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                {{-- Pie Chart Pendidikan --}}
                <div class="card mb-3">
                    <div class="card-header"><strong>PENDIDIKAN</strong></div>
                    <div class="card-body">
                        <canvas id="chartPendidikan"></canvas>
                    </div>
                </div>

                {{-- Status Karyawan --}}
                <div class="card">
                    <div class="card-header"><strong>STATUS KARYAWAN</strong></div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($statusKaryawan as $status => $jumlah)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $status }}
                                <span class="badge bg-primary rounded-pill">{{ $jumlah }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .card {
            border-radius: 12px;
        }

        .card-body {
            padding: 1.5rem;
        }
    </style>

</div>

@push('scripts')
<script>
    const ctx = document.getElementById('grafikPresensi');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                { label: 'Tepat Waktu', data: [74, 102, 75, 86, 98, 100], backgroundColor: '#4bc0c0' },
                { label: 'Terlambat', data: [17, 6, 16, 10, 5, 4], backgroundColor: '#ffcd56' },
                { label: 'Tidak Absen', data: [13, 9, 8, 6, 7, 6], backgroundColor: '#ff6384' },
            ]
        },
        options: { responsive: true }
    });

    const ctxPie = document.getElementById('chartPendidikan');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['SMK', 'D3', 'S1', 'S2'],
            datasets: [{
                data: [45, 23, 29, 7],
                backgroundColor: ['#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff']
            }]
        }
    });
</script>
@endpush