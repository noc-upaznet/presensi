<div>
    <style>
        .planner-table {
            white-space: nowrap;
            font-size: 12px;
            border-collapse: separate;
            border-spacing: 0;
        }

        /* =========================
       BORDER TABLE
    ========================= */

        .planner-table th,
        .planner-table td {
            text-align: center;
            vertical-align: middle;
            padding: 3px;
            border-right: 1px solid #9ca3af;
            border-bottom: 1px solid #9ca3af;
        }

        .planner-table thead th {
            border-top: 1px solid #9ca3af;
        }

        .planner-table th:first-child,
        .planner-table td:first-child {
            border-left: 1px solid #9ca3af;
        }

        /* =========================
       UKURAN CELL
    ========================= */

        .planner-table td {
            min-width: 95px;
        }

        .planner-table select {
            width: 95px;
            min-width: 95px;
            max-width: 95px;
            font-size: 12px;
            padding: 4px 6px;
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 4px;
            box-shadow: none;
        }

        /* =========================
       HEADER STICKY
    ========================= */

        .planner-table thead th {
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* =========================
       DRAG HANDLE
    ========================= */

        .planner-table th:nth-child(1),
        .planner-table td:nth-child(1) {
            position: sticky;
            left: 0;
            width: 40px;
            min-width: 40px;
            background: #fff !important;
            z-index: 130;
            border-right: 2px solid #6b7280;
        }

        /* =========================
       NO
    ========================= */

        .planner-table th:nth-child(2),
        .planner-table td:nth-child(2) {
            position: sticky;
            left: 40px;
            width: 55px;
            min-width: 55px;
            background: #fff !important;
            z-index: 130;
            border-right: 2px solid #6b7280;
        }

        /* =========================
       NAMA
    ========================= */

        .planner-table th:nth-child(3),
        .planner-table td:nth-child(3) {
            position: sticky;
            left: 95px;
            width: 240px;
            min-width: 240px;
            max-width: 240px;
            text-align: left;
            font-weight: 600;
            background: #fff !important;
            z-index: 130;
            border-right: 3px solid #374151;
        }

        /* Header sticky ikut offset */

        .planner-table thead th:nth-child(1) {
            left: 0;
        }

        .planner-table thead th:nth-child(2) {
            left: 40px;
        }

        .planner-table thead th:nth-child(3) {
            left: 95px;
        }

        /* =========================
       WARNA MINGGU
    ========================= */

        .planner-table th.week-1,
        .planner-table td.week-1 {
            background: #7CFC00 !important;
        }

        .planner-table th.week-2,
        .planner-table td.week-2 {
            background: #87CEFA !important;
        }

        .planner-table th.week-3,
        .planner-table td.week-3 {
            background: #FFD54F !important;
        }

        .planner-table th.week-4,
        .planner-table td.week-4 {
            background: #CE93D8 !important;
        }

        .planner-table th.week-5,
        .planner-table td.week-5 {
            background: #FF8A80 !important;
        }

        /* Select mengikuti warna minggu */

        .planner-table td.week-1 select {
            background: #7CFC00;
        }

        .planner-table td.week-2 select {
            background: #87CEFA;
        }

        .planner-table td.week-3 select {
            background: #FFD54F;
        }

        .planner-table td.week-4 select {
            background: #CE93D8;
        }

        .planner-table td.week-5 select {
            background: #FF8A80;
        }

        /* Sticky selalu putih */

        .planner-table th:nth-child(-n+3),
        .planner-table td:nth-child(-n+3) {
            background: #fff !important;
        }

        /* Hover */

        .planner-table tbody tr:hover td {
            filter: brightness(.98);
        }

        /* Divider akhir minggu */

        .planner-table .week-divider {
            border-right: 3px solid #374151 !important;
        }

        /* panduan */
        .transition-icon {
            transition: transform .25s ease;
        }

        .card-header[aria-expanded="true"] .transition-icon {
            transform: rotate(180deg);
        }

        .card-header:hover {
            background: #f8f9fa;
        }
    </style>
    <!-- Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">
                    <h3 class="mb-0">
                        Planner Jadwal
                    </h3>

                    <small class="text-muted">
                        Susun jadwal seluruh karyawan dalam satu halaman
                    </small>
                </div>

            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <!-- FILTER -->

            <div class="card shadow-sm mb-3">

                <div class="card-body">

                    <div class="row g-3 align-items-end">

                        <!-- Bulan -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Bulan</label>
                            <input type="month" class="form-control" wire:model.live="bulan">
                        </div>

                        <!-- Import Excel -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Import Jadwal</label>

                            <div class="input-group">
                                <input type="file" class="form-control" accept=".xlsx,.xls" wire:model="file">

                                <button type="button" class="btn btn-primary" wire:click="importExcel"
                                    wire:loading.attr="disabled" wire:target="file,importExcel">

                                    <span wire:loading.remove wire:target="file,importExcel">
                                        <i class="bi bi-upload me-1"></i>
                                        Import Excel
                                    </span>

                                    <span wire:loading wire:target="file,importExcel">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"
                                            aria-hidden="true"></span>
                                        Memproses...
                                    </span>

                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button class="btn btn-outline-success" wire:click="exportTemplate">

                            <i class="fas fa-file-excel me-1"></i>
                            Download Template

                        </button>
                        {{-- <button class="btn btn-outline-success" wire:click="exportGuide">

                            <i class="fas fa-file-excel me-1"></i>
                            Download Panduan

                        </button> --}}
                    </div>

                </div>

            </div>
            <div class="card shadow-sm mb-3">

                <div class="card-header d-flex justify-content-between align-items-center
                user-select-none"
                    data-bs-toggle="collapse" data-bs-target="#panduanImport" aria-expanded="false"
                    style="cursor:pointer;">

                    <div>
                        <i class="bi bi-info-circle-fill text-info me-2"></i>
                        <strong>Panduan Import Jadwal</strong>
                    </div>

                    <i class="bi bi-chevron-down transition-icon"></i>

                </div>

                <div class="collapse" id="panduanImport">

                    <div class="card-body">

                        <!-- PANDUAN -->
                        <div class="card border-info shadow-sm mb-3">
                            <div class="card-body">

                                <div class="row">

                                    <!-- Panduan -->
                                    <div class="col-lg-7">
                                        <h6 class="fw-bold text-primary mb-3">
                                            Sebelum Melakukan Import
                                        </h6>

                                        <ol class="mb-0 ps-3">
                                            <li>Jangan mengubah nama kolom pada template.</li>
                                            <li>Pastikan <strong>Shift</strong> sudah diatur pada menu <strong>Pembagian
                                                    Shift</strong>.</li>
                                            <li>Urutan karyawan dapat diubah sebelum template diexport.</li>
                                            <li>Urutan karyawan dapat diatur menggunakan <strong>Drag & Drop</strong>.
                                            </li>
                                            <li>Jangan menghapus baris karyawan yang sudah tersedia.</li>
                                            <li>Kolom tanggal hanya boleh diisi dengan kode shift.</li>
                                            <li>Pastikan tidak ada sel yang digabung (<strong>Merge Cell</strong>).</li>
                                            <li>Simpan file dalam format <strong>.xlsx</strong>.</li>
                                            <li>Sebelum import pastikan <strong>filter setting bulan</strong> sudah
                                                diatur ke bulan yang ingin diimport.</li>
                                            <li>Apabila format tidak sesuai maka proses import akan gagal.</li>
                                        </ol>
                                    </div>

                                    <!-- Format Kode -->
                                    <div class="col-lg-5 border-start">

                                        <h6 class="fw-bold text-success mb-3">
                                            Contoh Format Kode Jam Kerja
                                        </h6>

                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered align-middle mb-3">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nama Shift/Jam Kerja</th>
                                                        <th width="120">Kode</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>08.00-16.00</td>
                                                        <td><code>0816</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>07.30-12.30</td>
                                                        <td><code>07301230</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>13.00-21.00</td>
                                                        <td><code>1321</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>20.00-08.00</td>
                                                        <td><code>2008</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td>LIBUR</td>
                                                        <td><code>LIB</code></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="alert alert-warning mb-0 py-2">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            Semua kode jam ditulis <strong>tanpa titik (.)</strong>,
                                            <strong>tanpa tanda minus (-)</strong>,
                                            <strong>tanpa spasi</strong>,
                                            dan <strong>tanpa tanda kutip</strong>.
                                            <br><br>
                                            Jika mengalami kendala saat import, silakan hubungi
                                            <strong>Administrator</strong>.
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- GRID -->

            <div class="card shadow-sm">

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-bordered planner-table mb-0">

                            <thead>
                                <tr>
                                    <th width="40">Drag & Drop untuk Urut</th>
                                    <th style="width:55px">No</th>

                                    <th style="width:240px" class="text-center">
                                        Nama Karyawan
                                    </th>

                                    @foreach ($calendar as $tanggal)
                                        <th
                                            class="week-{{ $tanggal['week'] }} {{ $tanggal['is_divider'] ? 'week-divider' : '' }}">
                                            {{ $tanggal['day'] }}
                                        </th>
                                    @endforeach
                                </tr>

                                <tr>

                                    <!-- supaya tidak maju -->
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    @foreach ($calendar as $tanggal)
                                        <th
                                            class="week-{{ $tanggal['week'] }} {{ $tanggal['is_divider'] ? 'week-divider' : '' }}">
                                            {{ $tanggal['date']->locale('id')->translatedFormat('D') }}
                                        </th>
                                    @endforeach

                                </tr>

                            </thead>

                            <tbody id="planner-body" class="text-center">

                                @forelse($karyawan as $index => $item)

                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            <i class="fas fa-grip-vertical text-secondary drag-handle"
                                                style="cursor:move;"></i>
                                        </td>
                                        <td>{{ $index + 1 }}</td>

                                        <td>

                                            <strong>{{ $item->nama_karyawan }}</strong>

                                        </td>

                                        @foreach ($calendar as $tanggal)
                                            <td
                                                class="p-1 week-{{ $tanggal['week'] }} {{ $tanggal['is_divider'] ? 'week-divider' : '' }}">

                                                <select class="form-select form-select-sm"
                                                    wire:change="updateShift(
                                                        {{ $item->id }},
                                                        '{{ $tanggal['ym'] }}',
                                                        '{{ $tanggal['column'] }}',
                                                        $event.target.value
                                                    )">

                                                    <option value="">-</option>

                                                    @foreach ($shift as $s)
                                                        <option value="{{ $s->id }}"
                                                            @selected(($planner[$item->id][$tanggal['column']] ?? null) == $s->id)>
                                                            {{ $s->nama_shift }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                            </td>
                                        @endforeach

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="{{ count($calendar) + 2 }}" class="text-center py-5">

                                            Tidak ada data karyawan.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
    @script
        <script>
            Livewire.on('swal', (e) => {
                Swal.fire(e.params);
            });

            const sortable = new Sortable(
                document.getElementById('planner-body'), {

                    animation: 150,

                    handle: '.drag-handle',

                    ghostClass: 'table-warning',

                    onEnd() {

                        let order = [];

                        document.querySelectorAll('#planner-body tr').forEach(function(row, index) {

                            order.push({

                                id: row.dataset.id,

                                order: index + 1

                            });

                        });

                        $wire.updateOrder(order);

                    }

                }
            );

            document.addEventListener('DOMContentLoaded', () => {
                const collapse = document.getElementById('panduanImport');
                const header = collapse.previousElementSibling;

                collapse.addEventListener('show.bs.collapse', () => {
                    header.setAttribute('aria-expanded', 'true');
                });

                collapse.addEventListener('hide.bs.collapse', () => {
                    header.setAttribute('aria-expanded', 'false');
                });
            });
        </script>
    @endscript
