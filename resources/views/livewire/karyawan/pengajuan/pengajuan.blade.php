<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Daftar Pengajuan</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan</li>
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
        </div>
    
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Diajukan Pada</th>
                        <th>Nama</th>
                        <th>Pengajuan</th>
                        <th>Keterangan</th>
                        <th>Approve</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuans as $key)
                        <tr>
                            <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->created_at }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->getKaryawan->nama_karyawan }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->getShift->nama_shift }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->keterangan }}</td>
                            <td style="color: var(--bs-body-color);">-</td>
                            <td style="color: var(--bs-body-color);">
                                @if ($key->status == 0)
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif ($key->status == 1)
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-center" style="color: var(--bs-body-color);">
                                @if ($key->status == 0)
                                    <button class="btn btn-sm btn-success text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">Terima</button>
                                    <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">Tolak</button>
                                @endif
                                {{-- <button class="btn btn-sm btn-warning" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-pen-to-square"></i></button> --}}
                                <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <livewire:karyawan.pengajuan.modal-pengajuan />
</div>

@push('scripts')
    <script>
        Livewire.on('modalTambahPengajuan', (event) => {
            $('#modalTambahPengajuan').modal(event.action);
        });

        // Livewire.on('modalEditJadwal', (event) => {
        //     $('#modalEditJadwal').modal(event.action);
        // });

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