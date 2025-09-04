<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 mt-5">
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
                            <span>Slip Gaji Belum Dibuat</span><br>
                            <span><b>Entitas {{ $currentEntitas }}</b></span>
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
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner pe-5">
                            <!-- Tambahkan padding end/right -->
                            <h3>{{ $jumlahBelumPunyaSlipTitip }}</h3>
                            <span>Slip Gaji Belum Dibuat</span><br>
                            <span><b>Entitas Lainnya</b></span>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                        <a href="#" wire:click="showModalEks"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            More info <i class="bi bi-arrow-right-circle"></i>
                        </a>
                        <livewire:modal-payroll />  
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-info">
                        <div class="inner pe-5">
                            <!-- Tambahkan padding end/right -->
                            <h3>{{ $JumlahKaryawanInternal }}</h3>
                            <span>Jumlah Slip Gaji</span><br>
                            <span><b>Karyawan {{ $currentEntitas }} </b></span>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                        <span
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            -
                        </span>
                        <livewire:modal-payroll />  
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-info">
                        <div class="inner pe-5">
                            <!-- Tambahkan padding end/right -->
                            <h3>{{ $JumlahKaryawanTitip }}</h3>
                            <span>Jumlah Slip Gaji</span><br>
                            <span><b>Karyawan Titip </b></span>
                        </div>
                        <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
                            </path>
                        </svg>
                        <span
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                            -
                        </span>
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

                <button wire:click="createSlipGaji({{ $month }}, {{ $year }})" 
                        class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
            </div>

            <div class="card-body">
                <div class="d-flex mb-3 align-items-center">
                    {{-- Dropdown Tahun --}}
                    <select wire:model.lazy="selectedYear" class="form-select me-2" style="width: 100px;">
                        @for ($i = now()->year; $i >= 2023; $i--)
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

                    <select wire:model.lazy="selectedKaryawan" class="form-select me-2" style="width: 150px;">
                        <option value="">Karyawan</option>
                        @foreach ($karyawanList as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_karyawan }}</option>
                        @endforeach
                    </select>

                    <select wire:model.lazy="selectedStatus" class="form-select me-2" style="width: 150px;">
                        <option value="">Status</option>
                        <option value="0">Pending</option>
                        <option value="1">Accepted</option>
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
                    <label>
                        Show
                        <select wire:model="perPage" class="form-select form-select-sm d-inline-block w-auto mx-1">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                        entries per page
                    </label>
                    <button class="btn btn-sm btn-primary" wire:click="publishAll">
                        <i class="fas fa-square-check"></i> Publish All
                    </button>
                </div>
                <h5 class="text-secondary mb-3">Data Slip Karyawan {{ $currentEntitas }}</h5>
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
                                <th>Status Titip</th>
                                <th>Published</th>
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
                                            <div class="form-check form-switch d-inline-block">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    id="switchTitip{{ $payroll->id }}"
                                                    wire:change="toggleTitip({{ $payroll->id }})"
                                                    @if($payroll->titip) checked @endif>
                                                <label class="form-check-label" for="switchTitip{{ $payroll->id }}">
                                                    {{ $payroll->titip ? 'Titip' : $currentEntitas }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($payroll->published == 0)
                                                <button wire:click="publishPayroll({{ $payroll->id }})" class="btn btn-primary btn-sm">Publish</button>
                                            @elseif ($payroll->published == 1)
                                                <span class="badge bg-success">Published</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($payroll->accepted == 1)
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="loadSlipPreview('{{ Crypt::encrypt($payroll->id) }}')"><i class="fa-solid fa-print"></i>
                                            </button>
                                            <button 
                                                wire:click="editPayroll('{{ encrypt($payroll->id) }}')" 
                                                class="btn btn-warning btn-sm" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Edit">
                                                    <i class="fas fa-edit"></i>
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

                <h5 class="text-secondary mb-3">Data Slip Karyawan Titip</h5>
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
                                <th>Status Titip</th>
                                <th>Published</th>
                                <th>Accepted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data2->isEmpty())
                                <tr>
                                    <td colspan="12" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($data2 as $key)
                                    <tr>
                                        <td>{{ $key->no_slip }}</td>
                                        <td>{{ $key->getKaryawan->nama_karyawan }}</td>
                                        <td>{{ $key->nip_karyawan }}</td>
                                        <td>{{ $key->divisi }}</td>
                                        <td>{{ $key->periode }}</td>
                                        <td>Rp. {{ number_format($key->total_gaji, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="form-check form-switch d-inline-block">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    id="switchTitip{{ $key->id }}"
                                                    wire:change="toggleTitip({{ $key->id }})"
                                                    @if($key->titip) checked @endif>
                                                <label class="form-check-label" for="switchTitip{{ $key->id }}">
                                                    {{ $key->titip ? 'Titip' : $currentEntitas }}
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($key->published == 0)
                                                <button wire:click="publishPayroll({{ $key->id }})" class="btn btn-primary btn-sm">Publish</button>
                                            @elseif ($key->published == 1)
                                                <span class="badge bg-success">Published</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($key->accepted == 1)
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="loadSlipPreview('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-print"></i>
                                            </button>
                                            <button 
                                                wire:click="editPayroll('{{ encrypt($key->id) }}')" 
                                                class="btn btn-warning btn-sm" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Edit">
                                                    <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="confirmHapusPayroll({{ $key->id }})"
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-primary" id="editPayrollModalLabel">Edit Data Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label fw-semibold">No. Slip</label>
                            <input type="text" class="form-control" id="nip" wire:model="no_slip"
                                disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="employee" class="form-label fw-semibold">Karyawan</label>
                            <input type="text" class="form-control" id="nip" wire:model="nama_karyawan" 
                                disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label fw-semibold">Bulan & Tahun</label>
                            <input type="month" id="month" class="form-control" wire:model="bulan_tahun">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label fw-semibold">NPK/NIP</label>
                            <input type="text" class="form-control" id="nip" wire:model="nip_karyawan" 
                                disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="divisi" class="form-label fw-semibold">Divisi</label>
                            <input type="text" class="form-control" id="divisi" wire:model="divisi" 
                                disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                            <input type="number" id="gaji_pokok" class="form-control" wire:model="gaji_pokok"
                                disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tunjangan_jabatan" class="form-label fw-semibold">Tunjangan Jabatan</label>
                            <input type="number" id="tunjangan_jabatan" class="form-control" wire:model="tunjangan_jabatan"
                                disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lembur_nominal" class="form-label fw-semibold">Lembur</label>
                            <input type="number" id="lembur_nominal" class="form-control" wire:model="lembur_nominal"
                                disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="inovation_reward" class="form-label fw-semibold">Uang Transport</label>
                            <input type="number" name="transport" class="form-control" wire:model.lazy="transport" placeholder="Nominal">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="potongan" class="form-label fw-semibold">Uang Makan</label>
                            <input type="number" name="uang_makan" class="form-control" wire:model.lazy="uang_makan" placeholder="Nominal">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="potongan" class="form-label fw-semibold">Tunjangan Kebudayaan</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <select name="kebudayaan" class="form-select" wire:model.lazy="kebudayaan">
                                    <option value="">-- Pilih Nominal --</option>
                                    <option value="100000">100000</option>
                                    <option value="200000">200000</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="potongan" class="form-label fw-semibold">Bonus Fee Sharing</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <select name="fee_sharing" class="form-select" wire:model.lazy="fee_sharing">
                                    <option value="">-- Pilih Nominal --</option>
                                    <option value="100000">100000</option>
                                    <option value="200000">200000</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="potongan" class="form-label fw-semibold">Uang Transport</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <select name="transport" class="form-select" wire:model.lazy="transport">
                                    <option value="">-- Pilih Nominal --</option>
                                    <option value="5000">5.000</option>
                                    <option value="10000">10.000</option>
                                    <option value="15000">15.000</option>
                                    <option value="25000">25.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="potongan" class="form-label fw-semibold">Uang Makan</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <select name="uang_makan" class="form-select" wire:model.lazy="uang_makan">
                                    <option value="">-- Pilih Nominal --</option>
                                    <option value="5000">5.000</option>
                                    <option value="10000">10.000</option>
                                    <option value="15000">15.000</option>
                                    <option value="25000">25.000</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="izin_nominal" class="form-label fw-semibold">Potongan Izin</label>
                            <input type="text" id="izin_nominal" class="form-control" disabled wire:model="izin_nominal">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="terlambat_nominal" class="form-label fw-semibold">Potongan Terlambat</label>
                            <input type="text" id="terlambat_nominal" class="form-control" disabled wire:model="terlambat_nominal">
                        </div>
                    </div>
                    {{-- <div class="row mt-2 mb-2">
                        <div class="col-md-6">
                            <label>Nominal BPJS Kesehatan (1%)</label>
                            <input type="number" class="form-control" readonly wire:model.lazy="bpjs_nominal">
                        </div>

                        <div class="col-md-6">
                            <label>Nominal BPJS JHT (2%)</label>
                            <input type="number" class="form-control" readonly wire:model.lazy="bpjs_jht_nominal">
                        </div>
                    </div> --}}
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
                            wire:loading.attr="disabled" wire:target="saveEdit">
                            <div wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveEdit">
                                <i class="fa fa-save"></i> Simpan
                            </span>
                            <span wire:loading wire:target="saveEdit">Loading...</span>
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

        Livewire.on('modalPayrollEks', (event) => {
            $('#modalPayrollEks').modal(event.action);
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
            console.log(id);
            const iframe = document.getElementById('slipPreviewIframe');
            iframe.src = `/slip-gaji/html/${id}`;
            document.getElementById('downloadSlipLink').href = `/slip-gaji/download/${id}`;
        }

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