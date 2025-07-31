<div>
    <div class="modal fade" id="modal-tambah-template" wire:ignore.self tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header" style="color: var(--bs-body-color);">
                <h5 class="modal-title">Tambah Template Mingguan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: var(--bs-body-color);">
                <div class="container">
                    <div class="mb-3">
                        <label for="nama-template" class="form-label">Nama Template</label>
                        <input type="text" class="form-control" id="nama-template" wire:model="form.nama_template" required>
                        @error('form.nama_template') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="minggu" class="form-label">Minggu</label>
                        <select class="form-select" name="minggu" id="minggu" wire:model="form.minggu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.minggu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="senin" class="form-label">Senin</label>
                        <select class="form-select" name="senin" id="senin" wire:model="form.senin">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.senin') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="selasa" class="form-label">Selasa</label>
                        <select class="form-select" name="selasa" id="selasa" wire:model="form.selasa">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.selasa') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rabu" class="form-label">Rabu</label>
                        <select class="form-select" name="rabu" id="rabu" wire:model="form.rabu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.rabu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kamis" class="form-label">Kamis</label>
                        <select class="form-select" name="kamis" id="kamis" wire:model="form.kamis">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.kamis') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jumat" class="form-label">Jum'at</label>
                        <select class="form-select" name="jumat" id="jumat" wire:model="form.jumat">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.jumat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sabtu" class="form-label">Sabtu</label>
                        <select class="form-select" name="sabtu" id="sabtu" wire:model="form.sabtu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.sabtu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" wire:click='store'
                    wire:loading.attr="disabled">
                    <div wire:loading class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                    <span wire:loading>Loading...</span>
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-template" wire:ignore.self tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header" style="color: var(--bs-body-color);">
                <h5 class="modal-title">Edit Template Mingguan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: var(--bs-body-color);">
                <div class="container">
                    <div class="mb-3">
                        <label for="nama-template" class="form-label">Nama Template</label>
                        <input type="text" class="form-control" id="nama-template" wire:model="form.nama_template" required>
                        @error('form.nama_template') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="minggu" class="form-label">Minggu</label>
                        <select class="form-select" name="minggu" id="minggu" wire:model="form.minggu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.minggu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="senin" class="form-label">Senin</label>
                        <select class="form-select" name="senin" id="senin" wire:model="form.senin">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.senin') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="selasa" class="form-label">Selasa</label>
                        <select class="form-select" name="selasa" id="selasa" wire:model="form.selasa">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.selasa') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="rabu" class="form-label">Rabu</label>
                        <select class="form-select" name="rabu" id="rabu" wire:model="form.rabu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.rabu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kamis" class="form-label">Kamis</label>
                        <select class="form-select" name="kamis" id="kamis" wire:model="form.kamis">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.kamis') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jumat" class="form-label">Jum'at</label>
                        <select class="form-select" name="jumat" id="jumat" wire:model="form.jumat">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.jumat') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sabtu" class="form-label">Sabtu</label>
                        <select class="form-select" name="sabtu" id="sabtu" wire:model="form.sabtu">
                            <option value="">-- Pilih Shift --</option>
                            @foreach($jadwalShifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                            @endforeach
                        </select>
                        @error('form.sabtu') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" wire:click='saveEdit'
                    wire:loading.attr="disabled">
                    <div wire:loading class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                    <span wire:loading>Loading...</span>
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
                    Anda yakin ingin menghapus jadwal shift ini?
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
    </script>
    
@endpush
