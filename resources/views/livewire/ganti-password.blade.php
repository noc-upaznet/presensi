<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Ganti Password</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ganti Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content py-4">
        <div class="container-sm">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card shadow-sm rounded-4 border-0">
                        <form>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">Ubah Password</h5>

                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label fw-semibold">Password
                                        Sekarang</label>
                                    <input type="password" class="form-control" id="currentPassword"
                                        placeholder="Masukkan password sekarang">
                                </div>

                                <div class="mb-3">
                                    <label for="newPassword" class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" class="form-control" id="newPassword"
                                        placeholder="Masukkan password baru">
                                </div>

                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password
                                        Baru</label>
                                    <input type="password" class="form-control" id="confirmPassword"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>

                            <div class="card-footer bg-white border-0 px-4 pb-4">
                                <button type="button" class="btn btn-primary w-100 w-md-auto" data-bs-toggle="modal"
                                    data-bs-target="#passwordChangeModal">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="passwordChangeModal" tabindex="-1" aria-labelledby="passwordChangeModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 350px;">
                <div class="modal-content text-center" style="border-radius: 20px;">
                    <div class="modal-body py-5">
                        <div class="text-warning fs-1 mb-3">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <h5 class="mb-4">Apakah anda yakin ingin mengganti password anda?</h5>
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Yes</button>
                            <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>