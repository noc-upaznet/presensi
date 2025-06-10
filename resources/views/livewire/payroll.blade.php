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
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner pe-5">
                            <!-- Tambahkan padding end/right -->
                            <h3>22</h3>
                            <p>Slip Gaji Belum Dibuat</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                        <a href="#" wire:click="$dispatch('openSlipGajiModal')"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-arrow-right-circle"></i>
                        </a>
                        <livewire:salary-slip.slip-gaji-belum-dibuat />
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('create-slip-gaji') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Tambah
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#importPayrollModal">
                    <i class="fa-solid fa-file-excel"></i>
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
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F')
                            }}
                        </option>
                        @endforeach
                    </select>

                    {{-- Tombol Search --}}
                    <button class="btn btn-light me-2" wire:click="setPeriode">
                        <i class="fa fa-search"></i>
                    </button>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#exportPayrollModal">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>
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
                                <th>Departemen</th>
                                <th>Bulan</th>
                                {{-- <th>Kasbon</th> --}}
                                <th>Grand Total</th>
                                {{-- <th>Status</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $i => $payroll)
                            <tr>
                                <td>{{ $data->firstItem() + $i }}</td>
                                <td>{{ $payroll->no_gaji }}</td>
                                <td>{{ $payroll->nama }}</td>
                                <td>{{ $payroll->divisi }}</td>
                                <td>{{ \Carbon\Carbon::parse($payroll->created_at)->translatedFormat('F') }}
                                </td>
                                {{-- <td>Rp. {{ number_format($payroll->kasbon, 0, ',', '.') }}</td> --}}
                                <td>Rp. {{ number_format($payroll->total, 0, ',', '.') }}</td>
                                {{-- <td>
                                    @if ($payroll->status === 'Success')
                                    <span class="badge bg-success"><i
                                            class="fas fa-check-circle me-1"></i>Success</span>
                                    @else
                                    <span class="badge bg-secondary"><i class="fas fa-sync-alt me-1"></i>On
                                        Process</span>
                                    @endif
                                </td> --}}
                                <td>
                                    <button wire:click="downloadSlip({{ $payroll->id }})" class="btn btn-sm btn-info">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button wire:click.prevent="editPayroll({{ $payroll->id }})"
                                        class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editPayrollModal"><i class="fas fa-edit"></i>
                                    </button>
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
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>


    <!-- Modal Import Payroll -->
    <div wire:ignore.self class="modal fade" id="importPayrollModal" tabindex="-1"
        aria-labelledby="importPayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="importPayrollModalLabel">Import Data
                        Payroll
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="importExcel">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="file" wire:model="file"
                                accept=".xlsx, .xls, .csv">
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Format file harus .xlsx, .xls, atau .csv. Pastikan data
                                sesuai
                                dengan format yang telah ditentukan.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Export Payroll -->
    <div wire:ignore.self class="modal fade" id="exportPayrollModal" tabindex="-1"
        aria-labelledby="exportPayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="exportPayrollModalLabel">Export Data
                        Payroll
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Silakan pilih rentang waktu untuk diekspor:</p>

                    <div class="mb-3">
                        <label for="startDate" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="startDate" wire:model="startDate" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="endDate" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="endDate" wire:model="endDate" class="form-control">
                    </div>

                    {{-- <div class="d-flex justify-content-center">
                        <button class="btn btn-outline-success me-2" wire:click="exportExcel">
                            <i class="fa-solid fa-file-excel"></i> Excel
                        </button>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-success" wire:click="exportExcel">
                        <i class="fa-solid fa-file-excel"></i> Export
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Payroll-->
    <div wire:ignore.self class="modal fade" id="editPayrollModal" tabindex="-1" aria-labelledby="editPayrollModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="editPayrollModalLabel">Edit Data Payroll
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="employee" class="form-label fw-semibold">Karyawan</label>
                            <select id="employee" class="form-select" wire:model="employee_id">
                                <option value="">Pilih Karyawan</option>
                                @if(isset($employees) && is_iterable($employees))
                                @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="month" class="form-label fw-semibold">Bulan & Tahun</label>
                            <input type="month" id="month" class="form-control" wire:model="bulan_tahun">
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label fw-semibold">NPK/NIP</label>
                            <input type="text" class="form-control" id="nip" wire:model="nip" placeholder="12345678"
                                disabled>
                        </div>
                        <div class="mb-3">
                            <label for="departemen" class="form-label fw-semibold">Departemen</label>
                            <div class="input-group">
                                <select class="form-select" id="departemen" wire:model="departemen_id" disabled>
                                    <option value="">Finance</option>
                                    @if(isset($departemens) && is_iterable($departemens))
                                    @foreach($departemens as $departemen)
                                    <option value="{{ $departemen->id }}">{{ $departemen->nama }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                            <input type="number" id="gaji_pokok" class="form-control" wire:model="gaji_pokok"
                                placeholder="1000000" disabled>
                        </div>
                        <div class="mb-3 mt-4">
                            <label class="form-label fw-semibold">Tunjangan</label>

                            <div class="input-group mb-2 tunjangan-item">
                                <div class="row w-100">
                                    <div class="col-md-6">
                                        <select class="form-select">
                                            <option value="">Voucher</option>
                                            @if(isset($jenis_tunjangan) && is_iterable($jenis_tunjangan))
                                            @foreach($jenis_tunjangan as $tunj)
                                            <option value="{{ $tunj->id }}">{{ $tunj->nama_tunjangan }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text" style="pointer-events: none;">Rp</span>
                                            <input type="number" class="form-control" placeholder="100000">
                                        </div>
                                        <small class="text-muted">Contoh: 500000</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Potongan</label>
                            <div id="potongan-list">
                                <div class="input-group mb-2 potongan-item">
                                    <div class="row w-100">
                                        <div class="col-md-6">
                                            <select class="form-select">
                                                <option value="">Terlambat</option>
                                                @if(isset($jenis_potongan) && is_iterable($jenis_potongan))
                                                @foreach($jenis_potongan as $pot)
                                                <option value="{{ $pot->id }}">{{ $pot->nama_potongan }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text" style="pointer-events: none;">Rp</span>
                                                <input type="number" class="form-control" placeholder="52000"
                                                    wire:model="potongan">
                                            </div>
                                            <small class="text-muted">Contoh: 500000</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="terlambat" class="form-label fw-semibold">Terlambat</label>
                                <input type="number" id="terlambat" class="form-control" wire:model="terlambat"
                                    placeholder="Contoh: 2">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="izin" class="form-label fw-semibold">Izin</label>
                                <input type="number" id="izin" class="form-control" wire:model="izin"
                                    placeholder="Contoh: 1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="cuti" class="form-label fw-semibold">Cuti</label>
                                <input type="number" id="cuti" class="form-control" wire:model="cuti"
                                    placeholder="Contoh: 0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="kehadiran" class="form-label fw-semibold">Kehadiran</label>
                                <input type="number" id="kehadiran" class="form-control" wire:model="kehadiran"
                                    placeholder="Contoh: 25">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lembur" class="form-label fw-semibold">Lembur</label>
                                <input type="number" id="lembur" class="form-control" wire:model="lembur"
                                    placeholder="Contoh: 0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                style="border-radius: 8px;">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                style="border-radius: 8px;">Tutup</button>
                        </div>
                    </form>
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
                    <h5 class="modal-title fw-bold text-danger" id="hapusPayrollModalLabel">Hapus Data Payroll
                    </h5>
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
    window.addEventListener('dataPayrollAdded', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil ditambahkan.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editPayrollModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('dataPayrollImported', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil diimpor.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('importPayrollModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('dataPayrollExported', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil diekspor.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('exportPayrollModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('dataPayrollUpdated', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil diperbarui.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editPayrollModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('dataPayrollDeleted', event => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data payroll berhasil dihapus.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6',
        });
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('hapusPayrollModal'));
        if (modal) modal.hide();
    });

    window.addEventListener('redirect-download', event => {
            window.open(event.detail.url, '_blank');
        });
</script>