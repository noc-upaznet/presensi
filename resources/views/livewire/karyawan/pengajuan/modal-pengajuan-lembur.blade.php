<div>
    <div wire:ignore.self class="modal fade" id="modalTambahPengajuanLembur" tabindex="-1" aria-labelledby="modalTambahPengajuanLemburLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header border-bottom" style="color: var(--bs-body-color);">
              <h5 class="modal-title text-primary fw-bold" id="modalTambahPengajuanLemburLabel">Tambah Pengajuan Lembur</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="color: var(--bs-body-color);">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tanggal Pengajuan</label>
                        <input type="date" id="tanggal" wire:model="form.tanggal" class="form-control">
                        @error('form.tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Karyawan</label>
                        <select class="form-select" wire:model="form.nama_karyawan">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                            @endforeach
                        </select>
                        @error('form.nama_karyawan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Waktu Mulai</label>
                        <input class="form-control" type="time" wire:model="form.waktu_mulai">
                        @error('form.waktu_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Waktu Akhir</label>
                        <input class="form-control" type="time" wire:model="form.waktu_akhir">
                        @error('form.waktu_akhir">') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input class="form-control" wire:model="form.keterangan" name="keterangan" id="keterangan">
                        @error('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3" style="background-color: var(--bs-body-bg);">
                        <label class="form-label fw-semibold">Upload Bukti Lembur (Opsional)</label>
                        <input class="form-control" type="file" wire:model="file_bukti" name="file_bukti" style="color: var(--bs-body-color);">
                        @error('file_bukti') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" wire:click='store' wire:loading.attr="disabled" wire:target="store">
                    <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove wire:target="store"><i class="fa fa-save"></i> Simpan</span>
                    <span wire:loading wire:target="store">Loading...</span>
                </button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm-delete" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete"
                        wire:loading.attr="disabled">
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Ya, Hapus
                    </button>
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

        Livewire.on('refresh', () => {
            Livewire.dispatch('refreshTable');
        });
    </script>
    
@endpush