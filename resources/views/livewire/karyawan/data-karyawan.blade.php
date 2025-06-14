<div>
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row mt-5">
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
          <button wire:click="showModalImport" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Import</button>
        </div>
        <!-- /.card-header -->
      </div>
      <div class="p-0 table-responsive">
        <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
          <thead>
            <tr class="users-table-info">
              <th>Nama Karyawan</th>
              <th>Jenis Kelamin</th>
              <th>Entitas</th>
              <th>Divisi</th>
              <th>Tanggal Masuk</th>
              <th>Tanggal Keluar (PKWT)</th>
              <th>Status</th>
              <th>Email</th>
              {{-- <th>Password</th> --}}
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($datas as $key)
              <tr>
                <td style="color: var(--bs-body-color);">
                  {{ $key->nama_karyawan }}
                </td>
                <td style="color: var(--bs-body-color);">
                  {{ $key->jenis_kelamin }}
                </td>
                <td style="color: var(--bs-body-color);">{{ $key->entitas }}</td>
                <td style="color: var(--bs-body-color);">{{ $key->divisi }}</td>
                <td style="color: var(--bs-body-color);">{{ $key->tgl_masuk }}</td>
                <td style="color: var(--bs-body-color);">{{ $key->tgl_keluar }}</td>
                <td style="color: var(--bs-body-color);"><span class="badge-success">{{ $key->status_karyawan }}</span></td>
                <td style="color: var(--bs-body-color);">{{ $key->email }}</td>
                {{-- <td style="color: var(--bs-body-color);">password123</td> --}}
                <td class="text-center">
                  <button type="button" wire:click="DetailDataKaryawan({{ $key->id }})" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button>
                  <button type="button" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
</div>

<livewire:karyawan.modal-karyawan />


@push('scripts')
<script>
    Livewire.on('modal-edit-data-karyawan', (event) => {
        $('#modal-edit-data-karyawan').modal(event.action);
    });

    Livewire.on('modal-import', (event) => {
        $('#modal-import').modal(event.action);
    });

    Livewire.on('refresh', () => {
        Livewire.dispatch('refreshTable');
    });
</script>
@endpush
