<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Profile Saya</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile Saya</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="max-width: 720px;">
        <div class="card shadow-sm rounded-4 my-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-start gap-4">
                <!-- Foto Profil -->
                <div class="text-center">
                    <img src="./assets/img/user4-128x128.jpg" class="rounded-circle border border-primary" width="96"
                        height="96" alt="Foto Pegawai">
                </div>

                <!-- Info Pegawai -->
                <div class="flex-grow-1 w-100">
                    <div class="d-flex flex-row flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <h5 class="fw-bold mb-1">Nadia Safira Khairunnisa</h5>
                            <p class="mb-0 text-muted small">Marketing</p>
                            <p class="mb-0 text-muted small">ID: 12345678</p>
                        </div>
                        <span class="badge bg-light text-primary fw-semibold px-3 py-1">Aktif</span>
                    </div>

                    <hr class="my-3">

                    <!-- Detail Kontak -->
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted">Email</small>
                            <div class="fw-medium">dmarketing2@gmail.com</div>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted">No. Hp</small>
                            <div class="fw-medium">087876543216</div>
                        </div>
                    </div>

                    <!-- Performance -->
                    <div class="row align-items-start mt-3">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <p class="text-muted mb-1">Performance</p>
                                    <div class="d-flex align-items-end gap-1">
                                        <span class="fs-4 fw-bold text-dark">87</span>
                                        <span class="text-muted">/100</span>
                                    </div>
                                    <div class="progress" style="height: 6px; width: 100%; max-width: 240px;">
                                        <div class="progress-bar bg-primary" style="width: 87%;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center justify-content-end">
                                    <img src="./assets/img/categories/04.jpg" alt="Top Performer"
                                        style="height: 48px; object-fit: contain;">
                                </div>
                            </div>
                        </div>

                        <!-- Info Lain -->
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <small class="text-muted">Tanggal Bergabung</small>
                                <div class="fw-medium">10 Nov 2024</div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">Rekruiter</small>
                                <div class="fw-medium">Dimas Pradana</div>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDetailProfil">
                                View Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Profil -->
    <div class="modal fade" id="modalDetailProfil" tabindex="-1" aria-labelledby="modalDetailProfilLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content custom-rounded">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailProfilLabel">Profil Saya</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4 border-bottom pb-3 mt-3">
                        <img src="./assets/img/user4-128x128.jpg" alt="Avatar" class="rounded-circle me-3" width="64"
                            height="64">
                        <div>
                            <h5 class="mb-0">Nadia Safira Khairunnisa</h5>
                            <small class="text-muted">Marketing</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kiri -->
                        <div class="col-md-6">
                            <h6>Informasi Umum</h6>
                            <div class="mb-4">
                                <div class="row mb-2 small">
                                    <div class="col-5 text-muted">ID Karyawan</div>
                                    <div class="col-7 fw-semibold text-dark">1234567</div>
                                </div>
                                <div class="row mb-2 small">
                                    <div class="col-5 text-muted">Divisi</div>
                                    <div class="col-7 fw-semibold text-dark">SM</div>
                                </div>
                                <div class="row mb-2 small">
                                    <div class="col-5 text-muted">Jenis Karyawan</div>
                                    <div class="col-7 fw-semibold text-dark">PKWT-1</div>
                                </div>
                                <div class="row mb-2 small">
                                    <div class="col-5 text-muted">Entitas</div>
                                    <div class="col-7 fw-semibold text-dark">DJB</div>
                                </div>
                                <div class="row mb-2 small">
                                    <div class="col-5 text-muted">SPV</div>
                                    <div class="col-7 fw-semibold text-dark">Afnan Dhika</div>
                                </div>
                                <div class="row small">
                                    <div class="col-5 text-muted">Tanggal Bergabung</div>
                                    <div class="col-7 fw-semibold text-dark">10 Nov 2024</div>
                                </div>
                            </div>


                            <!-- Riwayat Kehadiran -->
                            <div class="col-md-6">
                                <h6>Riwayat Kehadiran</h6>
                                <div class="mb-4">
                                    <div class="row mb-2 small">
                                        <div class="col-6 text-muted">Cuti Diambil</div>
                                        <div class="col-6 text-end fw-semibold text-dark">2 Hari</div>
                                    </div>
                                    <div class="row mb-2 small">
                                        <div class="col-6 text-muted">Terlambat</div>
                                        <div class="col-6 text-end fw-semibold text-dark">16 Kali</div>
                                    </div>
                                    <div class="row small">
                                        <div class="col-6 text-muted">Izin</div>
                                        <div class="col-6 text-end fw-semibold text-dark">3 Hari</div>
                                    </div>
                                </div>
                            </div>

                            <h6>Feedback HR</h6>
                            <p class="small text-muted">
                                Nadia sangat cekatan dan mempunyai plan yang terstruktur serta komunikasi yang baik
                                di pekerjaan.
                            </p>
                        </div>

                        <!-- Kanan -->
                        <div class="col-md-6">
                            <h6>Skor Performa</h6>
                            <p class="small"><strong>Target Terpenuhi:</strong> 4/12</p>

                            <h6 class="mt-3">Riwayat Proyek</h6>
                            <div class="mb-3">
                                <span class="badge bg-secondary me-1">Campaign "1000 jaringan" 2025</span>
                                <span class="badge bg-secondary me-1">Digital Marketing Overhaul</span>
                            </div>

                            <h6>Riwayat Pelatihan/Sertifikasi</h6>
                            <div>
                                <span class="badge bg-primary me-1">Pelatihan SEO (2024)</span>
                                <span class="badge bg-primary me-1">Sertifikat Google Ads</span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-content.custom-rounded {
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            /* soft shadow */
            padding: 1.5rem;
        }

        .modal-body {
            padding-top: 0;
        }
    </style>
</div>