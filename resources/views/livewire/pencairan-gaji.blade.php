<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Pencairan Gaji Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pencairan Gaji Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm p-3 mb-5 bg-white rounded">
            <div class="container-fluid">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="periode" class="me-2 text-secondary">Periode:</label>
                        <input type="text" id="periode" class="form-control form-control-sm" wire:model="exportPeriod"
                            placeholder="Pilih Periode" style="width: 200px;">
                    </div>

                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPencairanGaji">
                        <i class="fa-solid fa-file-excel"></i> Export
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>Show <select class="form-select form-select-sm d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select> entries per page</label>
                    </div>
                    <div>
                        <input type="search" class="form-control form-control-sm" placeholder="Search...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Karyawan</th>
                                    <th>Pendapatan</th>
                                    <th>Tunjangan</th>
                                    <th>Bonus</th>
                                    <th>Potongan</th>
                                    <th>Upah Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pencairanGaji as $payroll)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="./assets/img/user4-128x128.jpg" {{ strtolower(str_replace(' ', '
                                                _', $payroll->nama_karyawan)) }}.png
                                            alt="{{ $payroll->nama_karyawan }}" class="avatar">
                                            <div class="ms-3">
                                                <strong>{{ $payroll->nama_karyawan }}</strong><br>
                                                <small class="text-muted">{{ $payroll->jabatan ?? 'Tidak ada jabatan'
                                                    }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($payroll->pendapatan) }}</td>
                                    <td>{{ number_format($payroll->tunjangan) }}</td>
                                    <td>{{ number_format($payroll->bonus) }}</td>
                                    <td>{{ number_format($payroll->potongan) }}</td>
                                    <td>{{ number_format($payroll->total_gaji) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center bg-white p-3">
                    <span>Showing {{ $pencairanGaji->firstItem() }} to {{ $pencairanGaji->lastItem() }} of
                        {{ $pencairanGaji->total() }} entries</span>
                    {{ $pencairanGaji->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPencairanGaji" tabindex="-1" aria-labelledby="modalPencairanGajiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPencairanGajiLabel">Export Pencairan Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="export">
                        <div class="mb-3">
                            <label for="exportFormat" class="form-label">Format Export</label>
                            <select id="exportFormat" class="form-select" wire:model="exportFormat">
                                <option value="excel">Excel</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exportPeriod" class="form-label">Periode</label>
                            <input type="text" id="exportPeriod" class="form-control" wire:model="exportPeriod"
                                placeholder="YYYY-MM" onchange="this.dispatchEvent(new InputEvent('input'))">
                        </div>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('exportSuccess', function () {
            alert('Export successful!');
        });
    });
    
    document.addEventListener("DOMContentLoaded", function() {
        $('#exportPeriod').datepicker({
            format: "yyyy-mm",
            minViewMode: 1,
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        $('#periode').datepicker({
            format: "MM yyyy",
            minViewMode: 1,
            autoclose: true,
            todayHighlight: true,
            clearBtn: true
        }).on('changeDate', function(e) {
            // Trigger Livewire event
            this.dispatchEvent(new InputEvent('input'));
        });
    });

</script>