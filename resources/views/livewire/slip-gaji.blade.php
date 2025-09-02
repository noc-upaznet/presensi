<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mt-5">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Slip Gaji</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Slip Gaji</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <label>
                            Show
                            <select wire:model="perPage" class="form-select form-select-sm d-inline-block w-auto mx-1">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                            </select>
                            entries per page
                        </label>
                    </div>
                </div>
                <h5 class="text-secondary mb-3">Data Slip Karyawan</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No. Slip</th>
                                <th>Nama</th>
                                <th>NIP Karyawan</th>
                                <th>Departemen</th>
                                <th>Bulan</th>
                                <th>Grand Total</th>
                                <th>Accepted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="12" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($data as $payroll)
                                    <tr>
                                        <td>{{ $payroll->no_slip }}</td>
                                        <td>{{ $payroll->getKaryawan->nama_karyawan }}</td>
                                        <td>{{ $payroll->nip_karyawan }}</td>
                                        <td>{{ $payroll->divisi }}</td>
                                        <td>{{ $payroll->periode }}</td>
                                        <td>Rp. {{ number_format($payroll->total_gaji, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($payroll->accepted == 1)
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="loadSlipPreview('{{ Crypt::encrypt($payroll->id) }}')"><i class="fa-solid fa-print"></i>
                                            </button>
                                            @if ($payroll->accepted == 0)
                                                <button class="btn btn-success btn-sm mt-1" wire:click="acceptPayroll('{{ $payroll->id }}')"><i class="fa-solid fa-check"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Slip Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="slipPreviewContent">
                <div class="text-center text-muted">
                    <iframe id="slipPreviewIframe" src="" style="width: 100%; height: 90vh; border: none;"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="downloadSlipLink" target="_blank" class="btn btn-primary"><i class="fa-solid fa-print"></i> Download PDF</a>
            </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function loadSlipPreview(id) {
            const iframe = document.getElementById('slipPreviewIframe');
            iframe.src = `/slip-gaji/html/${id}`;
            document.getElementById('downloadSlipLink').href = `/slip-gaji/download/${id}`;
        }

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>
@endpush
