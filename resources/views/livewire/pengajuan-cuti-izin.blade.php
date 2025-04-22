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
                <div class="card-header">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengajuan">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </button>

                </div>
                <div class="p-0">
                    <table class="table table-striped" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr class="users-table-info">
                                <th>Tanggal</th>
                                <th>Jenis Pengajuan</th>
                                <th>Waktu</th>
                                <th>Deskripsi</th>
                                <th>
                                    <label class="users-table__checkbox ms-20">
                                        File
                                    </label>
                                </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="color: var(--bs-body-color);">22-04-2025</td>
                                <td style="color: var(--bs-body-color);">Cuti</td>
                                <td style="color: var(--bs-body-color);">Full-Day</td>
                                <td style="color: var(--bs-body-color);">Ambil Jatah Cuti Bulan April</td>
                                <td style="color: var(--bs-body-color);">
                                    <label class="users-table__checkbox">
                                        <div class="categories-table-img">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <picture>
                                                        {{--
                                                        <source srcset="assets/img/categories/03.webp"
                                                            type="image/webp"><img src="./img/categories/03.jpg"
                                                            alt="category"> --}}
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </td>
                                <td>
                                    <span class="badge bg-success">Disetujui</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--bs-body-color);">12-02-2025</td>
                                <td style="color: var(--bs-body-color);">Izin</td>
                                <td style="color: var(--bs-body-color);">Full-Day</td>
                                <td style="color: var(--bs-body-color);">Sakit demam sudah 2 Hari</td>
                                <td style="color: var(--bs-body-color);">
                                    <label class="users-table__checkbox">
                                        <div class="categories-table-img">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <picture>
                                                        <source srcset="assets/img/categories/03.webp"
                                                            type="image/webp"><img src="./img/categories/03.jpg"
                                                            alt="category">
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </td>
                                <td>
                                    <span class="badge bg-warning">Menunggu persetujuan</span>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="mb-3">
                            <label for="jenis_pengajuan" class="form-label">Jenis Pengajuan</label>
                            <select class="form-select" id="jenis_pengajuan">
                                <option>Izin</option>
                                <option>Cuti</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="text" class="form-control" id="tanggal" placeholder="13 - 17 Maret 2025">
                        </div>
                        <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <select class="form-select" id="waktu">
                                <option>Sehari Penuh</option>
                                <option>Setengah Hari</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" placeholder="Contoh: Demam Tinggi">
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">File (Opsional)</label>
                            <input type="file" class="form-control" id="file">
                            <div class="form-text">Max file size: 5MB</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Request</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>