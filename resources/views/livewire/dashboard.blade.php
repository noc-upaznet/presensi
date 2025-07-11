<div class="container-fluid">
    {{-- Summary Cards --}}
    <div class="row g-3 mb-3 mt-5" syle="background-color: var(--bs-body-bg);">
        @php
        $cards = [
        ['title' => 'TOTAL PEGAWAI', 'value' => $totalPegawai ?? '0', 'icon' => 'fa-users', 'color' => 'warning', 'href' => '<a href="' . route('data-karyawan') . '" class="text-white text-decoration-none fw-medium">
            More Info <i class="fa-solid fa-circle-chevron-right"></i>
        </a>'],
        ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp.' . number_format($totalGaji) ?? '0', 'icon' => 'fa-money-bill-wave', 'color' => 'info', 'note' => '▲ -5% dari bulan sebelumnya', 'href' => '<a href="' . route('payroll') . '" class="text-white text-decoration-none fw-medium">
            More Info <i class="fa-solid fa-circle-chevron-right"></i>
        </a>'],
        ['title' => 'IZIN/CUTI', 'value' => $izinCuti ?? '0', 'icon' => 'fa-calendar-times', 'color' => 'danger', 'href' => '<a href="' . route('pengajuan') . '" class="text-white text-decoration-none fw-medium">
            More Info <i class="fa-solid fa-circle-chevron-right"></i>
        </a>'],
        ['title' => 'MASUK', 'value' => $totalPresensi ?? '0', 'icon' => 'fa-user-check', 'color' => 'success', 'href' => '<a href="' . route('riwayat-presensi') . '" class="text-white text-decoration-none fw-medium">
            More Info <i class="fa-solid fa-circle-chevron-right"></i>
        </a>'],
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
                    @isset($card['href'])
                        {!! $card['href'] !!}
                    @endisset
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
                        <span>{{ array_sum($statusKaryawan ?? []) }}</span>
                    </div>
                    @php
                        $totalStatus = array_sum($statusKaryawan ?? []);
                        $persen = function($jumlah) use ($totalStatus) {
                            return $totalStatus > 0 ? round(($jumlah / $totalStatus) * 100) : 0;
                        };
                    @endphp
                    <div class="progress mb-2" style="height: 14px;">
                        <div class="progress-bar bg-primary" style="width: {{ $persen($statusKaryawan['OJT'] ?? 0) }}%"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $persen($statusKaryawan['PKWT Kontrak'] ?? 0) }}%"></div>
                        <div class="progress-bar bg-danger" style="width: {{ $persen($statusKaryawan['Probation'] ?? 0) }}%"></div>
                    </div>
                    <ul class="list-unstyled small mb-2">
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-primary me-2"></i>
                            OJT ({{ $statusKaryawan['OJT'] ?? 0 }} - {{ $persen($statusKaryawan['OJT'] ?? 0) }}%)
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-warning me-2"></i>
                            PKWT Kontrak ({{ $statusKaryawan['PKWT Kontrak'] ?? 0 }} - {{ $persen($statusKaryawan['PKWT Kontrak'] ?? 0) }}%)
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-danger me-2"></i>
                            Probation ({{ $statusKaryawan['Probation'] ?? 0 }} - {{ $persen($statusKaryawan['Probation'] ?? 0) }}%)
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-info text-white py-2 text-center">
                    <a href="{{ route('data-karyawan') }}" class="text-white text-decoration-none fw-medium">
                        More Info <i class="fa-solid fa-circle-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-sm mt-3" style="background-color: var(--bs-body-bg);">
                <div class="card-header fw-bold">JADWAL SHIFT HARI INI</div>
                <div class="card-body">
                    @foreach ($shiftKategori as $kategori => $shifts)
                        <div class="mb-3">
                            <div class="fw-bold mb-2" style="color: var(--bs-body-color);">{{ $kategori }}</div>
                            @foreach ($shifts as $shift)
                                <div class="d-flex justify-content-between mb-1">
                                    <div>{{ $shift['label'] }}</div>
                                    <div>{{ $shift['jam'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
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
    const presensiCtx = document.getElementById('grafikPresensi').getContext('2d');
    new Chart(presensiCtx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Tepat Waktu',
                    backgroundColor: '#9966ff',
                    data: @json($tepatWaktu)
                },
                {
                    label: 'Terlambat',
                    backgroundColor: '#ff6384',
                    data: @json($terlambat)
                },
                {
                    label: 'Tidak Absen',
                    backgroundColor: '#36a2eb',
                    data: @json($tidakAbsen)
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
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