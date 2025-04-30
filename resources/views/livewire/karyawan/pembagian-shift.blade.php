<div>
    <div class="app-content-header">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Pembagian Shift</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pembagian Shift</li>
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
        <div class="mt-4 mb-4">
          <a href="{{ route('tambah-pembagian-shift') }}"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button></a>
          <!-- /.card-header -->
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
            <thead>
                <tr>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th style="width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($datas as $key)
              <tr>
                  <td style="color: var(--bs-body-color);">{{ $key->nama_shift }}</td>
                  <td style="color: var(--bs-body-color);">{{ \Carbon\Carbon::parse($key->jam_masuk)->format('H:i') }}</td>
                  <td style="color: var(--bs-body-color);">{{ \Carbon\Carbon::parse($key->jam_pulang)->format('H:i') }}</td>
                  <td>
                      <button class="btn btn-warning btn-sm" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                      <button class="btn btn-danger btn-sm" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"><i class="fa fa-trash"></i></button>
                  </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
    </div>
    <livewire:karyawan.modal-pembagian-shift />
</div>

@push('scripts')
<script>
  Livewire.on('modal-edit-shift', (event) => {
      $('#modal-edit-shift').modal(event.action);
  });
  Livewire.on('modal-confirm-delete', (event) => {
      $('#modal-confirm-delete').modal(event.action);
      $('#btn-confirm-delete').attr('wire:click', 'delete("' + event.id + '")');
      $('#modal-confirm-delete').modal('hide');
  });

  Livewire.on('refresh', () => {
      Livewire.dispatch('refreshTable');
  });

  Livewire.on('swal', (e) => {
      Swal.fire(e.params);
  });
</script>
@endpush