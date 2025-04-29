<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Pengajuan Lembur</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pengajuan Lembur</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="mb-4">
                <div class="card-header mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLembur">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>

                <div class="p-0 table-responsive">
                    <table class="table table-striped" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr class="users-table-info">
                                <th>Tanggal</th>
                                <th>Mulai</th>
                                <th>Selesai</th>
                                <th>Kegiatan</th>
                                <th>Dokumentasi</th>
                                <th>Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">22-04-2025</td>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">15.00</td>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">22.00</td>
                                <td style="color: var(--bs-body-color);">Layanan Helpdesk Menggantikan Dimas sakit</td>
                                <td style="color: var(--bs-body-color);">
                                    {{-- <img src="./assets/img/categories/03.jpg" alt="File" class="img-fluid rounded"
                                        style="max-width: 70px;"> --}}
                                </td>
                                <td>
                                    <span class="badge bg-success">SPV</span>
                                    <span class="badge bg-warning">HR</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetailPengajuanLembur1">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">18-04-2025</td>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">16.10</td>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">18.10</td>
                                <td style="color: var(--bs-body-color);">Update & input data pelanggan bulan ini</td>
                                <td style="color: var(--bs-body-color);">
                                    <img src="./assets/img/categories/03.jpg" alt="File" class="img-fluid rounded"
                                        style="max-width: 70px;">
                                </td>
                                <td>
                                    <span class="badge bg-success">SPV</span>
                                    <span class="badge bg-success">HR</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetailPengajuanLembur2">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Pengajuan Lembur -->
    <div class="modal fade" id="modalTambahLembur" tabindex="-1" aria-labelledby="modalTambahLemburLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                            <input type="text" class="form-control" id="tanggal" placeholder="17 Maret 2025">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rentang Waktu</label>
                            <div class="row">
                                <div class="col">
                                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                    <input type="time" id="jam_mulai" class="form-control" wire:model="jam_mulai">
                                </div>
                                <div class="col">
                                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                    <input type="time" id="jam_selesai" class="form-control" wire:model="jam_selesai">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" placeholder="Layanan Helpdesk">
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">File (Opsional)</label>
                            <input type="file" class="form-control" id="file">
                            <div class="form-text">Max file size: 5MB</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 w-md-auto">Request</button>
                        <button type="button" class="btn btn-secondary w-100 w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengajuan Lembur Pending -->
    <div class="modal fade" id="modalDetailPengajuanLembur1" tabindex="-1"
        aria-labelledby="modalDetailPengajuanLabelLembur1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-3 py-4" style="max-height: 90vh; overflow-y: auto;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./assets/img/user4-128x128.jpg" class="img-fluid rounded-circle" width="80"
                            height="80">
                        <h5 class="mt-3 mb-0 fw-bold">Nadia Safira Khairunnisa</h5>
                        <small class="text-muted">Billing</small>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10">
                            <div class="row mb-3 align-items-center">
                                <div class="col fw-bold">Informasi Permohonan</div>
                                <div class="col-auto">
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12 col-sm-5 text-muted">Waktu Lembur</div>
                                <div class="col-12 col-sm-7 fw-semibold">9 April 2025 Pukul 16.15 - 22.00 WIB</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Deskripsi</div>
                                <div class="col-7 fw-semibold">Layanan Helpdesk Menggantikan Dimas sakit</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-5 text-muted">Lampiran Gambar</div>
                                <div class="col-7 fw-semibold">
                                    <picture>
                                        <picture>
                                            {{-- <img src="./assets/img/categories/03.jpg" alt="category"
                                                class="img-fluid rounded"> --}}
                                        </picture>
                                    </picture>
                                </div>
                            </div>

                            <div class="timeline-container small">
                                <div class="position-relative ps-3 ms-1 mb-4">
                                    <div class="timeline-dot bg-success"></div>
                                    <div class="fw-semibold">
                                        <span class="text-success">Disetujui</span> Oleh SPV Finance
                                    </div>
                                    <div class="text-muted ms-3 small">13 Maret 2025 11:20:31 WIB</div>
                                    <div class="timeline-line"></div>
                                </div>

                                <div class="position-relative ps-3 ms-1 mb-4">
                                    <div class="timeline-dot bg-warning"></div>
                                    <div class="fw-semibold">Menunggu Persetujuan dari HR</div>
                                    <div class="timeline-line1"></div>
                                </div>

                                <div class="position-relative ps-3 ms-1">
                                    <div class="timeline-dot bg-secondary"></div>
                                    <div class="text-muted">Requested</div>
                                    <div class="text-muted ms-3 small">13 Maret 2025 07:20:31 WIB</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary w-100 w-md-auto"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengajuan Lembur Sukses -->
    <div class="modal fade" id="modalDetailPengajuanLembur2" tabindex="-1"
        aria-labelledby="modalDetailPengajuanLabelLembur2" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-3 py-4" style="max-height: 90vh; overflow-y: auto;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./assets/img/user4-128x128.jpg" class="img-fluid rounded-circle" width="80"
                            height="80">
                        <h5 class="mt-3 mb-0 fw-bold">Nadia Safira Khairunnisa</h5>
                        <small class="text-muted">Billing</small>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10">
                            <div class="row mb-3 align-items-center">
                                <div class="col fw-bold">Informasi Permohonan</div>
                                <div class="col-auto">
                                    <span class="badge bg-success">Approved</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-12 col-sm-5 text-muted">Waktu Lembur</div>
                                <div class="col-12 col-sm-7 fw-semibold">6 April 2025 Pukul 16.10 - 19.00 WIB</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Deskripsi</div>
                                <div class="col-7 fw-semibold">Update & input data pelanggan bulan ini</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-5 text-muted">Lampiran Gambar</div>
                                <div class="col-7 fw-semibold">
                                    <picture>
                                        <img src="./assets/img/categories/03.jpg" alt="category"
                                            class="img-fluid rounded">
                                    </picture>
                                </div>
                            </div>

                            <div class="timeline-container small">
                                <div class="position-relative ps-3 ms-1 mb-4">
                                    <div class="timeline-dot bg-success"></div>
                                    <div class="fw-semibold">
                                        <span class="text-success">Disetujui</span> Oleh SPV Finance
                                    </div>
                                    <div class="text-muted ms-3 small">13 Maret 2025 11:20:31 WIB</div>
                                    <div class="timeline-line"></div>
                                </div>

                                <div class="position-relative ps-3 ms-1 mb-4">
                                    <div class="timeline-dot bg-info"></div>
                                    <div class="fw-semibold">
                                        <span class="text-info">Disetujui</span> Oleh HR
                                    </div>
                                    <div class="text-muted ms-3 small">13 Maret 2025 15:10:02 WIB</div>
                                    <div class="timeline-line"></div>
                                </div>

                                <div class="position-relative ps-3 ms-1">
                                    <div class="timeline-dot bg-secondary"></div>
                                    <div class="text-muted">Requested</div>
                                    <div class="text-muted ms-3 small">13 Maret 2025 07:20:31 WIB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary w-100 w-md-auto"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 3px;
        }

        .timeline-line {
            position: absolute;
            width: 2px;
            height: 55px;
            /* sesuaikan dengan jarak antar dot */
            background-color: #ccc;
            left: 4px;
            top: 12px;
        }

        .timeline-line1 {
            position: absolute;
            width: 2px;
            height: 40px;
            /* sesuaikan dengan jarak antar dot */
            background-color: #ccc;
            left: 4px;
            top: 12px;
        }

        .text-muted {
            color: var(--bs-body-color) !important;
        }
    </style>
</div>