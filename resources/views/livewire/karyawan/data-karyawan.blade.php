<div>
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6 mt-5"><h3 class="mb-0" style="color: var(--bs-body-color);">Data Karyawan</h3></div>
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
          @can('karyawan-create')
            <a href="{{ route('tambah-data-karyawan') }}"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button></a>
            <button wire:click="showModalImport" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Import</button>
          @endcan
        </div>
        <!-- /.card-header -->
      </div>
      <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <label>Show 
                  <select class="form-select form-select-sm d-inline-block w-auto" wire:model.lazy="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                  </select> 
                  entries per page
                </label>
            </div>
            <div>
                <input type="text" class="form-control form-control-sm rounded-end-0" placeholder="Search" wire:model.live="search">
            </div>
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if ($datas->isEmpty())
                  <tr>
                    <td colspan="9" class="text-center">Data Not Found</td>
                  </tr>
                @endif
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
                    <td class="text-center">
                      <button type="button" wire:click="DetailDataKaryawan('{{ Crypt::encrypt($key->id) }}')" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></button>
                      <button type="button" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                      <button type="button" wire:click="confirmHapusKaryawan('{{ Crypt::encrypt($key->id) }}')" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-3">
            {{ $datas->links() }}
          </div>
        </div>
      </div>
  </div>
    <!-- Modal Hapus Payroll -->
    <div wire:ignore.self class="modal fade" id="hapusKaryawanModal" tabindex="-1"
        aria-labelledby="hapusKaryawanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 0.375rem; border-top: 4px solid #dc3545; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="hapusKaryawanModalLabel">Hapus Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data karyawan ini?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="delete" style="border-radius: 8px;"
                        data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
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

    Livewire.on('hapusKaryawanModal', (event) => {
          $('#hapusKaryawanModal').modal(event.action);
      });

    Livewire.on('refresh', () => {
        Livewire.dispatch('refreshTable');
    });
</script>
@endpush
