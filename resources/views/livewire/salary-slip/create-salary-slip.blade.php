<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Create Slip Gaji</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Slip Gaji</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>NPK/NIP</label>
                        <input type="text" class="form-control" placeholder="Contoh: 241120901">
                    </div>
                    <div class="col-md-4">
                        <label>Nama</label>
                        <div class="input-group">
                            <select class="form-select">
                                <option value="">Pilih Karyawan</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Alice Johnson</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label>Departemen</label>
                        <div class="input-group">
                            <select class="form-select">
                                <option value="">Pilih Departemen</option>
                                <option value="1">IT</option>
                                <option value="2">HR</option>
                                <option value="3">Finance</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-success text-white">PENDAPATAN</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Upah Pokok</label>
                        <input type="text" class="form-control" placeholder="Rp2.000.000">
                    </div>
                    <div class="col-md-4">
                        <label>Tunjangan Jabatan</label>
                        <input type="text" class="form-control" placeholder="Rp500.000">
                    </div>
                    <div class="col-md-4">
                        <label>Tunjangan Kehadiran</label>
                        <input type="text" class="form-control" placeholder="Rp300.000">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Upah Lembur</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label>Uang Makan</label>
                        <input type="text" class="form-control" placeholder="Rp1.000.000">
                    </div>
                    <div class="col-md-4">
                        <label>Uang Transport</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Bonus</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label>Tunjangan Kebudayaan</label>
                        <input type="text" class="form-control" placeholder="Rp1.000.000">
                    </div>
                    <div class="col-md-4">
                        <label>Fee Belajar</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-danger text-white">POTONGAN</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Potongan Terlambat</label>
                        <input type="text" class="form-control" placeholder="Rp20.000">
                    </div>
                    <div class="col-md-4">
                        <label>Potongan Ijin</label>
                        <input type="text" class="form-control" placeholder="Rp40.000">
                    </div>
                    <div class="col-md-4">
                        <label>Kas bon</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>JKK</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label>JKM</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label>JHT Karyawan</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Kesehatan</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label>Voucher</label>
                        <input type="text" class="form-control" placeholder="-">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-info text-white">KEHADIRAN</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label>Terlambat</label>
                        <input type="number" class="form-control" placeholder="2">
                    </div>
                    <div class="col-md-4">
                        <label>Izin</label>
                        <input type="number" class="form-control" placeholder="1">
                    </div>
                    <div class="col-md-4">
                        <label>Cuti</label>
                        <input type="number" class="form-control" placeholder="0">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Kehadiran</label>
                        <input type="number" class="form-control" placeholder="25">
                    </div>
                    <div class="col-md-4">
                        <label>Lembur</label>
                        <input type="number" class="form-control" placeholder="0">
                    </div>
                </div>
            </div>
        </div>


        <div class="text-end">
            <button class="btn btn-primary">Simpan Slip Gaji</button>
            <button class="btn btn-secondary">Kembali</button>
        </div>
    </div>
</div>