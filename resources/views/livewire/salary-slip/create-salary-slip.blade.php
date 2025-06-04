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
                        <form>
                            <div class="mb-3">
                                <label for="employee" class="form-label fw-semibold">Karyawan</label>
                                <select id="employee" class="form-select">
                                    <option value="">Pilih Karyawan</option>
                                    <option value="1">John Doe</option>
                                    <option value="2">Jane Smith</option>
                                    <option value="3">Alice Johnson</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="month" class="form-label fw-semibold">Bulan&Tahun</label>
                                <input type="month" id="month" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="nip" class="form-label fw-semibold">NPK/NIP</label>
                                <input type="text" class="form-control" placeholder="Contoh: 241120901" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="departemen" class="form-label fw-semibold">Departemen</label>
                                <div class="input-group">
                                    <select class="form-select" id="departemen" disabled>
                                        <option value="">Pilih Departemen</option>
                                        <option value="1">IT</option>
                                        <option value="2">HR</option>
                                        <option value="3">Finance</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="gaji_pokok" class="form-label fw-semibold">Gaji Pokok</label>
                                <input type="number" id="gaji_pokok" class="form-control" placeholder="Contoh: 5000000" disabled>
                            </div>
                            <div class="mb-3 mt-4">
                                <label for="tunjangan" class="form-label fw-semibold">Tunjangan</label>
                                <div id="tunjangan-list">
                                    <div class="input-group mb-2 tunjangan-item">
                                        <div class="row w-100">
                                            <div class="col-md-6">
                                                <select name="tunjangan[]" class="form-select">
                                                    <option value="">Pilih Tunjangan</option>
                                                    @if(!empty($jenis_tunjangan) && $jenis_tunjangan->count())
                                                    @foreach($jenis_tunjangan as $tunjangan)
                                                    <option value="{{ $tunjangan->id }}">{{ $tunjangan->nama_tunjangan
                                                        }}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">Data tidak tersedia</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"
                                                        style="pointer-events: none;">Rp</span>
                                                    <input type="number" name="tunjangan_nominal[]" class="form-control"
                                                        placeholder="Nominal">
                                                    <button type="button" class="btn btn-danger btn-remove-tunjangan"
                                                        style="margin-left:0;"
                                                        onclick="removeTunjangan(this)">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm mb-3 mt-2" onclick="addTunjangan()">
                                    <i class="bi bi-plus"></i> Tambah Tunjangan
                                </button>

                                @push('scripts')
                                <script>
                                    function addTunjangan() {
                                        const container = document.getElementById('tunjangan-list');
                                        const item = document.createElement('div');
                                        item.className = 'input-group mb-2 tunjangan-item';
                                        item.innerHTML = `
                                            <div class="row w-100">
                                                <div class="col-md-6">
                                                    <select name="tunjangan[]" class="form-select">
                                                        <option value="">Pilih Tunjangan</option>
                                                        @if(!empty($jenis_tunjangan) && $jenis_tunjangan->count())
                                                            @foreach($jenis_tunjangan as $tunjangan)
                                                                <option value="{{ $tunjangan->id }}">{{ $tunjangan->nama_tunjangan }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="">Data tidak tersedia</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text" style="pointer-events: none;">Rp</span>
                                                        <input type="number" name="tunjangan_nominal[]" class="form-control" placeholder="Nominal">
                                                        <button type="button" class="btn btn-danger btn-remove-tunjangan" style="margin-left:0;" onclick="removeTunjangan(this)">Hapus</button>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                        container.appendChild(item);
                                    }
                                    function removeTunjangan(btn) {
                                        btn.closest('.tunjangan-item').remove();
                                    }
                                </script>
                                @endpush
                            </div>

                            <div class="mb-3">
                                <label for="potongan" class="form-label fw-semibold">Potongan</label>
                                <div id="potongan-list">
                                    <div class="input-group mb-2 potongan-item">
                                        <div class="row w-100">
                                            <div class="col-md-6">
                                                <select name="potongan[]" class="form-select">
                                                    <option value="">Pilih Potongan</option>
                                                    @if(!empty($jenis_potongan) && $jenis_potongan->count())
                                                    @foreach($jenis_potongan as $potongan)
                                                    <option value="{{ $potongan->id }}">{{ $potongan->nama_potongan
                                                        }}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">Data tidak tersedia</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-text"
                                                        style="pointer-events: none;">Rp</span>
                                                    <input type="number" name="potongan_nominal[]" class="form-control"
                                                        placeholder="Nominal">
                                                    <button type="button" class="btn btn-danger btn-remove-tunjangan"
                                                        style="margin-left:0;" onclick="remove(this)">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm mb-3 mt-2" onclick="addPotongan()">
                                    <i class="bi bi-plus"></i> Tambah Potongan</button>

                                @push('scripts')
                                <script>
                                    function addPotongan() {
                                        const container = document.getElementById('potongan-list');
                                        const item = document.createElement('div');
                                        item.className = 'input-group mb-2 potongan-item';
                                        item.innerHTML = `
                                            <div class="row w-100">
                                                <div class="col-md-6">
                                                    <select name="potongan[]" class="form-select">
                                                        <option value="">Pilih Potongan</option>
                                                        @if(!empty($jenis_potongan) && $jenis_potongan->count())
                                                        @foreach($jenis_potongan as $potongan)
                                                        <option value="{{ $potongan->id }}">{{ $potongan->nama_potongan }}</option>
                                                        @endforeach
                                                        @else
                                                        <option value="">Data tidak tersedia</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text" style="pointer-events: none;">Rp</span>
                                                        <input type="number" name="potongan_nominal[]" class="form-control" placeholder="Nominal">
                                                        <button type="button" class="btn btn-danger btn-remove-potongan" style="margin-left:0;" onclick="removePotongan(this)">Hapus</button>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                        container.appendChild(item);
                                    }

                                    function removePotongan(btn) {
                                        btn.closest('.potongan-item').remove();
                                    }
                                </script>
                                @endpush
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="terlambat" class="form-label fw-semibold">Terlambat</label>
                                    <input type="number" id="terlambat" class="form-control" placeholder="Contoh: 2">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="izin" class="form-label fw-semibold">Izin</label>
                                    <input type="number" id="izin" class="form-control" placeholder="Contoh: 1">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="cuti" class="form-label fw-semibold">Cuti</label>
                                    <input type="number" id="cuti" class="form-control" placeholder="Contoh: 0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="kehadiran" class="form-label fw-semibold">Kehadiran</label>
                                    <input type="number" id="kehadiran" class="form-control" placeholder="Contoh: 25">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lembur" class="form-label fw-semibold">Lembur</label>
                                    <input type="number" id="lembur" class="form-control" placeholder="Contoh: 0">
                                </div>
                            </div>
                            <div class="footer text-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>