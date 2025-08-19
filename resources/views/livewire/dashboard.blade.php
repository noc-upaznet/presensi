<div class="container-fluid">
    <div class="app-content-header">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6 mt-5"><h3 class="mb-0" style="color: var(--bs-body-color);">Dashboard1</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    {{-- Summary Cards --}}
    {{-- <div class="row g-3 mb-3" syle="background-color: var(--bs-body-bg);">
        @php
        $cards = [
            ['title' => 'TOTAL PEGAWAI', 'value' => $totalPegawai ?? '0', 'icon' => 'fa-users', 'color' => 'warning', 'href' => '<a href="' . route('data-karyawan') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'IZIN/CUTI', 'value' => $izinCuti ?? '0', 'icon' => 'fa-calendar-times', 'color' => 'danger', 'href' => '<a href="' . route('pengajuan') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'MASUK', 'value' => $totalPresensi ?? '0', 'icon' => 'fa-user-check', 'color' => 'success', 'href' => '<a href="' . route('riwayat-presensi') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp.' . number_format($totalGaji) ?? '0', 'icon' => 'fa-money-bill-wave', 'color' => 'info', 'note' => $noteTotalGajiTetap, 'href' => '<a href="' . route('payroll') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'TOTAL GAJI KARYAWAN TITIP', 'value' => 'Rp.' . number_format($totalGajiTitip) ?? '0', 'icon' => 'fa-money-bill-wave', 'color' => 'info', 'note' => $noteTotalGajiTitip, 'href' => '<a href="' . route('payroll') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'BPJS Kesehatan', 'value' => 'Rp.' . number_format($totalBpjskes) ?? '0', 'icon' => 'fa-hospital-user', 'color' => 'info', 'note' => $noteTotalBpjskes, 'href' => '<a href="' . route('riwayat-presensi') . '" class="text-white text-decoration-none fw-medium">
                More Info <i class="fa-solid fa-circle-chevron-right"></i>
            </a>'],
            ['title' => 'BPJS Ketenagakerjaan', 'value' => 'Rp.' . number_format($totalBpjsJht) ?? '0', 'icon' => 'fa-briefcase', 'color' => 'primary', 'note' => $noteTotalBpjsJht, 'href' => '<a href="' . route('riwayat-presensi') . '" class="text-white text-decoration-none fw-medium">
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
    </div> --}}

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-3">
        @php
            use Illuminate\Support\Str;
            $cards = [
                ['title' => 'TOTAL PEGAWAI', 'value' => $totalPegawai ?? '0', 'icon' => 'fa-users', 'color' => 'warning', 'href' => route('data-karyawan')],
                ['title' => 'IZIN/CUTI', 'value' => $izinCuti ?? '0', 'icon' => 'fa-calendar-times', 'color' => 'danger', 'href' => route('pengajuan')],
                ['title' => 'MASUK', 'value' => $totalPresensi ?? '0', 'icon' => 'fa-user-check', 'color' => 'success', 'href' => route('riwayat-presensi')],
                ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp.' . number_format($totalGaji) ?? '0', 'icon' => 'fa-money-bill-wave', 'color' => 'info', 'note' => $noteTotalGajiTetap, 'href' => route('payroll')],
                ['title' => 'TOTAL GAJI KARYAWAN TITIP', 'value' => 'Rp.' . number_format($totalGajiTitip) ?? '0', 'icon' => 'fa-money-bill-wave', 'color' => 'info', 'note' => $noteTotalGajiTitip, 'href' => route('payroll')],
                ['title' => 'BPJS Kesehatan', 'value' => 'Rp.' . number_format($totalBpjskes) ?? '0', 'icon' => 'fa-hospital-user', 'color' => 'info', 'note' => $noteTotalBpjskes, 'href' => route('riwayat-presensi')],
                ['title' => 'BPJS Ketenagakerjaan', 'value' => 'Rp.' . number_format($totalBpjsJht) ?? '0', 'icon' => 'fa-briefcase', 'color' => 'primary', 'note' => $noteTotalBpjsJht, 'href' => route('riwayat-presensi')],
            ];

            $textColorMap = [
                'primary' => 'text-white',
                'secondary' => 'text-white',
                'success' => 'text-white',
                'danger' => 'text-white',
                'warning' => 'text-white',
                'info' => 'text-white',
                'dark' => 'text-white',
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col">
                <div class="border border-{{ $card['color'] }} rounded overflow-hidden h-100 d-flex flex-column">

                    {{-- Bagian Atas (Putih) --}}
                    <div class="bg-white position-relative p-3 pb-5" style="min-height: 120px;">
                        <h6 class="fw-bold mb-1 text-{{ $card['color'] }}" style="font-size: 1.2rem;">{{ $card['value'] }}
                        </h6>
                        <p class="mb-1 text-uppercase small text-dark">{{ $card['title'] }}</p>

                        @isset($card['note'])
                        @php
                            $isPositive = Str::contains($card['note'], '+');
                            $isNegative = Str::contains($card['note'], '-');
                            $noteColor = $isPositive ? 'text-success' : ($isNegative ? 'text-danger' : 'text-muted');
                            $arrow = $isPositive ? '▲' : ($isNegative ? '▼' : '');
                        @endphp
                        <p class="{{ $noteColor }} mb-0 small">
                            <span class="me-1">{{ $arrow }}</span>{{ $card['note'] }}
                        </p>
                        @endisset

                        <div class="icon position-absolute top-0 end-0 pe-3 pt-2 text-{{ $card['color'] }}"
                            style="font-size: 3.5rem; opacity: 0.15;">
                            <i class="fas {{ $card['icon'] }}"></i>
                        </div>
                    </div>

                    {{-- Bagian Bawah (More info) --}}
                    <a href="{{ $card['href'] }}"
                        class="d-flex justify-content-center align-items-center text-decoration-none px-3 py-2 bg-{{ $card['color'] }} {{ $textColorMap[$card['color']] ?? 'text-white' }} mt-auto small-box-footer">
                        More info<i class="fas fa-arrow-circle-right ms-2"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Grafik dan Status --}}
    <div class="row">
        {{-- Grafik Presensi --}}
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm mt-3" style="background-color: var(--bs-body-bg);">
                    <div class="card-header fw-bold">PRESENSI PERBULAN</div>
                    <div class="card-body" style="height: 320px;">
                        <canvas id="grafikPresensi" class="w-100 h-100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm border mt-3" style="background-color: var(--bs-body-bg);">
                    <div class="card-header fw-bold">JADWAL SHIFT HARI INI</div>
                    <div class="card-body" style="height: 255px;">
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
                    <div class="card-footer bg-primary p-2 text-center">
                        <a href="#" class="text-white text-decoration-none fw-medium">
                            More Info <i class="fas fa-arrow-circle-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                {{-- Status Karyawan --}}
                <div class="card shadow-sm mt-3 " style="background-color: var(--bs-body-bg);">
                    <div class="card-header fw-bold">STATUS KARYAWAN</div>
                    <div class="card-body" style="height: 255px;">
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
</script>
@endpush