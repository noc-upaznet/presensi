<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Payroll</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payroll</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('create-slip-gaji') }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Tambah
                </a>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                    data-bs-target="#importPayrollModal">   
                    <i class="fa-solid fa-file-import"></i>
                    Import
                </button>   
            </div>
            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    {{-- Dropdown Tahun --}}
                    <select wire:model="selectedYear" class="form-select me-2" style="width: 100px;">
                        @for ($i = now()->year; $i >= 2020; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>

                    {{-- Dropdown Bulan --}}
                    <select wire:model="selectedMonth" class="form-select me-2" style="width: 150px;">
                        <option value="">Bulan</option>
                        @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Tombol Search --}}
                    <button class="btn btn-light" wire:click="setPeriode">
                        <i class="fa fa-search"></i>
                    </button>
                </div>

                <h5 class="text-secondary mb-3">
                    Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') }}
                </h5>

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

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Gaji</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Bulan</th>
                                <th>Kasbon</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $i => $payroll)
                            <tr>
                                <td>{{ $data->firstItem() + $i }}</td>
                                <td>{{ $payroll->no_gaji }}</td>
                                <td>{{ $payroll->nama }}</td>
                                <td>{{ $payroll->jabatan }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->created_at)->translatedFormat('F') }}</td>
                                <td>Rp. {{ number_format($payroll->kasbon, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($payroll->total, 0, ',', '.') }}</td>
                                <td>
                                    @if ($payroll->status === 'Success')
                                    <span class="badge bg-success"><i
                                            class="fas fa-check-circle me-1"></i>Success</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-sync-alt me-1"></i>On
                                        Process</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info"><i class="fas fa-print"></i></button>
                                    <button wire:click.prevent="confirmHapusPayroll({{ $payroll->id }})"
                                        class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusPayrollModal"><i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <span>
                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                    </span>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Payroll -->
    <div wire:ignore.self class="modal fade" id="hapusPayrollModal" tabindex="-1"
        aria-labelledby="hapusPayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #dc3545; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="hapusPayrollModalLabel">Hapus Data Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data payroll ini?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deletePayroll" style="border-radius: 8px;"
                        data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('dataPayrollTerhapus', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil dihapus.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
    });
</script>