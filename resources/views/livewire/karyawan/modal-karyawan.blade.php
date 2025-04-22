<div>
    <div class="modal fade" id="modal-edit-data-karyawan" tabindex="-1">
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
                            <label for="name" class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" id="name" wire:model.defer="name" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tanggal Evaluasi</label>
                            <input type="date" class="form-control" id="name" wire:model.defer="name" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Entitas</label>
                            <select class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">-- Pilih Entitas --</option>
                                <option value="DJB">DJB</option>
                                <option value="UNR">UNR</option>
                                <option value="UNB">UNB</option>
                                <option value="MC">MC</option>
                            </select>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Status Karyawan</label>
                            <select class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">-- Pilih Status Karyawan --</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Resign">Resign</option>
                            </select>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Level Posisi</label>
                            <input class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Tanggal Tidak Aktif</label>
                            <input type="date" class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Jabatan</label>
                            <input class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Keterangan Tidak Aktif</label>
                            <textarea class="form-control" id="validationCustom04" required></textarea>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Divisi</label>
                            <select class="form-select" id="validationCustom04" required>
                                <option selected disabled value="">-- Pilih Divisi --</option>
                                <option value="Finance">Finance</option>
                                <option value="HRD">HRD</option>
                                <option value="NOC">NOC</option>
                                <option value="Teknisi">Teknisi</option>
                            </select>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Tanggal Bergabung</label>
                            <input type="date" class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Password</label>
                            <input class="form-control" id="validationCustom04" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
</div>
