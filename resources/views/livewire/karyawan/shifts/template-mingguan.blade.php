<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Template Mingguan</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Template Mingguan</li>
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
          <div class="mb-4 mt-4">
            <button class="btn btn-primary" wire:click="showAdd"><i class="fa-solid fa-plus"></i> Tambah</button>
            <!-- /.card-header -->
          </div>
          <div class="p-0">
            <table class="table table-striped table-bordered" style="background-color: var(--bs-body-bg);">
              <thead>
                <tr class="users-table-info">
                  <th>Nama Template</th>
                  <th>Minggu</th>
                  <th>Senin</th>
                  <th>Selasa</th>
                  <th>Rabu</th>
                  <th>Kamis</th>
                  <th>Jum'at</th>
                  <th>Sabtu</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $key)
                    <tr>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->nama_template }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                          {{ $key->getMinggu->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                          {{ $key->getSenin->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->getSelasa->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->getRabu->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->getkamis->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->getJumat->nama_shift }}
                        </td>
                        <td style="color: var(--bs-body-color);">
                            {{ $key->getSabtu->nama_shift }}
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
      <livewire:karyawan.shifts.modal-template />
</div>

@push('scripts')
<script>
  Livewire.on('modal-tambah-template', (event) => {
      const modalElement = document.getElementById('modal-tambah-template');
      const modal = new bootstrap.Modal(modalElement);
      
      if (event.action === 'show') {
          modal.show();
      } else {
          modal.hide();
      }
  });

  Livewire.on('closeModal', () => {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modal-tambah-template'));
      modal.hide();
  });

  Livewire.on('modal-edit-template', (event) => {
      const modalElement = document.getElementById('modal-edit-template');
      const modal = new bootstrap.Modal(modalElement);
      
      if (event.action === 'show') {
          modal.show();
      } else {
          modal.hide();
      }
  });
  Livewire.on('closeModal', () => {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modal-edit-template'));
      modal.hide();
  });

  Livewire.on('refresh', () => {
      Livewire.dispatch('refreshTable');
  });
</script>
@endpush


