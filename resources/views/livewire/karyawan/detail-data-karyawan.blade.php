<div>
    <div class="p-4">
        <h5 class="fw-bold mb-3" style="color: var(--bs-body-color);">Detail Data Karyawan</h5>
    
        <div class="border rounded-4 p-4">
            <h6 class="fw-bold text-primary">Detail Informasi Pribadi</h6>
            <div class="row align-items-start mt-3">
                <div class="col-md-12" style="color: var(--bs-body-color);">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Nama</span> <span>: {{ $karyawan->nama_karyawan ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tempat Lahir</span> <span>: {{ $karyawan->tempat_lahir ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tanggal Lahir</span> <span>: {{ $karyawan->tanggal_lahir ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Status Pernikahan</span> <span>: {{ $karyawan->status_perkawinan ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Kewarganegaraan</span> <span>: Indonesia</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Gol. Darah</span> <span>: {{ $karyawan->gol_darah ?? '-' }}</span>
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
                        <span>Jenis Kelamin</span> <span>: {{ $karyawan->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>NIK</span> <span>: {{ $karyawan->nik ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Agama</span> <span>: {{ $karyawan->agama ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Divisi</span> <span>: {{ $karyawan->divisi ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Jabatan</span> <span>: {{ $karyawan->jabatan ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Level</span> <span>: {{ $karyawan->level ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Entitas</span> <span>: {{ $karyawan->entitas ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Tanggal Kontrak</span> <span>: {{ $karyawan->tgl_masuk ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Tanggal Habis Kontrak</span> <span>: {{ $karyawan->tgl_keluar ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Status Hubungan Kerja</span> <span>: {{ $karyawan->status_karyawan ?? '-' }}</span>
                    </div>
                </div>
    
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Kontak</h6>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>No.HP</span> <span>: {{ $karyawan->no_hp ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Email</span> <span>: {{ $karyawan->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
    
            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Informasi Alamat</h6>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat KTP</span>
                <span>: {{ $karyawan->alamat_ktp ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat Domisili</span>
                <span>: {{ $karyawan->alamat_domisili ?? '-' }}</span>
            </div>

            <hr class="my-4">

            <h6 class="fw-bold text-primary mb-3">Data Keluarga</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>HUBUNGAN KELUARGA</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>JENIS KELAMIN</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL LAHIR</th>
                            <th>AGAMA</th>
                            <th>PENDIDIKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SUAMI</td>
                            <td>{{ $karyawan->nama_karyawan ?? '-' }}</td>
                            <td>{{ $karyawan->nik ?? '-' }}</td>
                            <td>{{ $karyawan->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $karyawan->tempat_lahir ?? '-' }}</td>
                            <td>{{ $karyawan->tanggal_lahir ?? '-' }}</td>
                            <td>{{ $karyawan->agama ?? '-' }}</td>
                            <td>{{ $karyawan->pendidikan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2">STATUS PERKAWINAN</th>
                            <th rowspan="2">TANGGAL PERKAWINAN</th>
                            <th rowspan="2">STATUS HUBUNGAN<br>DALAM KELUARGA</th>
                            <th rowspan="2">KEWARGANEGARAAN</th>
                            <th colspan="2">DOKUMEN IMIGRASI</th>
                            <th colspan="2">NAMA ORANG TUA</th>
                        </tr>
                        <tr>
                            <th>NO PASPOR</th>
                            <th>NO KITAP</th>
                            <th>AYAH</th>
                            <th>IBU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Menikah</td>
                            <td>12-05-2018</td>
                            <td>Kepala Keluarga</td>
                            <td>Indonesia</td>
                            <td>A1234567</td>
                            <td>-</td>
                            <td>Budi</td>
                            <td>Siti</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold text-primary mb-3">Data Tanggungan (Suami/Istri/Anak) (Dependent Data of Husband/Wife/Children) </h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>HUBUNGAN KELUARGA</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>JENIS KELAMIN</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL LAHIR</th>
                            <th>PENDIDIKAN</th>
                            <th>PEKERJAAN</th>
                            <th>NO TELEPHONE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SUAMI</td>
                            <td>{{ $karyawan->nama_karyawan ?? '-' }}</td>
                            <td>{{ $karyawan->nik ?? '-' }}</td>
                            <td>{{ $karyawan->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $karyawan->tempat_lahir ?? '-' }}</td>
                            <td>{{ $karyawan->tanggal_lahir ?? '-' }}</td>
                            <td>{{ $karyawan->pendidikan ?? '-' }}</td>
                            <td>{{ $karyawan->pendidikan ?? '-' }}</td>
                            <td>{{ $karyawan->pendidikan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold text-primary mb-3 mt-3">RIWAYAT PENDIDIKAN (EDUCATIONAL BACKGROUND) </h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>JENJANG PENDIDIKAN</th>
                            <th>NAMA SEKOLAH / INSTITUT</th>
                            <th>TAHUN MULAI</th>
                            <th>TAHUN AKHIR</th>
                            <th>JURUSAN</th>
                            <th>NILAI/IPK (CGPA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SUAMI</td>
                            <td>{{ $karyawan->nama_karyawan ?? '-' }}</td>
                            <td>{{ $karyawan->nik ?? '-' }}</td>
                            <td>{{ $karyawan->jenis_kelamin ?? '-' }}</td>
                            <td>{{ $karyawan->tempat_lahir ?? '-' }}</td>
                            <td>{{ $karyawan->tanggal_lahir ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h6 class="fw-bold text-primary mb-3 mt-3">PENGALAMAN KERJA</h6>

            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Data Gamifikasi</h6>
            <div style="color: var(--bs-body-color);">
                <span>Jumlah Poin</span>
                <span>: {{ $karyawan->poin ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <div class="mb-3 mt-3 col-md-12">
                    <label for="jumlah-poin" class="form-label fw-semibold">Jumlah Poin</label>
                    <input type="number" wire:model.lazy="jml_poin" class="form-control" id="jumlah-poin" placeholder="Masukkan Jml. Poin">
                    <div align="right" class="mt-2">
                        <button class="btn btn-primary" wire:click="updateGamifikasi"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@push('scripts')
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>
@endpush
