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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama-karyawan" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="nama-karyawan" wire:model="form.nama_karyawan" required>
                            @error('form.nama_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" wire:model="form.email" required>
                            @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="entitas" class="form-label">Entitas</label>
                            <select class="form-select" id="entitas" wire:model="form.entitas">
                                <option selected disabled value="">-- Pilih Entitas --</option>
                                <option value="DJB">DJB</option>
                                <option value="UNR">UNR</option>
                                <option value="UNB">UNB</option>
                                <option value="MC">MC</option>
                            </select>
                            @error('form.entitas') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status-karyawan" class="form-label">Status Karyawan</label>
                            <select class="form-select" id="status-karyawan" name="status_karyawan" wire:model="form.status_karyawan" >
                                <option selected disabled value="">-- Pilih Status Karyawan --</option>
                                <option value="Pegawai Kontrak(PKWT)">Pegawai Kontrak(PKWT)</option>
                                <option value="Pegawai Tetap">Pegawai Tetap</option>
                            </select>
                            @error('form.status_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl-masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tgl-masuk" wire:model="form.tgl_masuk" >
                            @error('form.tgl_masuk') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl-keluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tgl-keluar" wire:model="form.tgl_keluar" >
                            @error('form.tgl_keluar') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="divisi" class="form-label">Divisi</label>
                            <select class="form-select" id="divisi" wire:model="form.divisi">
                                <option selected disabled value="">-- Pilih Divisi --</option>
                                <option value="Finance">Finance</option>
                                <option value="HRD">HRD</option>
                                <option value="NOC">NOC</option>
                                <option value="Teknisi">Teknisi</option>
                            </select>
                            @error('form.divisi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-select" id="jabatan" wire:model="form.jabatan">
                                <option selected disabled value="">-- Pilih Jabatan --</option>
                                <option value="Billing">Billing</option>
                                <option value="UNR">UNR</option>
                                <option value="UNB">UNB</option>
                                <option value="MC">MC</option>
                            </select>
                            @error('form.jabatan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <textarea class="form-control" id="alamatKTP" wire:model="form.alamatKTP"></textarea>
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
                            <textarea class="form-control" id="alamatDomisili" name="alamatDomisili"
                                    wire:model="form.alamatDomisili"
                                    :readonly="$wire.form.gunakanAlamatKTP"></textarea>
                            @error('form.alamatDomisili') <span class="text-danger">{{ $message }}</span> @enderror
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
                            @error('form.divisi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="spv" class="form-label">SPV</label>
                            <input class="form-control" id="spv" wire:model="form.spv">
                            @error('form.spv') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" wire:click='saveEdit'
                    wire:loading.attr="disabled">
                    <div wire:loading class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading>Loading...</span>
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
