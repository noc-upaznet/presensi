<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Pembagian Shift Karyawan</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('pembagian-shift') }}">Pembagian Shift</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembagian Shift Karyawan</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="app-content mt-4">
        <!--begin::Container-->
        <div class="container-fluid">
            {{-- <div class="p-4 border rounded-4">
                <div class="col-md-6">
                    <div class="mb-3" style="color: var(--bs-body-color);">
                        <label for="nama-shift" class="form-label">Nama Shift</label>
                        <input class="form-control" id="nama-shift" name="nama_shift" wire:model="form.nama_shift">
                        @error('form.nama_shift') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3" style="color: var(--bs-body-color);">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" wire:model="form.deskripsi"></textarea>
                        @error('form.deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div> --}}
            <div class="container mt-3">
                <div>
                    <div class="row fw-semibold text-white mb-2">
                        <div class="col">Shift</div>
                        <div class="col">Jam Masuk</div>
                        <div class="col">Jam Pulang</div>
                        <div class="col"></div>
                    </div>
                
                    @foreach ($jadwals as $index => $jadwal)
                        <div class="row mb-2 align-items-center">
                            <div class="col">
                                {{-- <select class="form-select" wire:model="jadwals.{{ $index }}.nama_shift">
                                    <option value="">-- Pilih Shift --</option>
                                    <option value="07:00-15:00">07:00-15:00</option>
                                    <option value="Setengah Hari">Setengah Hari</option>
                                    <option value="Sore">Sore</option>
                                    <option value="Malam">Malam</option>
                                </select> --}}
                                <input type="text" class="form-control" wire:model="jadwals.{{ $index }}.nama_shift" placeholder="00.00-00.00">
                            </div>
                            <div class="col">
                                <input type="time" class="form-control" wire:model="jadwals.{{ $index }}.jam_masuk">
                            </div>
                            <div class="col">
                                <input type="time" class="form-control" wire:model="jadwals.{{ $index }}.jam_pulang">
                            </div>
                            <div class="col">
                                <button class="btn btn-danger" wire:click.prevent="hapusJadwal({{ $index }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                
                    <button class="btn btn-primary mt-3" wire:click.prevent="tambahJadwal">
                        <i class="bi bi-plus"></i> Tambah Jadwal
                    </button>
                </div>
            </div>
            <div class="container mt-4">
                <a href="{{ route('pembagian-shift') }}"><button type="button" class="btn btn-secondary"><i class="fas fa-undo"></i> Kembali</button></a>
                <button type="button" wire:click="store" class="btn btn-primary"
                    wire:loading.attr="disabled">
                    <div wire:loading class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                    <span wire:loading>Loading...</span>
                </button>
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