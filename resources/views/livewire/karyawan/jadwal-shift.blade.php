<div>
    <div class="app-content-header">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Jadwal Shift Karyawan</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Jadwal Shift Karyawan</li>
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
            <a href="{{ route('tambah-jadwal-shift') }}"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button></a>
          </div>
          <!-- /.card-header -->
        </div>
        <div class="p-0">
          <table class="table table-striped" style="background-color: var(--bs-body-bg);">
            <thead>
              <tr class="users-table-info">
                <th>Shift</th>
                <th>Jam Kerja</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="color: var(--bs-body-color);">UNR</td>
                <td style="color: var(--bs-body-color);">Finance</td>
                
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