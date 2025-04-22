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
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('jadwal-shift') }}">Jadwal Shift Karyawan</a></li>
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
            <div class="p-4 border rounded-4">
                <div class="col-md-6">
                    <div class="mb-3" style="color: var(--bs-body-color);">
                        <label for="alamatKTP" class="form-label">Nama Shift</label>
                        <input class="form-control" id="validationCustom04" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3" style="color: var(--bs-body-color);">
                        <label for="alamatKTP" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="validationCustom04" required></textarea>
                    </div>
                </div>
            </div>
            <div class="container mt-3">
                <div class="row mb-3" style="color: var(--bs-body-color);">
                    <div class="col-md-4">
                        <span>Nama Shift</span>
                    </div>
                    <div class="col-md-4">
                        <span>Jam Masuk</span>
                    </div>
                    <div class="col-md-4">
                        <span>Jam Pulang</span>
                    </div>
                </div>
                <div class="shift-container">
                    <div class="shift-item mb-3 d-flex align-items-center">
                        <input type="text" class="form-control me-2" name="shift_name[]" placeholder="Nama Shift">
                        <input type="time" class="form-control me-2" name="jam_masuk[]">
                        <span style="color: var(--bs-body-color)">-</span>
                        <input type="time" class="form-control mx-2" name="jam_pulang[]">
                        <button type="button" class="btn btn-danger btn-hapus"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
                <button id="tambah-jadwal" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Tambah Jadwal
                </button>
            </div>
            <div class="container mt-4">
                <a href="{{ route('jadwal-shift') }}"><button type="button" class="btn btn-secondary"><i class="fas fa-undo"></i> Kembali</button></a>
                <button type="button" class="btn btn-primary"><i class="far fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('tambah-jadwal').addEventListener('click', function () {
        const container = document.querySelector('.shift-container');

        const template = `
        <div class="shift-item mb-3 d-flex align-items-center">
            <input type="text" class="form-control me-2" name="shift_name[]" placeholder="Nama Shift">
            <input type="time" class="form-control me-2" name="jam_masuk[]">
            <span>-</span>
            <input type="time" class="form-control mx-2" name="jam_pulang[]">
            <button type="button" class="btn btn-danger btn-hapus"><i class="fas fa-trash-alt"></i></button>
        </div>`;

        container.insertAdjacentHTML('beforeend', template);
    });

    // Event untuk Hapus
    document.querySelector('.shift-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endpush