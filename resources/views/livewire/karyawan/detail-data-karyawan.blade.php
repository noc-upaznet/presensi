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
                                <span>Nama</span> <span>: {{ $data['nama_karyawan'] }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tempat Lahir</span> <span>: {{ $data['tempat_lahir'] }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tanggal Lahir</span> <span>: {{ $data['tanggal_lahir'] }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Status Pernikahan</span> <span>: {{ $data['status_perkawinan'] }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Kewarganegaraan</span> <span>: Indonesia</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Gol. Darah</span> <span>: {{ $data['gol_darah'] }}</span>
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
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Jenis Kelamin</span> <span>: {{ $data['jenis_kelamin'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>NIK</span> <span>: {{ $data['nik'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Agama</span> <span>: {{ $data['agama'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Jabatan</span> <span>: {{ $data['jabatan'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Divisi</span> <span>: {{ $data['divisi'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Tanggal Masuk</span> <span>: {{ $data['tgl_masuk'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Status Hubungan Kerja</span> <span>: {{ $data['status_karyawan'] }}</span>
                    </div>
                </div>
    
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Kontak</h6>
                    {{-- @foreach([
                        'Telepon' => '087990321654',
                        'Email' => 'psbila201@gmail.com',
                        'LinkedIn' => 'Salsabilap22',
                        'Instagram' => 'bilasp'
                    ] as $label => $value)
                        <div class="mb-1 d-flex justify-content-between">
                            <span>{{ $label }}</span> <span>: {{ $value }}</span>
                        </div>
                    @endforeach --}}
                    <div class="mb-1 d-flex justify-content-between">
                        <span>No.HP</span> <span>: {{ $data['no_hp'] }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Email</span> <span>: {{ $data['email'] }}</span>
                    </div>
                </div>
            </div>
    
            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Informasi Alamat</h6>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat KTP</span>
                <span>: {{ $data['alamat_ktp'] }}</span>
            </div>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat Domisili</span>
                <span>: {{ $data['alamat_domisili'] }}</span>
            </div>
        </div>
    </div>
</div>
