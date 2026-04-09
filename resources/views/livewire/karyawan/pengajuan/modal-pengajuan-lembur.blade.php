<div>
    <div wire:ignore.self class="modal fade" id="modalTambahPengajuanLembur" tabindex="-1"
        aria-labelledby="modalTambahPengajuanLemburLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahPengajuanLabel">Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal <small
                                class="text-danger">*</small></label>
                        <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                        @error('form.tanggal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Lembur <small class="text-danger">*</small></label>
                        <select class="form-select" wire:model="form.jenis">
                            <option value="">-- Pilih Jenis Lembur --</option>
                            <option value="1">Hari Biasa</option>
                            <option value="2">Hari Libur</option>
                        </select>
                        @error('form.jenis')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rentang Waktu</label>
                        <div class="row">
                            <div class="col">
                                <label for="waktu_mulai" class="form-label">Jam Mulai <small
                                        class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_mulai">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 0; $i < 48; $i++)
                                        @php
                                            $hour = floor($i / 2);
                                            $minute = $i % 2 == 0 ? '00' : '30';
                                            $value = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . $minute;

                                            // Label: jika 00:00 ditampilkan sebagai 24:00
                                            $label = $value === '00:00' ? '24:00' : $value;
                                        @endphp
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endfor
                                </select>
                                @error('form.waktu_mulai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="waktu_akhir" class="form-label">Jam Selesai <small
                                        class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_akhir">
                                    <option value="">Pilih Jam</option>
                                    @for ($i = 0; $i < 48; $i++)
                                        @php
                                            $hour = floor($i / 2);
                                            $minute = $i % 2 == 0 ? '00' : '30';
                                            $value = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . $minute;

                                            $label = $value === '00:00' ? '24:00' : $value;
                                        @endphp
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endfor
                                </select>
                                @error('form.waktu_akhir')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="total_jam" class="form-label fw-semibold">Jumlah Jam Lembur</label>
                        <input type="text" id="total_jam" class="form-control" wire:model="form.total_jam" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" id="keterangan" placeholder="Layanan Helpdesk"
                            wire:model="form.keterangan">
                        @error('form.keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Bukti <span class="text-danger">*</span></label>

                        <label for="file_bukti"
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

                            <input type="file" class="d-none" id="file_bukti" wire:model="file_bukti"
                                accept=".jpg,.jpeg,.png">

                            @if ($file_bukti && is_object($file_bukti))
                                {{-- Preview file yang dipilih --}}
                                <img src="{{ $file_bukti->temporaryUrl() }}" alt="Preview"
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

                        @error('file_bukti')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100 w-md-auto" wire:click='store'
                        wire:loading.attr="disabled" wire:target="store">
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

    <div wire:ignore.self class="modal fade" id="modalEditPengajuanLembur" tabindex="-1"
        aria-labelledby="modalEditPengajuanLemburLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditPengajuanLemburLabel">Pengajuan Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label fw-semibold">Tanggal <small
                                class="text-danger">*</small></label>
                        <input type="date" class="form-control" id="tanggal" wire:model="form.tanggal">
                        @error('form.tanggal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Lembur <small
                                class="text-danger">*</small></label>
                        <select class="form-select" wire:model="form.jenis">
                            <option value="">-- Pilih Jenis Lembur --</option>
                            <option value="1">Hari Biasa</option>
                            <option value="2">Hari Libur</option>
                        </select>
                        @error('form.jenis')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rentang Waktu</label>
                        <div class="row">
                            <div class="col">
                                <label for="waktu_mulai" class="form-label">Jam Mulai <small
                                        class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_mulai">
                                    <option value="">Pilih Jam</option>
                                    @php
                                        $start = \Carbon\Carbon::createFromTime(0, 0);
                                        $end = \Carbon\Carbon::createFromTime(23, 30);
                                    @endphp
                                    @while ($start <= $end)
                                        <option value="{{ $start->format('H:i') }}">{{ $start->format('H:i') }}
                                        </option>
                                        @php $start->addMinutes(30); @endphp
                                    @endwhile
                                </select>
                                @error('form.waktu_mulai')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="waktu_akhir" class="form-label">Jam Selesai <small
                                        class="text-danger">*</small></label>
                                <select class="form-control" wire:model.lazy="form.waktu_akhir">
                                    <option value="">Pilih Jam</option>
                                    @php
                                        $start = \Carbon\Carbon::createFromTime(0, 0);
                                        $end = \Carbon\Carbon::createFromTime(23, 30);
                                    @endphp
                                    @while ($start <= $end)
                                        <option value="{{ $start->format('H:i') }}">{{ $start->format('H:i') }}
                                        </option>
                                        @php $start->addMinutes(30); @endphp
                                    @endwhile
                                </select>
                                @error('form.waktu_akhir')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="total_jam" class="form-label fw-semibold">Jumlah Jam Lembur</label>
                        <input type="text" id="total_jam" class="form-control" wire:model="form.total_jam"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan <small
                                class="text-danger">*</small></label>
                        <input type="text" class="form-control" id="keterangan" placeholder="Layanan Helpdesk"
                            wire:model="form.keterangan">
                        @error('form.keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Bukti <span class="text-danger">*</span></label>

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

                            <input type="file" class="d-none" id="file" wire:model="file_bukti"
                                accept=".jpg,.jpeg,.png">

                            {{-- @if ($file_bukti && is_object($file_bukti))
                                <img src="{{ $file_bukti->temporaryUrl() }}" alt="Preview"
                                    style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                            @elseif ($existingFile)
                                <img src="{{ Storage::disk('s3')->temporaryUrl($existingFile, now()->addMinutes(30)) }}"
                                    alt="File saat ini"
                                    style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                            @else
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🖼️</div>
                                <p class="mb-1 fw-semibold text-secondary">Klik atau drag file ke sini</p>
                                <p class="mb-0 text-muted small">JPG, JPEG, PNG — maks. 2MB</p>
                            @endif --}}

                            @if ($file_bukti && is_object($file_bukti))
                                {{-- Preview file baru --}}
                                <img src="{{ $file_bukti->temporaryUrl() }}" alt="Preview"
                                    style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                            @elseif ($existingFile)
                                {{-- Preview file lama dari public storage --}}
                                <img src="{{ asset('storage/' . $existingFile) }}" alt="File saat ini"
                                    style="max-height: 180px; max-width: 100%; border-radius: 8px; object-fit: cover;">
                                <p class="mt-2 mb-0 text-muted small">Klik untuk ganti file</p>
                            @else
                                {{-- Belum ada file --}}
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🖼️</div>
                                <p class="mb-1 fw-semibold text-secondary">Klik atau drag file ke sini</p>
                                <p class="mb-0 text-muted small">JPG, JPEG, PNG — maks. 2MB</p>
                            @endif
                        </label>

                        {{-- Tombol remove --}}
                        @if (($file_bukti && is_object($file_bukti)) || $existingFile)
                            <button type="button" wire:click="removeFile"
                                style="
                                        margin-top: 8px;
                                        padding: 4px 12px;
                                        font-size: 12px;
                                        border-radius: 8px;
                                        border: 1px solid #fca5a5;
                                        background: #fff1f2;
                                        color: #dc2626;
                                        cursor: pointer;
                                    ">
                                🗑️ Hapus file
                            </button>
                        @endif

                        @if (session()->has('error'))
                            <small class="text-danger">{{ session('error') }}</small>
                        @endif

                        @error('file_bukti')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100 w-md-auto" wire:click='saveEdit'
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
