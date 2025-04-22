<div>
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Data Karyawan</h3></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
          </ol>
        </div>
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content Header-->
  <!--begin::App Content-->
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
      <div class="mb-4">
        <div class="card-header">
          <a href="{{ route('tambah-data-karyawan') }}"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button></a>
          <button class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Import</button>
        </div>
        <!-- /.card-header -->
      </div>
      <div class="p-0">
        <table class="table table-striped" style="background-color: var(--bs-body-bg);">
          <thead>
            <tr class="users-table-info">
              <th>
                <label class="users-table__checkbox ms-20">
                  Karyawan
                </label>
              </th>
              <th>Jenis Kelamin</th>
              <th>Entitas</th>
              <th>Divisi</th>
              <th>Tanggal Masuk</th>
              <th>Tanggal Keluar (PKWT)</th>
              <th>Status</th>
              <th>Email</th>
              <th>Password</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="color: var(--bs-body-color);">
                <label class="users-table__checkbox">
                  <div class="categories-table-img">
                    <div class="row">
                      <div class="col-md-6">
                        <picture><source srcset="assets/img/categories/01.webp" type="image/webp"><img src="./img/categories/01.jpg" alt="category"></picture>
                      </div>
                      <div class="col-md-6">
                        John Doe
                      </div>
                    </div>
                  </div>
                </label>
              </td>
              <td style="color: var(--bs-body-color);">
                Laki-laki
              </td>
              <td style="color: var(--bs-body-color);">UNR</td>
              <td style="color: var(--bs-body-color);">Finance</td>
              <td style="color: var(--bs-body-color);">17-04-2021</td>
              <td style="color: var(--bs-body-color);">17-04-2022</td>
              <td style="color: var(--bs-body-color);"><span class="badge-success">Aktif</span></td>
              <td style="color: var(--bs-body-color);">johndoe@gmail.com</td>
              <td style="color: var(--bs-body-color);">password123</td>
              <td>
                <a href="{{ route('detail-data-karyawan') }}"><button type="button" class="btn btn-primary" style="font-size: 10px;"><i class="fa-solid fa-eye"></i></button></a>
                <button type="button" wire:click="showEdit" class="btn btn-warning" style="font-size: 10px;"><i class="fa-solid fa-pen"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  </div>
</div>

<livewire:karyawan.modal-karyawan />


@push('scripts')
<script>
    Livewire.on('modal-edit-data-karyawan', (event) => {
        const modalElement = document.getElementById('modal-edit-data-karyawan');
        const modal = new bootstrap.Modal(modalElement);
        
        if (event.action === 'show') {
            modal.show();
        } else {
            modal.hide();
        }
    });
</script>
@endpush
