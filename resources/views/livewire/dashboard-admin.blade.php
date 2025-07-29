<div class="container-fluid">
    {{-- Summary Cards --}}
    {{-- <div class="row g-3 mb-3">
        @php
        $cards = [
        ['title' => 'TOTAL PEGAWAI', 'value' => '104', 'icon' => 'fa-users', 'color' => 'warning'],
        ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp75.985.069', 'icon' => 'fa-money-bill-wave', 'color' =>
        'info', 'note' => '-5% dari bulan sebelumnya'],
        ['title' => 'IZIN/CUTI', 'value' => '6', 'icon' => 'fa-calendar-times', 'color' => 'danger'],
        ['title' => 'MASUK', 'value' => '98', 'icon' => 'fa-user-check', 'color' => 'success'],
        ['title' => 'BPJS Ketenagakerjaan', 'value' => 'Rp9.189.000', 'icon' => 'fa-hospital-user', 'color' => 'info',
        'note' => '+8% dari bulan sebelumnya'],
        ['title' => 'JHT', 'value' => 'Rp5.250.000', 'icon' => 'fa-briefcase', 'color' => 'primary',
        'note' => '+5% dari bulan sebelumnya'],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="col-md-6 col-xl-3">
            <div class="card shadow-sm h-100 d-flex flex-column">
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
                    @php
                    $isPositive = \Illuminate\Support\Str::contains($card['note'], '+');
                    $isNegative = \Illuminate\Support\Str::contains($card['note'], '-');
                    $noteColor = $isPositive ? 'text-success' : ($isNegative ? 'text-danger' : 'text-muted');
                    $arrow = $isPositive ? '▲' : ($isNegative ? '▼' : '');
                    @endphp
                    <p class="{{ $noteColor }} small mb-0">
                        <span class="me-1">{{ $arrow }}</span>{{ $card['note'] }}
                    </p>
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

    </div> --}}

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-3">
        @php
        use Illuminate\Support\Str;
        $cards = [
        ['title' => 'TOTAL PEGAWAI', 'value' => '104', 'icon' => 'fa-users', 'color' => 'warning'],
        ['title' => 'TOTAL GAJI KARYAWAN', 'value' => 'Rp75.985.069', 'icon' => 'fa-money-bill-wave', 'color' => 'info',
        'note' => '-5% dari bulan sebelumnya'],
        ['title' => 'IZIN/CUTI', 'value' => '6', 'icon' => 'fa-calendar-times', 'color' => 'danger'],
        ['title' => 'MASUK', 'value' => '98', 'icon' => 'fa-user-check', 'color' => 'success'],
        ['title' => 'BPJS Ketenagakerjaan', 'value' => 'Rp9.189.000', 'icon' => 'fa-hospital-user', 'color' => 'info',
        'note' => '+8% dari bulan sebelumnya'],
        ['title' => 'JHT', 'value' => 'Rp5.250.000', 'icon' => 'fa-briefcase', 'color' => 'primary', 'note' => '+5% dari
        bulan sebelumnya'],
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
                <a href="#"
                    class="d-flex justify-content-center align-items-center text-decoration-none px-3 py-2 bg-{{ $card['color'] }} {{ $textColorMap[$card['color']] ?? 'text-white' }} mt-auto small-box-footer">
                    More info <i class="fas fa-arrow-circle-right ms-2"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Grafik dan Status --}}
    <div class="row g-3 mt-3">
        {{-- PRESENSI PERBULAN --}}
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-80">
                <div class="card-header bg-white fw-bold">PRESENSI PERBULAN</div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="grafikPresensi" class="w-80 h-80"></canvas>
                </div>
            </div>
        </div>

        {{-- JADWAL SHIFT HARI INI --}}
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-primary border h-80">
                <div class="card-header bg-white fw-bold text-primary" style="font-size: 1rem;">JADWAL SHIFT HARI INI
                </div>
                <div class="card-body py-3">
                    <h6 class="text-primary text-center mb-3">Shift Helpdesk</h6>
                    <ul class="list-unstyled small mb-4">
                        <li class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-clock me-1 text-muted"></i>Shift Pagi</span>
                            <span class="fw-medium">07:00 - 15:00</span>
                        </li>
                        <li class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-clock me-1 text-muted"></i>Shift Siang</span>
                            <span class="fw-medium">15:00 - 23:00</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span><i class="fas fa-clock me-1 text-muted"></i>Shift Malam</span>
                            <span class="fw-medium">23:00 - 07:00</span>
                        </li>
                    </ul>
                    <h6 class="text-primary text-center mb-3">Shift Non Helpdesk</h6>
                    <ul class="list-unstyled small">
                        <li class="d-flex justify-content-between mb-1">
                            <span><i class="fas fa-clock me-1 text-muted"></i>Senin–Jumat</span>
                            <span class="fw-medium">08:00 - 16:00</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span><i class="fas fa-clock me-1 text-muted"></i>Sabtu</span>
                            <span class="fw-medium">08:00 - 13:00</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-primary p-2 text-center">
                    <a href="#" class="text-white text-decoration-none fw-medium">
                        More Info <i class="fas fa-arrow-circle-right ms-2"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- STATUS KARYAWAN --}}
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-info border h-100">
                <div class="card-header bg-white fw-bold text-info" style="font-size: 1rem;">STATUS KARYAWAN</div>
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
                    <ul class="list-unstyled small mb-0">
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-primary me-2"></i> Kontrak (91 – 87.5%)
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-warning me-2"></i> Probation (9 – 8.65%)
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-square text-danger me-2"></i> OJT (4 – 3.85%)
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-info p-2 text-center">
                    <a href="#" class="text-white text-decoration-none fw-medium">
                        More Info <i class="fas fa-arrow-circle-right ms-2"></i>
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