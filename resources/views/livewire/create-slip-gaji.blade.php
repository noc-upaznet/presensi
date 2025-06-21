<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Create Slip Gaji</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Slip Gaji</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Form Slip Gaji</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="no_slip" class="form-label fw-semibold">No. Slip</label>
                            <input type="text" id="no_slip" class="form-control @error('no_slip') is-invalid @enderror" wire:model="no_slip" readonly>
                            @error('no_slip') 
                                <small class="text-danger">{{ $message }}</small> 
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="month" class="form-label fw-semibold">Bulan & Tahun</label>
                            <input type="month" id="month" class="form-control @error('bulanTahun') is-invalid @enderror" wire:model="bulanTahun">
                            @error('bulanTahun') 
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee" class="form-label fw-semibold">Karyawan</label>
                            <select id="employee" class="form-select" wire:model="user_id" wire:change="loadDataKaryawan">
                                <option value="">Pilih Karyawan</option>
                                @foreach($karyawan as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_karyawan }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nip" class="form-label fw-semibold">NPK/NIP</label>
                            <input type="text" class="form-control" disabled wire:model="nip_karyawan">
                        </div>
                        <div class="mb-3">
                            <label for="departemen" class="form-label fw-semibold">Departemen</label>
                            <div class="input-group">
                                <input type="text" class="form-control" disabled wire:model="divisi">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                            <input type="text" id="gaji_pokok" class="form-control" disabled wire:model="gaji_pokok">
                        </div>

                        <div class="mb-3">
                            <label for="tunjangan_jabatan" class="form-label fw-semibold">Tunjangan Jabatan</label>
                            <input type="text" id="tunjangan_jabatan" class="form-control" disabled wire:model="tunjangan_jabatan">
                        </div>

                        <div class="mb-3">
                            <label for="lembur_nominal" class="form-label fw-semibold">Lembur</label>
                            <input type="text" id="lembur_nominal" class="form-control" disabled wire:model="lembur_nominal">
                        </div>

                        <div class="mb-3">
                            <label for="izin_nominal" class="form-label fw-semibold">Potongan Izin</label>
                            <input type="text" id="izin_nominal" class="form-control" disabled wire:model="izin_nominal">
                        </div>

                        <div class="mb-3">
                            <label for="terlambat_nominal" class="form-label fw-semibold">Potongan Terlambat</label>
                            <input type="text" id="terlambat_nominal" class="form-control" disabled wire:model="terlambat_nominal">
                        </div>

                        <div class="mb-3">
                            <label for="potongan" class="form-label fw-semibold">Tunjangan</label>
                            @foreach ($tunjangan as $index => $item)
                                <div class="input-group mb-2">
                                    <select name="tunjangan[]" class="form-select" wire:model="tunjangan.{{ $index }}.nama">
                                        <option value="">-- Pilih Tunjangan --</option>
                                        @if(!empty($jenis_tunjangan) && $jenis_tunjangan->count())
                                            @foreach($jenis_tunjangan as $tunjangan)
                                                @if (!in_array($tunjangan->id, $tunjangan_terpilih ?? []))
                                                    <option value="{{ $tunjangan->nama_tunjangan }}">{{ $tunjangan->nama_tunjangan }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="">Data tidak tersedia</option>
                                        @endif
                                    </select>
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" wire:model.lazy="tunjangan.{{ $index }}.nominal">
                                    <button type="button" class="btn btn-danger" wire:click="removeTunjangan({{ $index }})">Hapus</button>
                                </div>
                            @endforeach
                            <button type="button" class="btn btn-success mb-2" wire:click="addTunjangan">+ Tambah Tunjangan</button>
                        </div>

                        <div class="mb-3">
                            <label for="potongan" class="form-label fw-semibold">Potongan</label>
                            @foreach ($potongan as $index => $item)
                                <div class="input-group mb-2">
                                    <select name="potongan[]" class="form-select" wire:model="potongan.{{ $index }}.nama">
                                        <option value="">-- Pilih Potongan --</option>
                                        @if(!empty($jenis_potongan) && $jenis_potongan->count())
                                            @foreach($jenis_potongan as $potongan)
                                                @if (!in_array($potongan->id, $potongan_terpilih ?? []))
                                                    <option value="{{ $potongan->nama_potongan }}">{{ $potongan->nama_potongan }}</option>
                                                @endif
                                            @endforeach
                                        @else
                                            <option value="">Data tidak tersedia</option>
                                        @endif
                                    </select>
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" wire:model.lazy="potongan.{{ $index }}.nominal">
                                    <button type="button" class="btn btn-danger" wire:click="removePotongan({{ $index }})">Hapus</button>
                                </div>
                            @endforeach
                            <button type="button" class="btn btn-success mb-2" wire:click="addPotongan">+ Tambah Potongan</button>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" wire:model.lazy="bpjs_digunakan" class="form-check-input" id="bpjsCheckbox">
                            <label class="form-check-label" for="bpjsCheckbox">Gunakan BPJS Kesehatan</label>
                        </div>

                        @if($bpjs_digunakan)
                            <div class="row mt-2 mb-2">
                                <div class="col-md-6">
                                    <label>Persentase BPJS Kesehatan (%)</label>
                                    <input type="number" class="form-control" wire:model="persentase_bpjs">
                                </div>
                                <div class="col-md-6">
                                    <label>Nominal BPJS</label>
                                    <input type="text" class="form-control" readonly value="{{ number_format($bpjs_nominal, 0, ',', '.') }}">
                                </div>
                            </div>
                        @endif

                        <div class="form-check">
                            <input type="checkbox" wire:model.lazy="bpjs_jht_digunakan" class="form-check-input" id="bpjsJhtCheckbox">
                            <label class="form-check-label" for="bpjsJhtCheckbox">Gunakan BPJS JHT</label>
                        </div>

                        @if($bpjs_jht_digunakan)
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Persentase BPJS JHT (%)</label>
                                    <input type="number" class="form-control" wire:model="persentase_bpjs_jht">
                                </div>
                                <div class="col-md-6">
                                    <label>Nominal BPJS JHT</label>
                                    <input type="text" class="form-control" readonly value="{{ number_format($bpjs_jht_nominal, 0, ',', '.') }}">
                                </div>
                            </div>
                        @endif
                        
                        <div class="row mt-3">
                            <div class="col-md-2 mb-3">
                                <label for="kehadiran" class="form-label fw-semibold">Kehadiran (Hari)</label>
                                <input type="text" class="form-control" value="{{ $rekap['kehadiran'] }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="terlambat" class="form-label fw-semibold">Terlambat (Hari)</label>
                                <input type="text" class="form-control" value="{{ $rekap['terlambat'] }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="izin" class="form-label fw-semibold">Izin (Hari)</label>
                                <input type="text" class="form-control" value="{{ $rekap['izin'] }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="cuti" class="form-label fw-semibold">Cuti (Hari)</label>
                                <input type="text" class="form-control" value="{{ $rekap['cuti'] }}" readonly>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="lembur" class="form-label fw-semibold">Lembur (Jam)</label>
                                <input type="text" class="form-control" value="{{ $rekap['lembur'] }}" wire:model="lembur" readonly>
                            </div>
                        </div>
                        <div class="row">
                            
                        </div>  

                        <div class="mb-3">
                            <label>Total Gaji</label>
                            <input type="text" class="form-control" value="Rp {{ number_format($total_gaji, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="footer text-end">
                            <button type="button" wire:click="store" class="btn btn-primary"
                                wire:loading.attr="disabled">
                                <div wire:loading class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                                <span wire:loading>Loading...</span>
                            </button>
                            <a href="{{ route('payroll') }}" class="btn btn-secondary">Kembali</a>
                        </div>
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