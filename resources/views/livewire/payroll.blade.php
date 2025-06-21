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
                            <h3>{{ $jumlahBelumPunyaSlip }}</h3>
                            <p>Slip Gaji Belum Dibuat</p>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                        <a href="#" wire:click="showModal"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-arrow-right-circle"></i>
                        </a>
                        <livewire:modal-payroll />  
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                @php
                    $month = $selectedMonth ?? now()->subMonth()->format('n');
                    $year = $selectedYear ?? now()->year;
                @endphp
                <a href="{{ route('create-slip-gaji', ['month' => $month, 'year' => $year]) }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Tambah
                </a>
            </div>

            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    {{-- Dropdown Tahun --}}
                    <select wire:model.lazy="selectedYear" class="form-select me-2" style="width: 100px;">
                        @for ($i = now()->year; $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>

                    {{-- Dropdown Bulan --}}
                    <select wire:model.lazy="selectedMonth" class="form-select me-2" style="width: 150px;">
                        <option value="">Bulan</option>
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}">
                                {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <div class="ms-auto">
                        <button type="button" class="btn btn-sm btn-success" wire:click="export">
                            <i class="fas fa-file-export"></i> Export
                        </button>
                    </div>

                </div>

                <h5 class="text-secondary mb-3">
                    Periode: {{ $selectedMonth ? \Carbon\Carbon::createFromFormat('m', $selectedMonth)->locale('id')->translatedFormat('F') . ' ' . $selectedYear : 'Semua Periode' }}
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
                                {{-- <th>No</th> --}}
                                <th>No. Slip</th>
                                <th>Nama</th>
                                <th>NIP Karyawan</th>
                                <th>Departemen</th>
                                <th>Bulan</th>
                                <th>Grand Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($data as $i => $payroll)
                                    <tr>
                                        {{-- <td>{{ $data->firstItem() + $i }}</td> --}}
                                        <td>{{ $payroll->no_slip }}</td>
                                        <td>{{ $payroll->getKaryawan->nama_karyawan }}</td>
                                        <td>{{ $payroll->nip_karyawan }}</td>
                                        <td>{{ $payroll->divisi }}</td>
                                        <td>{{ $payroll->periode }}</td>
                                        <td>Rp. {{ number_format($payroll->total_gaji, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="loadSlipPreview({{ $payroll->id }})"><i class="fa-solid fa-print"></i>
                                            </button>
                                            <button wire:click="editPayroll({{ $payroll->id }})"
                                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmHapusPayroll({{ $payroll->id }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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

    <!-- Modal Edit Payroll-->
    <div wire:ignore.self class="modal fade" id="editPayrollModal" tabindex="-1" aria-labelledby="editPayrollModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="editPayrollModalLabel">Edit Data Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nip" class="form-label fw-semibold">No. Slip</label>
                        <input type="text" class="form-control" id="nip" wire:model="no_slip"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label for="employee" class="form-label fw-semibold">Karyawan</label>
                        <input type="text" class="form-control" id="nip" wire:model="nama_karyawan" 
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label for="month" class="form-label fw-semibold">Bulan & Tahun</label>
                        <input type="month" id="month" class="form-control" wire:model="bulan_tahun">
                    </div>
                    <div class="mb-3">
                        <label for="nip" class="form-label fw-semibold">NPK/NIP</label>
                        <input type="text" class="form-control" id="nip" wire:model="nip_karyawan" 
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="divisi" class="form-label fw-semibold">Divisi</label>
                        <input type="text" class="form-control" id="divisi" wire:model="divisi" 
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                        <input type="number" id="gaji_pokok" class="form-control" wire:model="gaji_pokok"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tunjangan_jabatan" class="form-label fw-semibold">Tunjangan Jabatan</label>
                        <input type="number" id="tunjangan_jabatan" class="form-control" wire:model="tunjangan_jabatan"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="lembur_nominal" class="form-label fw-semibold">Lembur</label>
                        <input type="number" id="lembur_nominal" class="form-control" wire:model="lembur_nominal"
                            disabled>
                    </div>
                    <div class="mb-3">
                        <label for="izin_nominal" class="form-label fw-semibold">Potongan Izin</label>
                        <input type="text" id="izin_nominal" class="form-control" disabled wire:model="izin_nominal">
                    </div>
                    <div class="mb-3">
                        <label for="terlambat_nominal" class="form-label fw-semibold">Potongan Terlambat</label>
                        <input type="text" id="terlambat_nominal" class="form-control" disabled wire:model="terlambat_nominal">
                    </div>
                    <div class="mb-3">
                        <label for="potongan" class="form-label fw-semibold">Tunjangan</label>
                        @foreach ($tunjangan as $index => $item)
                            <div class="input-group mb-2">
                                <select name="tunjangan[]" class="form-select" wire:model="tunjangan.{{ $index }}.nama">
                                    <option value="">-- Pilih Tunjangan --</option>
                                    @if(!empty($jenis_tunjangan) && $jenis_tunjangan->count())
                                        @foreach($jenis_tunjangan as $tunjangan)
                                            @if (!in_array($tunjangan->id, $tunjangan_terpilih ?? []))
                                                <option value="{{ $tunjangan->nama_tunjangan }}">{{ $tunjangan->nama_tunjangan }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="">Data tidak tersedia</option>
                                    @endif
                                </select>
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" wire:model.lazy="tunjangan.{{ $index }}.nominal">
                                <button type="button" class="btn btn-danger" wire:click="removeTunjangan({{ $index }})">Hapus</button>
                            </div>
                        @endforeach
                        <div class="mt-2">
                            <button type="button" class="btn btn-success mb-2" wire:click="addTunjangan">+ Tambah Tunjangan</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="potongan" class="form-label fw-semibold">Potongan</label>
                        @foreach ($potongan as $index => $item)
                            <div class="input-group mb-2">
                                <select name="potongan[]" class="form-select" wire:model="potongan.{{ $index }}.nama">
                                    <option value="">-- Pilih Potongan --</option>
                                    @if(!empty($jenis_potongan) && $jenis_potongan->count())
                                        @foreach($jenis_potongan as $potongan)
                                            @if (!in_array($potongan->id, $potongan_terpilih ?? []))
                                                <option value="{{ $potongan->nama_potongan }}">{{ $potongan->nama_potongan }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="">Data tidak tersedia</option>
                                    @endif
                                </select>
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" wire:model.lazy="potongan.{{ $index }}.nominal">
                                <button type="button" class="btn btn-danger" wire:click="removePotongan({{ $index }})">Hapus</button>
                            </div>
                        @endforeach
                        <div class="mt-2">
                            <button type="button" class="btn btn-success mb-2" wire:click="addPotongan">+ Tambah Potongan</button>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-md-6">
                            <label>Nominal BPJS Kesehatan (1%)</label>
                            <input type="number" class="form-control" wire:model.lazy="bpjs_nominal">
                        </div>

                        <div class="col-md-6">
                            <label>Nominal BPJS JHT (2%)</label>
                            <input type="number" class="form-control" wire:model.lazy="bpjs_jht_nominal">
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="terlambat" class="form-label fw-semibold">Terlambat</label>
                            <input type="number" id="terlambat" class="form-control" wire:model="terlambat">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="izin" class="form-label fw-semibold">Izin</label>
                            <input type="number" id="izin" class="form-control" wire:model="izin">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cuti" class="form-label fw-semibold">Cuti</label>
                            <input type="number" id="cuti" class="form-control" wire:model="cuti">
                        </div>
                    </div> --}}
                    {{-- <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kehadiran" class="form-label fw-semibold">Kehadiran</label>
                            <input type="number" id="kehadiran" class="form-control" wire:model="kehadiran">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lembur" class="form-label fw-semibold">Lembur</label>
                            <input type="number" id="lembur" class="form-control" wire:model="lembur">
                        </div>
                    </div> --}}
                    <div class="mb-3">
                        <label for="terlambat" class="form-label fw-semibold">Total gaji</label>
                        <input type="number" id="terlambat" class="form-control" wire:model="total_gaji" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="saveEdit" class="btn btn-primary"
                            wire:loading.attr="disabled">
                            <div wire:loading class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading>Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Tutup</button>
                    </div>
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

@push('scripts')
    <script>
        Livewire.on('modalPayroll', (event) => {
            $('#modalPayroll').modal(event.action);
        });

        Livewire.on('editPayrollModal', (event) => {
            $('#editPayrollModal').modal(event.action);
        });

        Livewire.on('hapusPayrollModal', (event) => {
            $('#hapusPayrollModal').modal(event.action);
        });

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        function loadSlipPreview(id) {
            const iframe = document.getElementById('slipPreviewIframe');
            iframe.src = `/slip-gaji/html/${id}`;
            document.getElementById('downloadSlipLink').href = `/slip-gaji/download/${id}`;
        }

        // window.addEventListener('dataPayrollAdded', event => {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Berhasil',
        //         text: 'Data payroll berhasil ditambahkan.',
        //         confirmButtonText: 'OK',
        //         confirmButtonColor: '#3085d6',
        //     });
        // });

        // window.addEventListener('dataPayrollImported', event => {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Berhasil',
        //         text: 'Data payroll berhasil diimpor.',
        //         confirmButtonText: 'OK',
        //         confirmButtonColor: '#3085d6',
        //     });
        // });

        // window.addEventListener('dataPayrollExported', event => {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Berhasil',
        //         text: 'Data payroll berhasil diekspor.',
        //         confirmButtonText: 'OK',
        //         confirmButtonColor: '#3085d6',
        //     });
        // });

        // window.addEventListener('dataPayrollUpdated', event => {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Berhasil',
        //         text: 'Data payroll berhasil diperbarui.',
        //         confirmButtonText: 'OK',
        //         confirmButtonColor: '#3085d6',
        //     });
        // });

        window.addEventListener('dataPayrollDeleted', event => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data payroll berhasil dihapus.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            });
        });

        // window.addEventListener('redirect-download', event => {
        //         window.open(event.detail.url, '_blank');
        //     });
    </script>
@endpush