<div>
    <div wire:ignore.self class="modal fade" id="modalTambahPengajuanLembur" tabindex="-1" aria-labelledby="modalTambahPengajuanLemburLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal <small class="text-danger">*</small></label>
                        <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                        @error('form.tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Lembur <small class="text-danger">*</small></label>
                        <select class="form-select" wire:model="form.jenis">
                            <option value="">-- Pilih Jenis Lembur --</option>
                            <option value="1">Hari Biasa</option>
                            <option value="2">Hari Libur</option>
                        </select>
                        @error('form.jenis') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rentang Waktu</label>
                        <div class="row">
                            <div class="col">
                                <label for="waktu_mulai" class="form-label">Jam Mulai <small class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_mulai">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                        </option>
                                    @endfor
                                </select>
                                @error('form.waktu_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                                <label for="waktu_akhir" class="form-label">Jam Selesai <small class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_akhir">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                        </option>
                                    @endfor
                                </select>
                                @error('form.waktu_akhir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="total_jam" class="form-label fw-semibold">Jumlah Jam Lembur</label>
                        <input type="text" id="total_jam" class="form-control" wire:model="form.total_jam" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan <small class="text-danger">*</small></label>
                        <input type="text" class="form-control" id="keterangan" placeholder="Layanan Helpdesk" wire:model="form.keterangan">
                        @error('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">File Bukti</label>
                        <input type="file" class="form-control" id="file" wire:model="file_bukti">
                        @error('file_bukti') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100 w-md-auto" wire:click='store' wire:loading.attr="disabled" wire:target="store">
                        <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove wire:target="store"><i class="fa fa-save"></i> Simpan</span>
                        <span wire:loading wire:target="store">Loading...</span>
                    </button>
                    <button type="button" class="btn btn-secondary w-100 w-md-auto"
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditPengajuanLembur" tabindex="-1" aria-labelledby="modalEditPengajuanLemburLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditPengajuanLemburLabel">Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal <small class="text-danger">*</small></label>
                        <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                        @error('form.tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Lembur <small class="text-danger">*</small></label>
                        <select class="form-select" wire:model="form.jenis">
                            <option value="">-- Pilih Jenis Lembur --</option>
                            <option value="1">Hari Biasa</option>
                            <option value="2">Hari Libur</option>
                        </select>
                        @error('form.jenis') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rentang Waktu</label>
                        <div class="row">
                            <div class="col">
                                <label for="waktu_mulai" class="form-label">Jam Mulai <small class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_mulai">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                        </option>
                                    @endfor
                                </select>
                                @error('form.waktu_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                                <label for="waktu_akhir" class="form-label">Jam Selesai <small class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_akhir">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00
                                        </option>
                                    @endfor
                                </select>
                                @error('form.waktu_akhir') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="total_jam" class="form-label fw-semibold">Jumlah Jam Lembur</label>
                        <input type="text" id="total_jam" class="form-control" wire:model="form.total_jam" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan <small class="text-danger">*</small></label>
                        <input type="text" class="form-control" id="keterangan" placeholder="Layanan Helpdesk" wire:model="form.keterangan">
                        @error('form.keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">File Bukti</label>
                        <input type="file" class="form-control" id="file" wire:model="file_bukti">
                        @error('file_bukti') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100 w-md-auto" wire:click='saveEdit' wire:loading.attr="disabled" wire:target="saveEdit">
                        <div wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove wire:target="saveEdit"><i class="fa fa-save"></i> Simpan</span>
                        <span wire:loading wire:target="saveEdit">Loading...</span>
                    </button>
                    <button type="button" class="btn btn-secondary w-100 w-md-auto"
                        data-bs-dismiss="modal">Cancel</button>
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