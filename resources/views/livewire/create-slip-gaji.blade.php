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
                            <label for="month" class="form-label fw-semibold">
                                Periode: 
                                {{ $cutoffStart->translatedFormat('d M Y') }} - {{ $cutoffEnd->translatedFormat('d M Y') }}
                            </label>
                            <input type="month" id="month" class="form-control @error('bulanTahun') is-invalid @enderror" 
                                wire:model.lazy="bulanTahun">
                            @error('bulanTahun') 
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="employee" class="form-label fw-semibold">Karyawan</label>
                            <select id="employee" class="form-select" wire:model.lazy="user_id">
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
                            <label for="jabatan" class="form-label fw-semibold">Jabatan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" disabled wire:model="jabatan">
                            </div>
                        </div>
                        @if ($this->isSalesPosition() || $this->isCollectorPosition())
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="jumlah-psb" class="form-label fw-semibold">Jumlah PSB</label>
                                    <input type="number" wire:model.lazy="jml_psb" class="form-control" id="jumlah-psb" placeholder="Masukkan Jml. PSB sales">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="insentif" class="form-label fw-semibold">Insentif</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="insentif" class="form-control" disabled wire:model="insentif">
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($this->isSalesPositionSpv())
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="jumlah-psb-spv" class="form-label fw-semibold">Jumlah PSB</label>
                                    <input type="number" wire:model.lazy="jml_psb_spv" class="form-control" id="jumlah-psb-spv" placeholder="Masukkan Jml. PSB sales">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="insentif-spv" class="form-label fw-semibold">Insentif</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="insentif-spv" class="form-control" disabled wire:model="insentif_spv">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="gaji_pokok" class="form-control" disabled wire:model="gaji_pokok">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tunjangan_jabatan" class="form-label fw-semibold">Tunjangan Jabatan</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="text" id="tunjangan_jabatan" class="form-control" disabled wire:model="tunjangan_jabatan">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="lembur_nominal" class="form-label fw-semibold">
                                    Lembur Hari Biasa
                                </label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="lembur_nominal" class="form-control" disabled wire:model="lembur_nominal">
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lemburLibur_nominal" class="form-label fw-semibold">
                                    Lembur Hari Libur
                                </label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="lemburLibur_nominal" class="form-control" disabled wire:model="lemburLibur_nominal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label fw-semibold">Daftar Lembur Hari Biasa</label>
                                <ul class="list-group">
                                    @foreach($listLemburBiasa as $l)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($l['tanggal'])->format('d-m-Y') }}<br>
                                                <strong>Waktu:</strong> {{ $l['waktu_mulai'] }} - {{ $l['waktu_akhir'] }}
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ $l['jam'] }} jam</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mb-3 col-6">
                                <label class="form-label fw-semibold">Daftar Lembur Hari Libur</label>
                                <ul class="list-group">
                                    @foreach($listLemburLibur as $l)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($l['tanggal'])->format('d-m-Y') }}<br>
                                                <strong>Waktu:</strong> {{ $l['waktu_mulai'] }} - {{ $l['waktu_akhir'] }}
                                            </div>
                                            <span class="badge bg-success rounded-pill">{{ $l['jam'] }} jam</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="inovation_reward" class="form-label fw-semibold">Inovation Reward</label>
                            <div class="input-group mb-2">
                                <input type="number" name="inovation_reward" class="form-control" wire:model.lazy="inovation_reward" placeholder="Nominal">
                                <span class="input-group-text">X</span>
                                <input type="number" class="form-control" wire:model.lazy="inovation_reward_jumlah" placeholder="Jumlah">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="inovation_reward" class="form-label fw-semibold">Uang Transport</label>
                                <div class="input-group mb-2">
                                    {{-- <input type="number" name="transport" class="form-control" wire:model.lazy="transport" placeholder="Nominal"> --}}
                                    <select name="transport" id="transport" class="form-select" wire:model.lazy="transport">
                                        <option value="">Nominal</option>
                                        <option value="5000">5.000</option>
                                        <option value="10000">10.000</option>
                                        <option value="15000">15.000</option>
                                        <option value="25000">25.000</option>
                                    </select>
                                    <span class="input-group-text">X</span>
                                    <input type="number" class="form-control" wire:model.lazy="transport_jumlah" placeholder="Jumlah">
                                </div>

                                {{-- Menampilkan total hasil perkalian --}}
                                <div class="fw-bold">
                                    Total: {{ number_format($transport_total, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="potongan" class="form-label fw-semibold">Uang Makan</label>
                                <div class="input-group mb-2">
                                    <input type="number" name="uang_makan" class="form-control" wire:model.lazy="uang_makan" placeholder="Nominal">
                                    <span class="input-group-text">X</span>
                                    <input type="number" class="form-control" wire:model.lazy="uang_makan_jumlah" placeholder="Jumlah">
                                </div>

                                {{-- Menampilkan total hasil perkalian --}}
                                <div class="fw-bold">
                                    Total: {{ number_format($uang_makan_total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="potongan" class="form-label fw-semibold">Tunjangan Kebudayaan</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <select name="kebudayaan" class="form-select" wire:model.lazy="kebudayaan">
                                        <option value="">-- Pilih Nominal --</option>
                                        <option value="100000">100000</option>
                                        <option value="200000">200000</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="potongan" class="form-label fw-semibold">Bonus Fee Sharing</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <select name="fee_sharing" class="form-select" wire:model.lazy="fee_sharing">
                                        <option value="">-- Pilih Nominal --</option>
                                        <option value="100000">100000</option>
                                        <option value="200000">200000</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="potongan" class="form-label fw-semibold">Tunjangan</label>
                            @foreach ($tunjangan as $index => $item)
                                <div class="input-group mb-2">
                                    <select name="tunjangan[]" class="form-select" wire:model.lazy="tunjangan.{{ $index }}.nama">
                                        <option value="">-- Pilih Tunjangan --</option>
                                        @if(!empty($jenis_tunjangan) && $jenis_tunjangan->count())
                                            @foreach($jenis_tunjangan as $t)
                                                @if (!in_array($t->id, $tunjangan_terpilih ?? []))
                                                    <option value="{{ $t->nama_tunjangan }}">{{ $t->nama_tunjangan }}</option>
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
                                    <select name="potongan[]" class="form-select" wire:model.lazy="potongan.{{ $index }}.nama">
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

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="izin_nominal" class="form-label fw-semibold">Potongan Izin</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="izin_nominal" class="form-control" disabled wire:model="izin_nominal">
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="terlambat_nominal" class="form-label fw-semibold">Potongan Terlambat</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="terlambat_nominal" class="form-control" disabled wire:model="terlambat_nominal">
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" wire:model.lazy="bpjs_digunakan" class="form-check-input" id="bpjsCheckbox">
                            <label class="form-check-label" for="bpjsCheckbox">BPJS Kesehatan</label>
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
                            <label class="form-check-label" for="bpjsJhtCheckbox">BPJS JHT</label>
                        </div>

                        @if($bpjs_jht_digunakan)
                            <div class="row mt-2 mb-2">
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
                        {{-- BPJS Dibayarkan Perusahaan --}}
                        <div class="form-check">
                            <input type="checkbox" wire:model.lazy="bpjs_perusahaan_digunakan" class="form-check-input" id="bpjsPCheckbox">
                            <label class="form-check-label" for="bpjsPCheckbox">BPJS Kesehatan (Perusahaan)</label>
                        </div>

                        @if($bpjs_perusahaan_digunakan)
                            <div class="row mt-2 mb-2">
                                <div class="col-md-6">
                                    <label>Persentase BPJS Kesehatan (%)</label>
                                    <input type="number" class="form-control" wire:model="persentase_bpjs_perusahaan">
                                </div>
                                <div class="col-md-6">
                                    <label>Nominal BPJS (Perusahaan)</label>
                                    <input type="text" class="form-control" readonly value="{{ number_format($bpjs_perusahaan_nominal, 0, ',', '.') }}">
                                </div>
                            </div>
                        @endif

                        <div class="form-check">
                            <input type="checkbox" wire:model.lazy="bpjs_jht_perusahaan_digunakan" class="form-check-input" id="bpjsJhtPCheckbox">
                            <label class="form-check-label" for="bpjsJhtPCheckbox">BPJS JHT (Perusahaan)</label>
                        </div>

                        @if($bpjs_jht_perusahaan_digunakan)
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Persentase BPJS JHT (%)</label>
                                    <input type="number" class="form-control" wire:model="persentase_bpjs_jht_perusahaan">
                                </div>
                                <div class="col-md-6">
                                    <label>Nominal BPJS JHT (Perusahaan)</label>
                                    <input type="text" class="form-control" readonly value="{{ number_format($bpjs_jht_perusahaan_nominal, 0, ',', '.') }}">
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
                                <input type="text" class="form-control" value="{{ ($rekap['izin'] ?? 0) + 0.5 * ($rekap['izin setengah hari'] ?? 0) }}" readonly>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="cuti" class="form-label fw-semibold">Cuti (Hari)</label>
                                <input type="text" class="form-control" value="{{ $rekap['cuti'] }}" readonly>
                            </div>
                            
                            <div class="col-md-2 mb-3">
                                <label for="lembur" class="form-label fw-semibold">Lembur (Jam)</label>
                                <input type="text" class="form-control" value="{{ $rekap['lembur'] }}" readonly>
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
                                wire:loading.attr="disabled" wire:target="store">
                                <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span wire:loading.remove wire:target="store">
                                    <i class="fa fa-save"></i> Simpan
                                </span>
                                <span wire:loading wire:target="store">Loading...</span>
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