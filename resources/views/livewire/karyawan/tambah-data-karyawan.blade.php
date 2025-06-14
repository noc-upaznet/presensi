<div>
    <style>
        .step-circle {
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #333;
            font-weight: bold;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }
        .step-circle.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .step-line {
            flex-grow: 0.1;
            height: 2px;
            background-color: #ddd;
        }
        body.dark-mode .step-circle {
            background-color: #444;
            color: #ccc;
        }
        body.dark-mode .step-circle.active {
            background-color: #0d6efd;
            color: #fff;
        }
        body.dark-mode .step-line {
            background-color: #555;
        }
    </style>
    <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid" style="color: var(--bs-body-color);">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Tambah Data Karyawan</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data Karyawan</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="mb-4">
            <div class="card-header">
            <!-- /.card-header -->
            <div class="card-body p-0">
                <!-- Step Progress -->
                <div class="d-flex justify-content-center align-items-center mb-5 mt-4" style="color: var(--bs-body-color);">
                    <div class="text-center mx-5">
                        <div class="rounded-circle {{ $step >= 1 ? 'bg-primary text-white' : 'bg-light text-dark' }} d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            1
                        </div>
                        <div><small>Data Personal</small></div>
                    </div>
                    <div class="step-line {{ $step > 1 ? 'bg-primary text-white' : 'bg-light text-dark' }}"></div>
                    <div class="text-center mx-5">
                        <div class="rounded-circle {{ $step >= 2 ? 'bg-primary text-white' : 'bg-light text-dark' }} d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            2
                        </div>
                        <div><small>Data Ketenagakerjaan</small></div>
                    </div>
                    <div class="step-line {{ $step > 2 ? 'bg-primary text-white' : 'bg-light text-dark' }}"></div>
                    <div class="text-center mx-5">
                        <div class="rounded-circle {{ $step >= 3 ? 'bg-primary text-white' : 'bg-light text-dark' }} d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            3
                        </div>
                        <div><small>Data Gaji</small></div>
                    </div>
                </div>
                <div class="container" style="color: var(--bs-body-color);">
                    @if ($step === 1)
                        <!-- Form Data Personal -->
                        <div>
                            <p>Silahkan isi data karyawan di bawah ini.</p>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Data Personal</h4>
                        <div class="mb-3">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" wire:model="form.nama_karyawan" required>
                            @error('form.nama_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" wire:model="form.email" required>
                                @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no-hp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="no-hp" name="no_hp" wire:model="form.no_hp" required>
                                @error('form.no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" wire:model="password">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tempat-lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir" wire:model="form.tempat_lahir" required>
                                @error('form.tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tgl-lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tgl-lahir" name="tanggal_lahir" wire:model="form.tanggal_lahir">
                                @error('form.tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis-kelamin" class="form-label">Jenis Kelamin</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="laki-laki" value="Laki-laki" wire:model="form.jenis_kelamin" name="jenis_kelamin">
                                        <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="perempuan" value="Perempuan" name="jenis_kelamin" wire:model="form.jenis_kelamin">
                                        <label class="form-check-label" for="perempuan">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status_perkawinan" class="form-label">Status Perkawinan</label>
                                <select class="form-select" id="status_perkawinan" wire:model="form.status_perkawinan" name="status_perkawinan">
                                    <option selected value="">-- Pilih Status Perkawinan --</option>
                                    <option value="Menikah">Menikah</option>
                                    <option value="Belum Menikah">Belum Menikah</option>
                                </select>
                                @error('form.status_perkawinan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gol_darah" class="form-label">Gol. Darah</label>
                                <input type="text" class="form-control" id="gol_darah" wire:model="form.gol_darah" name="gol_darah">
                                @error('form.gol_darah') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="agama" class="form-label">Agama</label>
                                <select class="form-select" wire:model="form.agama" id="agama" name="agama">
                                    <option selected>-- Pilih Agama --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Katolik">Kristen Katolik</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                @error('form.agama') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Identitas & Alamat</h4>
                        <div class="row" x-data="{ jenis: @entangle('form.jenis_identitas'), nomorKTP: @entangle('form.nomorKTP'), errorKTP: '' }">
                            <div class="col-md-6 mb-3">
                                <label for="jenisIdentitas" class="form-label">Jenis Identitas</label>
                                <select class="form-select" id="jenisIdentitas" x-model="jenis">
                                    <option value="">-- Pilih Jenis Identitas --</option>
                                    <option value="KTP">KTP</option>
                                    <option value="VISA">VISA</option>
                                </select>
                            </div>
                
                            <div class="col-md-6 mb-3" x-show="jenis === 'KTP'">
                                <label for="nomorKTP" class="form-label">Nomor KTP/ NIK</label>
                                <input type="text" class="form-control" maxlength="16" placeholder="Masukkan Nomor KTP / NIK" wire:model="form.nomorKTP" x-on:input="
                                if($el.value.match(/[^0-9]/g)) {
                                    errorKTP = 'Nomor KTP / NIK hanya boleh angka!';
                                    $el.value = $el.value.replace(/[^0-9]/g, '');
                                } else {
                                    errorKTP = '';
                                }">
                                <div class="text-danger mt-1" x-text="errorKTP" x-show="errorKTP"></div>
                            </div>
                
                            <div class="col-md-6 mb-3" x-show="jenis === 'VISA'">
                                <label for="nomorVISA" class="form-label">Nomor VISA</label>
                                <input type="text" class="form-control" placeholder="Masukkan Nomor VISA" wire:model="form.nomorVISA">
                            </div>
                        </div>
                        <div x-data="{
                            syncAlamat() {
                                if (this.$wire.form.gunakanAlamatKTP) {
                                    this.$wire.form.alamatDomisili = this.$wire.form.alamatKTP;
                                } else {
                                    this.$wire.form.alamatDomisili = '';
                                }
                            }
                        }" x-init="$watch(() => $wire.form.gunakanAlamatKTP, value => syncAlamat())">
                            <div class="mb-3">
                                <label for="alamatKTP" class="form-label">Alamat Sesuai KTP</label>
                                <textarea class="form-control" id="alamatKTP" wire:model.defer="form.alamatKTP"></textarea>
                                @error('form.alamatKTP') <span class="text-danger">{{ $message }}</span> @enderror
                        
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="copyAlamat" wire:model="form.gunakanAlamatKTP">
                                    <label class="form-check-label" for="copyAlamat">
                                        Gunakan alamat KTP sebagai alamat domisili
                                    </label>
                                </div>
                            </div>
                        
                            <div class="mb-3">
                                <label for="alamatDomisili" class="form-label">Alamat Domisili</label>
                                <textarea class="form-control" id="alamatDomisili"
                                        wire:model.defer="form.alamatDomisili"
                                        :readonly="$wire.form.gunakanAlamatKTP"></textarea>
                                @error('form.alamatDomisili') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div align="right">
                            @if ($step > 1)
                                <button type="button" wire:click="prevStep" class="btn btn-secondary">Kembali</button>
                            @endif
                        
                            @if ($step < 3)
                                <button type="button" wire:click="nextStep" class="btn btn-primary">Selanjutnya</button>
                            @else
                                <button type="button" wire:click="store" class="btn btn-success">Simpan</button>
                            @endif
                        </div>
                    @endif
                    @if ($step === 2)
                        <!-- Form Data Ketenagakerjaan -->
                        <div>
                            <p>Silahkan isi data ketenagakerjaan di bawah ini.</p>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Data Ketenagakerjaan</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nip-karyawan" class="form-label">NPK/NIP Karyawan</label>
                                <input type="text" class="form-control" id="nip-karyawan" wire:model="form.nip_karyawan" name="nip_karyawan">
                                @error('form.nip_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status-karyawan" class="form-label">Status Karyawan</label>
                                <select class="form-select" id="status-karyawan" wire:model="form.status_karyawan">
                                    <option selected disabled value="">-- Pilih Status Karyawan --</option>
                                    <option value="Pegawai Kontrak(PKWT)">Pegawai Kontrak(PKWT)</option>
                                    <option value="Pegawai Tetap">Pegawai Tetap</option>
                                </select>
                                @error('form.status_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tgl-masuk" class="form-label">Tanggal Masuk Kerja</label>
                                <input type="date" class="form-control" id="tgl-masuk" wire:model="form.tgl_masuk" required>
                                @error('form.tgl_masuk') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tgl-keluar" class="form-label">Tanggal Berakhir Kerja</label>
                                <input type="date" class="form-control" id="tgl-keluar" wire:model="form.tgl_keluar" required>
                                @error('form.tgl_keluar') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="entitas" class="form-label">Entitas</label>
                                <select class="form-select" id="entitas" wire:model="form.entitas">
                                    <option selected disabled value="">-- Pilih Entitas --</option>
                                     @foreach ($entitas as $key)
                                        <option value="{{ $key->id }}">{{ $key->nama }}</option>
                                    @endforeach
                                </select>
                                @error('form.entitas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="divisi" class="form-label">Divisi</label>
                                <select class="form-select" id="divisi" wire:model="form.divisi">
                                    <option selected disabled value="">-- Pilih Divisi --</option>
                                     @foreach ($divisi as $key)
                                        <option value="{{ $key->id }}">{{ $key->nama }}</option>
                                    @endforeach
                                </select>
                                @error('form.divisi') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <select class="form-select" id="jabatan" wire:model="form.jabatan">
                                    <option selected disabled value="">-- Pilih Jabatan --</option>
                                     @foreach ($jabatan as $key)
                                        <option value="{{ $key->id }}">{{ $key->nama_jabatan }}</option>
                                    @endforeach
                                </select>
                                @error('form.jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sistem-kerja" class="form-label">Sistem Kerja</label>
                                <select class="form-select" id="sistem-kerja" wire:model="form.sistem_kerja">
                                    <option selected disabled value="">-- Pilih Sistem Kerja --</option>
                                    <option value="Work From Office(WFO)">Work From Office(WFO)</option>
                                    <option value="Work From Home(WFH)">Work From Home(WFH)</option>
                                    <option value="Work From Anywhere(WFA)">Work From Anywhere(WFA)</option>
                                </select>
                                @error('form.sistem_kerja') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div align="right">
                            @if ($step > 1)
                                <button type="button" wire:click="prevStep" class="btn btn-secondary">Kembali</button>
                            @endif
                        
                            @if ($step < 3)
                                <button type="button" wire:click="nextStep" class="btn btn-primary">Selanjutnya</button>
                            @else
                                <button type="button" wire:click="store" class="btn btn-success">Simpan</button>
                            @endif
                        </div>
                    @endif
                    @if ($step === 3)
                        <!-- Form Data Gaji -->
                        <div>
                            <p>Silahkan isi data penggajian di bawah ini.</p>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Penggajian</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gaji-pokok" class="form-label">Gaji Pokok</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="gaji-pokok">Rp.</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="gaji_pokok"
                                        oninput="formatRupiah(this)"
                                        wire:model.lazy="form.gaji_pokok"
                                    />
                                </div>
                                @error('form.gaji_pokok') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tunjangan-jabatan" class="form-label">Tunjangan Jabatan</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="tunjangan-jabatan">Rp.</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="tunjangan_jabatan"
                                        oninput="formatRupiah(this)"
                                        wire:model.lazy="form.tunjangan_jabatan"
                                    />
                                </div>
                                @error('form.tunjangan_jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bonus" class="form-label">Bonus (Jika Memenuhi Target)</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="bonus">Rp.</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="bonus"
                                        oninput="formatRupiah(this)"
                                        wire:model.lazy="form.bonus"
                                    />
                                </div>
                                @error('form.bonus') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="jenis-penggajian" class="form-label">Jenis Penggajian</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_penggajian" id="bulanan" value="Bulanan" wire:model="form.jenis_penggajian">
                                        <label class="form-check-label" for="bulanan">Bulanan</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jenis_penggajian" id="harian" value="Harian" wire:model="form.jenis_penggajian">
                                        <label class="form-check-label" for="harian">Harian</label>
                                    </div>
                                </div>
                                @error('form.jenis_penggajian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Rekening Bank</h4>
                        <div class="mb-3">
                            <label for="nama-bank" class="form-label">Nama Bank</label>
                            <select class="form-select" id="nama-bank" wire:model="form.nama_bank">
                                <option selected disabled value="">-- Pilih Bank --</option>
                                <option value="BCA">Bank Central Asia (BCA)</option>
                                <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
                                <option value="BNI">Bank Negara Indonesia (BNI)</option>
                                <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                <option value="Mandiri">Bank Mandiri</option>
                            </select>
                            @error('form.nama_bank') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no-rek" class="form-label">Nomor Rekening</label>
                                <input class="form-control" id="no-rek" wire:model="form.no_rek">
                                @error('form.no_rek') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama-pemilik-rekening" class="form-label">Nama Pemilik Rekening</label>
                                <input class="form-control" id="nama-pemilik-rekening" wire:model="form.nama_pemilik_rekening">
                                @error('form.nama_pemilik_rekening') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <h4 style="color: blue; margin-bottom: 20px;">Pengaturan BPJS</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no-bpjs-tk" class="form-label">Nomor BPJS Ketenagakerjaan</label>
                                <input class="form-control" id="no-bpjs-tk" wire:model="form.no_bpjs_tk">
                                @error('form.no_bpjs_tk') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="npp-bpjs-tk" class="form-label">NPP BPJS Ketenagakerjaan</label>
                                <select class="form-select" id="npp-bpjs-tk" wire:model="form.npp_bpjs_tk">
                                    <option selected disabled value="">-- Pilih --</option>
                                    <option value="Test">Test</option>
                                </select>
                                @error('form.npp_bpjs_tk') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tgl-aktif-bpjstk" class="form-label">Tanggal Aktif BPJS Ketenagakerjaan</label>
                            <input type="date" class="form-control" id="tgl-aktif-bpjstk" wire:model="form.tgl_aktif_bpjstk">
                            @error('form.tgl_aktif_bpjstk') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no-bpjs" class="form-label">Nomor BPJS Kesehatan</label>
                                <input class="form-control" id="no-bpjs" wire:model="form.no_bpjs">
                                @error('form.no_bpjs') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="anggota-bpjs" class="form-label">Anggota BPJS Kesehatan</label>
                                <select class="form-select" id="anggota-bpjs" wire:model="form.anggota_bpjs">
                                    <option selected disabled value="">-- Pilih --</option>
                                    <option value="Test">Test</option>
                                </select>
                                @error('form.anggota_bpjs') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tgl-aktif-bpjs" class="form-label">Tanggal Aktif BPJS Kesehatan</label>
                                <input type="date" class="form-control" id="tgl-aktif-bpjs" wire:model="form.tgl_aktif_bpjs">
                                @error('form.tgl_aktif_bpjs') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="penanggung" class="form-label">Ditanggung Oleh</label>
                                <select class="form-select" id="penanggung" wire:model="form.penanggung">
                                    <option selected disabled value="">-- Pilih --</option>
                                    <option value="Karyawan">Karyawan</option>
                                    <option value="Perusahaan">Perusahaan</option>
                                </select>
                                @error('form.penanggung') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div align="right">
                            @if ($step > 1)
                                <button type="button" wire:click="prevStep" class="btn btn-secondary">Kembali</button>
                            @endif
                        
                            @if ($step < 3)
                                <button type="button" wire:click="nextStep" class="btn btn-primary">Selanjutnya</button>
                            @else
                                <button type="button" wire:click="store" class="btn btn-success"
                                    wire:loading.attr="disabled">
                                    <div wire:loading class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                                    <span wire:loading>Loading...</span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push("scripts")
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        function formatRupiah(input) {
            let value = input.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            input.value = rupiah;
        }
    </script>
    
@endpush
