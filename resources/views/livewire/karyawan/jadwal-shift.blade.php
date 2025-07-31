<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6 mt-5"><h3 class="mb-0" style="color: var(--bs-body-color);">Jadwal Shift Karyawan</h3></div>
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
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" wire:click="showAdd">
                <i class="bi bi-plus"></i> Tambah
            </button>
    
            <div class="d-flex gap-2">
                <select class="form-select" style="width: 150px;" wire:model="filterKaryawan" wire:change="filterByKaryawan($event.target.value)">
                    <option value="" selected>Pilih Karyawan</option>
                    @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                    @endforeach
                </select>
    
                <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan" wire:model.lazy="filterBulan">
    
                <button class="btn btn-light" disabled>Pilih Waktu</button>
            </div>
        </div>
    
        <div class="table-responsive">
            <table class="table table-striped table-hover" style="background-color: var(--bs-body-bg);">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Nama Karyawan</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $key)
                        <tr wire:key="jadwal-{{ $key->id }}">
                            <td style="color: var(--bs-body-color);">{{ $key->bulan_tahun }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->getKaryawan->nama_karyawan }}</td>
                            <td class="text-center" style="color: var(--bs-body-color);">
                                <button class="btn btn-sm btn-info text-white" wire:click="showDetail('{{ Crypt::encrypt($key->id) }}')"><i class="fa fa-eye"></i></button>
                                <button class="btn btn-sm btn-warning" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-sm btn-danger" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $jadwals->links() }}
        </div>
    </div>

    <livewire:karyawan.modal-jadwal-shift />

</div>

@push('scripts')
    <script>
        Livewire.on('modalTambahJadwal', (event) => {
            $('#modalTambahJadwal').modal(event.action);

            setTimeout(() => {
                $('#bulanPicker').datepicker({
                    format: "yyyy-mm",
                    startView: "months",
                    minViewMode: "months",
                    autoclose: true
                });
            }, 300);
        });

        Livewire.on('modalEditJadwal', (event) => {
            $('#modalEditJadwal').modal(event.action);
        });

        Livewire.on('modalDetailJadwal', (event) => {
            $('#modalDetailJadwal').modal(event.action);
        });

        Livewire.on('modal-confirm-delete', (event) => {
            $('#modal-confirm-delete').modal(event.action);
            $('#btn-confirm-delete').attr('wire:click', 'delete("' + event.id + '")');
            $('#modal-confirm-delete').modal('hide');
        });

        Livewire.on('refresh', () => {
            Livewire.dispatch('refreshTable');
        });
    </script>
@endpush