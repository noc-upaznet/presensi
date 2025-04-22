<div>
    <div class="p-4">
        <h5 class="fw-bold mb-3" style="color: var(--bs-body-color);">Detail Data Karyawan</h5>
    
        <div class="border rounded-4 p-4">
            <h6 class="fw-bold text-primary">Detail Informasi Pribadi</h6>
            <div class="row align-items-start mt-3">
                <div class="col-md-3 text-center">
                    <img src="assets/img/avatar2.png" class="square-circle border" alt="Foto Karyawan">
                </div>
                <div class="col-md-9" style="color: var(--bs-body-color);">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Nama</span> <span>: Salsabila Putri</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tempat Lahir</span> <span>: Tulungagung</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tanggal Lahir</span> <span>: 09-01-1997</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Status Pernikahan</span> <span>: Belum Menikah</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Kewarganegaraan</span> <span>: Indonesia</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Gol. Darah</span> <span>: O</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Berat Badan</span> <span>: 55</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tinggi Badan</span> <span>: 155</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Ukuran Sepatu</span> <span>: 39</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Ukuran Baju</span> <span>: M</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <hr class="my-4">
    
            <div class="row" style="color: var(--bs-body-color);">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Pribadi</h6>
                    @foreach([
                        'Jenis Kelamin' => 'Perempuan',
                        'NIK' => '3509871234560197',
                        'Agama' => 'Islam',
                        'Jabatan' => 'Billing',
                        'Divisi' => 'Finance',
                        'Tanggal Bergabung' => '09-06-2024',
                        'Status Hubungan Kerja' => 'Pegawai Kontrak',
                        'Sisa Kontrak' => '6 Bulan'
                    ] as $label => $value)
                        <div class="mb-1 d-flex justify-content-between">
                            <span>{{ $label }}</span> <span>: {{ $value }}</span>
                        </div>
                    @endforeach
                </div>
    
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Kontak</h6>
                    @foreach([
                        'Telepon' => '087990321654',
                        'Email' => 'psbila201@gmail.com',
                        'LinkedIn' => 'Salsabilap22',
                        'Instagram' => 'bilasp'
                    ] as $label => $value)
                        <div class="mb-1 d-flex justify-content-between">
                            <span>{{ $label }}</span> <span>: {{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
    
            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Informasi Alamat</h6>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat</span>
                <span>: Jl. Pahlawan No. 25, Desa Beji, Kecamatan Boyolangu, Kabupaten Tulungagung, Jawa Timur 66235</span>
            </div>
        </div>
    </div>
</div>
