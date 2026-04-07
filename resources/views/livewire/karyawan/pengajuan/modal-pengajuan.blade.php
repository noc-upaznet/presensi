<div>
    <div wire:ignore.self class="modal fade" id="modalTambahPengajuan" tabindex="-1"
        aria-labelledby="modalTambahPengajuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pengajuan <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="form.pengajuan">
                                <option value="">-- Pilih Pengajuan --</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                @endforeach
                            </select>
                            @error('form.pengajuan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal <small
                                    class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                            @error('form.tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <select class="form-select" id="waktu">
                                <option value="full">Sehari Penuh</option>
                                <option value="half">Setengah Hari</option>
                            </select>
                        </div> --}}

                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan <small
                                    class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="keterangan"
                                placeholder="Contoh: Demam Tinggi" wire:model="form.keterangan">
                            @error('form.keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">File Bukti</label>

                            <label for="file"
                                style="
                                    display: block;
                                    border: 2px dashed #cbd5e1;
                                    border-radius: 12px;
                                    padding: 1.5rem;
                                    text-align: center;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                    background: #f8fafc;
                                "
                                onmouseover="this.style.borderColor='#6366f1';this.style.background='#f5f3ff'"
                                onmouseout="this.style.borderColor='#cbd5e1';this.style.background='#f8fafc'">

                                <input type="file" class="d-none" id="file" wire:model="file"
                                    accept=".jpg,.jpeg,.png">

                                @if ($file && is_object($file))
                                    {{-- Preview file yang dipilih --}}
                                    <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                                        style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                    <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                                @else
                                    {{-- Belum ada file --}}
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🖼️</div>
                                    <p class="mb-1 fw-semibold text-secondary">Klik atau drag file ke sini</p>
                                    <p class="mb-0 text-muted small">JPG, JPEG, PNG — maks. 2MB</p>
                                @endif
                            </label>

                            @if (session()->has('error'))
                                <small class="text-danger">{{ session('error') }}</small>
                            @else
                                <small class="text-muted">Ukuran maksimal file: 2MB</small>
                            @endif

                            @error('file')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary  w-100 w-md-auto" wire:click='store'
                            wire:loading.attr="disabled" wire:target="store">
                            <div wire:loading wire:target="store" class="spinner-border spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="store"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="store">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-100 w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditPengajuan" tabindex="-1"
        aria-labelledby="modalEditPengajuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="modalEditPengajuanLabel">Edit Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <form>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pengajuan <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="form.pengajuan">
                                <option value="">-- Pilih Pengajuan --</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                @endforeach
                            </select>
                            @error('form.pengajuan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal <small
                                    class="text-danger">*</small></label>
                            <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                            @error('form.tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
                            <label for="waktu" class="form-label">Waktu</label>
                            <select class="form-select" id="waktu">
                                <option value="full">Sehari Penuh</option>
                                <option value="half">Setengah Hari</option>
                            </select>
                        </div> --}}

                        <div class="mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan <small
                                    class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="keterangan"
                                placeholder="Contoh: Demam Tinggi" wire:model="form.keterangan">
                            @error('form.keterangan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">File Bukti</label>

                            <label for="file"
                                style="
                                        display: block;
                                        border: 2px dashed #cbd5e1;
                                        border-radius: 12px;
                                        padding: 1.5rem;
                                        text-align: center;
                                        cursor: pointer;
                                        transition: all 0.2s;
                                        background: #f8fafc;
                                    "
                                onmouseover="this.style.borderColor='#6366f1';this.style.background='#f5f3ff'"
                                onmouseout="this.style.borderColor='#cbd5e1';this.style.background='#f8fafc'">

                                <input type="file" class="d-none" id="file" wire:model="file"
                                    accept=".jpg,.jpeg,.png">

                                @if ($file && is_object($file))
                                    {{-- Preview file baru --}}
                                    <img src="{{ $file->temporaryUrl() }}" alt="Preview"
                                        style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                    <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                                @elseif ($existingFile)
                                    {{-- Preview file lama dari S3 --}}
                                    <img src="{{ Storage::disk('s3')->temporaryUrl($existingFile, now()->addMinutes(30)) }}"
                                        alt="File saat ini"
                                        style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                    <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                                @else
                                    {{-- Belum ada file --}}
                                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🖼️</div>
                                    <p class="mb-1 fw-semibold text-secondary">Klik atau drag file ke sini</p>
                                    <p class="mb-0 text-muted small">JPG, JPEG, PNG — maks. 2MB</p>
                                @endif
                            </label>

                            @if (session()->has('error'))
                                <small class="text-danger">{{ session('error') }}</small>
                            @endif

                            @error('file')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary  w-100 w-md-auto" wire:click='saveEdit'
                            wire:loading.attr="disabled" wire:target="saveEdit">
                            <div wire:loading wire:target="saveEdit" class="spinner-border spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveEdit"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="saveEdit">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-100 w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pengajuan Izin Pending -->
    <div wire:ignore.self class="modal fade" id="modalDetailPengajuan" tabindex="-1"
        aria-labelledby="modalDetailPengajuanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content px-3 py-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Pengajuan Izin/Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="./assets/img/user4-128x128.jpg" alt="Foto Pegawai" class="rounded-circle"
                            width="80" height="80">
                        <h5 class="mt-3 mb-0 fw-bold">{{ $detail->getUser->name ?? '-' }}</h5>
                        <small class="text-muted">Admin Human Resources</small>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10">
                            <div class="row mb-3 align-items-center">
                                <div class="col fw-bold">Informasi Permohonan</div>
                                <div class="col-auto">
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Nama Pengajuan</div>
                                <div class="col-7 fw-semibold">Izin Sakit</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Jenis</div>
                                <div class="col-7 fw-semibold">Sehari Penuh</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Tanggal Cuti</div>
                                <div class="col-7 fw-semibold">13 - 17 Mar 2025</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-5 text-muted">Lama Izin/Cuti</div>
                                <div class="col-7 fw-semibold">2 Hari</div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-5 text-muted">Tanggal Permohonan</div>
                                <div class="col-7 fw-semibold">13 Mar 2025</div>
                            </div>

                            <div class="position-relative ps-4 ms-1">
                                <div class="timeline-dot bg-dark"></div>
                                <div class="fw-semibold">Menunggu Persetujuan dari Amin Syukron</div>
                                <div class="timeline-line"></div>
                            </div>

                            <div class="position-relative ps-4 ms-1 mt-3">
                                <div class="timeline-dot bg-secondary"></div>
                                <div class="text-muted">Requested</div>
                                <div class="text-muted ms-4">13 Maret 2025 07:20:31 WIB</div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary w-100 w-md-auto"
                        data-bs-dismiss="modal">Tutup</button>
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

@push('scripts')
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        Livewire.on('refresh', () => {
            Livewire.dispatch('refreshTable');
        });
    </script>
@endpush
