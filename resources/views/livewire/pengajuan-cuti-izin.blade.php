<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Pengajuan Cuti/Izin</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pengajuan Cuti/Izin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="mb-4">
                <div class="card-header mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengajuan">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>
                </div>

                <div class="p-0 table-responsive">
                    <table class="table table-striped" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr class="users-table-info">
                                <th>Tanggal</th>
                                <th>Jenis Pengajuan</th>
                                <th>Waktu</th>
                                <th>Deskripsi</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">22-04-2025</td>
                                <td style="color: var(--bs-body-color);">Cuti</td>
                                <td style="color: var(--bs-body-color);">Full-Day</td>
                                <td style="color: var(--bs-body-color);">Ambil Jatah Cuti Bulan April</td>
                                <td style="color: var(--bs-body-color);">
                                    {{-- <img src="./assets/img/categories/03.jpg" alt="File" class="img-fluid rounded"
                                        style="max-width: 70px;"> --}}
                                </td>
                                <td>
                                    <span class="badge bg-success">Disetujui</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetailPengajuan2">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td style="color: var(--bs-body-color); white-space: nowrap;">12-02-2025</td>
                                <td style="color: var(--bs-body-color);">Izin</td>
                                <td style="color: var(--bs-body-color);">Full-Day</td>
                                <td style="color: var(--bs-body-color);">Sakit demam sudah 2 Hari</td>
                                <td style="color: var(--bs-body-color);">
                                    <img src="./assets/img/categories/03.jpg" alt="File" class="img-fluid rounded"
                                        style="max-width: 70px;">
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetailPengajuan">
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

    <!-- Modal Tambah Pengajuan Cuti/Izin -->
    <div class="modal fade" id="modalTambahPengajuan" tabindex="-1" aria-labelledby="modalTambahPengajuanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="jenis_pengajuan" class="form-label">Jenis Pengajuan</label>
                            <select class="form-select" id="jenis_pengajuan">
                                <option value="izin">Izin</option>
                                <option value="cuti">Cuti</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggal" placeholder="13 - 17 Maret 2025">
                        </div>

                        <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <select class="form-select" id="waktu">
                                <option value="full">Sehari Penuh</option>
                                <option value="half">Setengah Hari</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" placeholder="Contoh: Demam Tinggi">
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">File (Opsional)</label>
                            <input type="file" class="form-control" id="file">
                            <div class="form-text">Ukuran maksimal file: 5MB</div>
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

    <!-- Modal Detail Pengajuan Izin Pending -->
    <div class="modal fade" id="modalDetailPengajuan" tabindex="-1" aria-labelledby="modalDetailPengajuanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-3 py-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./assets/img/user4-128x128.jpg" alt="Foto Pegawai" class="rounded-circle" width="80"
                            height="80">
                        <h5 class="mt-3 mb-0 fw-bold">Nadia Safira Khairunnisa</h5>
                        <small class="text-muted">Admin Human Resources</small>
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
                                <div class="col-5 text-muted">Nama Pengajuan</div>
                                <div class="col-7 fw-semibold">Izin Sakit</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Jenis</div>
                                <div class="col-7 fw-semibold">Sehari Penuh</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Tanggal Cuti</div>
                                <div class="col-7 fw-semibold">13 - 17 Mar 2025</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Lama Izin/Cuti</div>
                                <div class="col-7 fw-semibold">2 Hari</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-5 text-muted">Tanggal Permohonan</div>
                                <div class="col-7 fw-semibold">13 Mar 2025</div>
                            </div>

                            <div class="position-relative ps-4 ms-1">
                                <div class="timeline-dot bg-dark"></div>
                                <div class="fw-semibold">Menunggu Persetujuan dari Amin Syukron</div>
                                <div class="timeline-line"></div>
                            </div>

                            <div class="position-relative ps-4 ms-1 mt-3">
                                <div class="timeline-dot bg-secondary"></div>
                                <div class="text-muted">Requested</div>
                                <div class="text-muted ms-4">13 Maret 2025 07:20:31 WIB</div>
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

    <!-- Modal Detail Pengajuan Izin Sukses -->
    <div class="modal fade" id="modalDetailPengajuan2" tabindex="-1" aria-labelledby="modalDetailPengajuanLabel2"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-3 py-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./assets/img/user4-128x128.jpg" alt="Foto Pegawai" class="rounded-circle" width="80"
                            height="80">
                        <h5 class="mt-3 mb-0 fw-bold">Nadia Safira Khairunnisa</h5>
                        <small class="text-muted">Admin Human Resources</small>
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
                                <div class="col-5 text-muted">Nama Pengajuan</div>
                                <div class="col-7 fw-semibold">Izin Sakit</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Jenis</div>
                                <div class="col-7 fw-semibold">Sehari Penuh</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Tanggal Cuti</div>
                                <div class="col-7 fw-semibold">13 - 17 Mar 2025</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Lama Izin/Cuti</div>
                                <div class="col-7 fw-semibold">2 Hari</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-5 text-muted">Tanggal Permohonan</div>
                                <div class="col-7 fw-semibold">13 Mar 2025</div>
                            </div>

                            <div class="position-relative ps-4 ms-1">
                                <div class="timeline-dot bg-success"></div>
                                <div class="fw-semibold">
                                    <span class="text-success">Disetujui</span> oleh Amin Syukron
                                </div>
                                <div class="text-muted ms-4">13 Maret 2025 09:10:41 WIB</div>
                                <div class="timeline-line mt3"></div>
                            </div>

                            <div class="position-relative ps-4 ms-1 mt-3">
                                <div class="timeline-dot bg-secondary"></div>
                                <div class="text-muted">Requested</div>
                                <div class="text-muted ms-4">13 Maret 2025 07:20:31 WIB</div>
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

        .text-muted {
            color: var(--bs-body-color) !important;
        }
    </style>
</div>