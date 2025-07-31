<div>
    <div class="modal fade" id="modal-edit-data-karyawan" wire:ignore.self tabindex="-1">
        <div class="modal-dialog modal-xl">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header" style="color: var(--bs-body-color);">
                <h5 class="modal-title">Edit Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: var(--bs-body-color);">
                <div class="container">
                    

                    <!-- Form Data Personal -->
                    <div>
                        <p>Silahkan isi data karyawan di bawah ini.</p>
                    </div>
                    <h4 style="color: blue; margin-bottom: 20px;">Data Personal</h4>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="nama_karyawan" class="form-label">Nama Karyawan <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" wire:model="form.nama_karyawan" required>
                            @error('form.nama_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no-hp" class="form-label">No HP <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="no-hp" name="no_hp" wire:model="form.no_hp" required>
                            @error('form.no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <small class="text-danger">*</small></label>
                            <input type="email" class="form-control" id="email" name="email" wire:model="form.email" required>
                            @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tempat-lahir" class="form-label">Tempat Lahir <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="tempat-lahir" name="tempat_lahir" wire:model="form.tempat_lahir" required>
                            @error('form.tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl-lahir" class="form-label">Tanggal Lahir <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="tgl-lahir" name="tanggal_lahir" wire:model="form.tanggal_lahir">
                            @error('form.tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jenis-kelamin" class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="laki-laki" value="Laki-laki" wire:model="form.jenis_kelamin" name="jenis_kelamin">
                                    <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="perempuan" value="Perempuan" name="jenis_kelamin" wire:model="form.jenis_kelamin">
                                    <label class="form-check-label" for="perempuan">Perempuan</label>
                                </div>
                                <div>
                                    @error('form.jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_perkawinan" class="form-label">Status Perkawinan <small class="text-danger">*</small></label>
                            <select class="form-select" id="status_perkawinan" wire:model="form.status_perkawinan" name="status_perkawinan">
                                <option selected value="">-- Pilih Status Perkawinan --</option>
                                <option value="Married">Married</option>
                                <option value="Single">Single</option>
                                <option value="Widow/Widower">Widow/Widower</option>
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
                            <label for="agama" class="form-label">Agama <small class="text-danger">*</small></label>
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
                            <label for="jenisIdentitas" class="form-label">Jenis Identitas <small class="text-danger">*</small></label>
                            <select class="form-select" id="jenisIdentitas" x-model="jenis">
                                <option value="">-- Pilih Jenis Identitas --</option>
                                <option value="KTP">KTP</option>
                                <option value="VISA">VISA</option>
                            </select>
                            <div>
                                @error('form.jenis_identitas') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
            
                        <div class="col-md-6 mb-3" x-show="jenis === 'KTP'">
                            <label for="nomorKTP" class="form-label">Nomor KTP/ NIK <small class="text-danger">*</small></label>
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
                            <label for="nomorVISA" class="form-label">Nomor VISA <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" placeholder="Masukkan Nomor VISA" wire:model="form.nomorVISA">
                        </div>
                    </div>
                    <div>
                        <div class="mb-3">
                            <label for="alamatKTP" class="form-label">Alamat Sesuai KTP <small class="text-danger">*</small></label>
                            <textarea class="form-control" id="alamatKTP" wire:model.defer="form.alamatKTP"></textarea>
                            @error('form.alamatKTP') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    
                        <div class="mb-3">
                            <label for="alamatDomisili" class="form-label">Alamat Domisili <small class="text-danger">*</small></label>
                            <textarea class="form-control" id="alamatDomisili"
                                    wire:model.defer="form.alamatDomisili"></textarea>
                            @error('form.alamatDomisili') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <h4 style="color: blue; margin-bottom: 20px;">Data Ketenagakerjaan</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nip-karyawan" class="form-label">NPK/NIP Karyawan <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="nip-karyawan" wire:model="form.nip_karyawan" name="nip_karyawan">
                            @error('form.nip_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status-karyawan" class="form-label">Status Karyawan <small class="text-danger">*</small></label>
                            <select class="form-select" id="status-karyawan" wire:model="form.status_karyawan">
                                <option selected disabled value="">-- Pilih Status Karyawan --</option>
                                <option value="PKWT Kontrak">PKWT Kontrak</option>
                                <option value="Probation">Probation</option>
                                <option value="OJT">OJT</option>
                            </select>
                            @error('form.status_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl-masuk" class="form-label">Tanggal Masuk Kerja <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="tgl-masuk" wire:model="form.tgl_masuk" required>
                            @error('form.tgl_masuk') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl-keluar" class="form-label">Tanggal Berakhir Kerja <small class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="tgl-keluar" wire:model="form.tgl_keluar" required>
                            @error('form.tgl_keluar') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="entitas" class="form-label">Entitas <small class="text-danger">*</small></label>
                            <select class="form-select" id="entitas" wire:model="form.entitas">
                                <option selected disabled value="">-- Pilih Entitas --</option>
                                    @foreach ($entitas as $key)
                                    <option value="{{ $key->nama }}">{{ $key->nama }}</option>
                                @endforeach
                            </select>
                            @error('form.entitas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="divisi" class="form-label">Divisi <small class="text-danger">*</small></label>
                            <select class="form-select" id="divisi" wire:model="form.divisi">
                                <option selected disabled value="">-- Pilih Divisi --</option>
                                    @foreach ($divisi as $key)
                                    <option value="{{ $key->nama }}">{{ $key->nama }}</option>
                                @endforeach
                            </select>
                            @error('form.divisi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">Jabatan <small class="text-danger">*</small></label>
                            <select class="form-select" id="jabatan" wire:model="form.jabatan">
                                <option selected disabled value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $key)
                                    <option value="{{ $key->nama_jabatan }}">{{ $key->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('form.jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="level" class="form-label">Level <small class="text-danger">*</small></label>
                            <select class="form-select" id="level" wire:model="form.level">
                                <option selected disabled value="">-- Pilih Level --</option>
                                <option value="Manager">Manager</option>
                                <option value="SPV">SPV</option>
                                <option value="Staff">Staff</option>
                            </select>
                            @error('form.level') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
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

                    <h4 style="color: blue; margin-bottom: 20px;">Penggajian</h4>
                    <div class="mb-3">
                        <label for="total-upah" class="form-label">Total Upah <small class="text-danger">*</small></label>
                        <input class="form-control" type="text" id="total-upah" wire:model.lazy="form.total_upah">
                        @error('form.total_upah') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gaji-pokok" class="form-label">Gaji Pokok <small class="text-danger">*</small></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="gaji-pokok">Rp.</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="gaji_pokok"
                                    oninput="formatRupiah(this)"
                                    wire:model.lazy="form.gaji_pokok"
                                    readonly
                                />
                            </div>
                            @error('form.gaji_pokok') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tunjangan-jabatan" class="form-label">Tunjangan Jabatan <small class="text-danger">*</small></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="tunjangan-jabatan">Rp.</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="tunjangan_jabatan"
                                    oninput="formatRupiah(this)"
                                    wire:model.lazy="form.tunjangan_jabatan"
                                    readonly
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
                            <label for="jenis-penggajian" class="form-label">Jenis Penggajian <small class="text-danger">*</small></label>
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
                        <label for="nama-bank" class="form-label">Nama Bank <small class="text-danger">*</small></label>
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
                            <label for="no-rek" class="form-label">Nomor Rekening <small class="text-danger">*</small></label>
                            <input class="form-control" id="no-rek" wire:model="form.no_rek">
                            @error('form.no_rek') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama-pemilik-rekening" class="form-label">Nama Pemilik Rekening <small class="text-danger">*</small></label>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="saveEdit" class="btn btn-primary"
                    wire:loading.attr="disabled" wire:target="saveEdit">
                    <div wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove wire:target="saveEdit">
                        <i class="fa fa-save"></i> Simpan
                    </span>
                    <span wire:loading wire:target="saveEdit">Loading...</span>
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-import" wire:ignore.self tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header" style="color: var(--bs-body-color);">
              <h5 class="modal-title">Import Data Karyawan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="saveImport">
                <div class="modal-body" style="color: var(--bs-body-color);">
                    <div class="container">
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Form Upload</label>
                            <input class="form-control" type="file" id="formFile" wire:model="file" accept=".xlsx, .xls">
                            <div wire:loading wire:target="file" class="text-warning mt-2">
                                Sedang upload file, mohon tunggu...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"
                        wire:loading.attr="disabled">
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                        <span wire:loading>Loading...</span>
                    </button>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>

@push("scripts")
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>
    
@endpush
