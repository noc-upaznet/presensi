<div class="container-fluid">
    {{-- Summary Cards --}}
    <div class="row g-3 mb-3 mt-5" syle="background-color: var(--bs-body-bg);">
        @php
        $cards = [
        ['title' => 'TOTAL PEGAWAI', 'value' => '104', 'icon' => 'fa-users', 'color' => 'warning'],
        ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp. 75.985.069', 'icon' => 'fa-money-bill-wave', 'color' =>
        'info', 'note' => '▲ -5% dari bulan sebelumnya'],
        ['title' => 'IZIN/CUTI', 'value' => '6', 'icon' => 'fa-calendar-times', 'color' => 'danger'],
        ['title' => 'MASUK', 'value' => '98', 'icon' => 'fa-user-check', 'color' => 'success'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 d-flex flex-column" style="background-color: var(--bs-body-bg);">
                <div class="card-body text-center flex-grow-1">
                    <div class="d-flex justify-content-center mb-2">
                        <div class="rounded-circle bg-{{ $card['color'] }} d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="fas {{ $card['icon'] }} text-white"></i>
                        </div>
                    </div>
                    <h6 class="text-muted text-uppercase mb-1">{{ $card['title'] }}</h6>
                    <h3 class="fw-bold mb-2">{{ $card['value'] }}</h3>
                    @isset($card['note'])
                    <p class="text-danger small mb-0">{{ $card['note'] }}</p>
                    @endisset
                </div>
                <div class="card-footer bg-info text-white py-2 text-center">
                    <a href="#" class="text-white text-decoration-none fw-medium">
                        More Info <i class="fa-solid fa-circle-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Grafik dan Status --}}
    <div class="row">
        {{-- Grafik Presensi --}}
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow-sm h-100" style="background-color: var(--bs-body-bg);">
                    <div class="card-header fw-bold">PRESENSI PERBULAN</div>
                    <div class="card-body" style="height: 320px;">
                        <canvas id="grafikPresensi" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="card shadow-sm h-100" style="background-color: var(--bs-body-bg);">
                    <div class="card-header fw-bold">PENDIDIKAN</div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 320px;">
                        <canvas id="chartPendidikan" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            {{-- Status Karyawan --}}
            <div class="card shadow-sm mt-3" style="background-color: var(--bs-body-bg);">
                <div class="card-header fw-bold">STATUS KARYAWAN</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Total</span>
                        <span>104</span>
                    </div>
                    <div class="progress mb-2" style="height: 14px;">
                        <div class="progress-bar bg-primary" style="width: 87.5%"></div>
                        <div class="progress-bar bg-warning" style="width: 8.65%"></div>
                        <div class="progress-bar bg-danger" style="width: 3.85%"></div>
                    </div>
                    <ul class="list-unstyled small mb-2">
                        <li class="d-flex align-items-center"><i class="fas fa-square text-primary me-2"></i> Tetap
                            (91
                            - 87.5%)</li>
                        <li class="d-flex align-items-center"><i class="fas fa-square text-warning me-2"></i>
                            Kontrak (9
                            - 8.65%)</li>
                        <li class="d-flex align-items-center"><i class="fas fa-square text-danger me-2"></i>
                            Probation
                            (4 - 3.85%)</li>
                    </ul>
                </div>
                <div class="card-footer bg-info text-white py-2 text-center">
                    <a href="#" class="text-white text-decoration-none fw-medium">
                        More Info <i class="fa-solid fa-circle-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            {{-- Jadwal Shift --}}
            <div class="card shadow-sm mt-3" style="background-color: var(--bs-body-bg);">
                <div class="card-header fw-bold">JADWAL SHIFT HARI INI</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div><strong>Shift Pagi</strong></div>
                        <div>07:00 - 15:00</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div><strong>Shift Siang</strong></div>
                        <div>15:00 - 23:00</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div><strong>Shift Malam</strong></div>
                        <div>23:00 - 07:00</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            {{-- Insight SDM --}}
            <div class="card shadow-sm mt-3" style="background-color: var(--bs-body-bg);">
                <div class="card-header fw-bold">INSIGHT SDM</div>
                <div class="card-body small">
                    <p>👥 Rata-rata usia karyawan: <strong>29 tahun</strong></p>
                    <p>👩‍🏫 Pendidikan tertinggi terbanyak: <strong>S1</strong></p>
                    <p>📈 Pertumbuhan karyawan bulan ini: <strong>+2 orang</strong></p>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const presensiCtx = document.getElementById('grafikPresensi');
    new Chart(presensiCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                { label: 'Tepat Waktu', backgroundColor: '#9966ff', data: [74, 102, 88, 75, 98, 99, 102, 95, 90, 99, 99, 103] },
                { label: 'Terlambat', backgroundColor: '#ff6384', data: [17, 0, 8, 16, 15, 0, 0, 0, 5, 5, 3, 0] },
                { label: 'Tidak Absen', backgroundColor: '#36a2eb', data: [13, 0, 2, 1, 3, 1, 0, 2, 0, 1, 3, 0] }
            ]
        },
       options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            layout: {
                padding: 10
            }
        }
    });

    const pendidikanCtx = document.getElementById('chartPendidikan');
    new Chart(pendidikanCtx, {
        type: 'pie',
        data: {
            labels: ['SMK', 'D3', 'S1', 'S2'],
            datasets: [{
                data: [45, 17, 29, 13],
                backgroundColor: ['#36a2eb', '#ffcd56', '#4bc0c0', '#9966ff']
            }]
        }
    });
</script>
@endpush