<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0 mt-5" style="color: var(--bs-body-color);">Daftar Pengajuan</h3></div>
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
        @if (auth()->user()->role == 'user')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-primary" wire:click="showAdd">
                    <i class="bi bi-plus"></i> Tambah
                </button>
            </div>
        @endif
        @if (auth()->user()->role == 'spv' || auth()->user()->role == 'hr')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <select class="form-select" wire:model.lazy="filterPengajuan" style="width: 150px;">
                        <option value="">Pilih Status</option>
                        <option value="0">Menunggu</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                    </select>

                    <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan" wire:model.lazy="filterBulan">

                    {{-- <button class="btn btn-light">Pilih Waktu</button> --}}
                </div>
            </div>
        @endif
        
    
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Diajukan Pada</th>
                        @if (auth()->user()->role != 'user')
                            <th>Nama</th>
                        @endif
                        <th>Pengajuan</th>
                        <th>Keterangan</th>
                        <th>Approve</th>
                        <th>Status</th>
                        @if (auth()->user()->role != 'user')
                            <th class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuans as $key)
                        <tr>
                            <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->created_at }}</td>
                            @if (auth()->user()->role != 'user')
                                <td style="color: var(--bs-body-color);">{{ $key->getUser->name }}</td>
                            @endif
                            <td style="color: var(--bs-body-color);">{{ $key->getShift->nama_shift }}</td>
                            <td style="color: var(--bs-body-color);">{{ $key->keterangan }}</td>
                            <td style="color: var(--bs-body-color);">
                                @if ($key->approve_spv == 1)
                                    <span class="badge bg-success">SPV</span>
                                @elseif ($key->approve_spv == 2)
                                    <span class="badge bg-danger">SPV</span>
                                @endif
                                @if ($key->approve_hr == 1)
                                    <span class="badge bg-success">HRD</span>
                                @endif
                            </td>
                            <td style="color: var(--bs-body-color);">
                                {{-- @if ($key->approve_spv == 1 && $key->approve_hr == 1)
                                    <span class="badge bg-success">Diterima</span>
                                @endif --}}
                                @if ($key->status == 0)
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif ($key->status == 1)
                                    <span class="badge bg-success">Diterima</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            @if (auth()->user()->role != 'user')
                                <td class="text-center" style="color: var(--bs-body-color);">
                                    {{-- <button class="btn btn-sm btn-info mb-2" wire:click="showDetail('{{ Crypt::encrypt($key->id) }}')"><i class="fa-solid fa-eye"></i></button> --}}
                                    @if (auth()->user()->role == 'spv')
                                        @if ($key->approve_spv == 0)
                                            <button class="btn btn-sm btn-success text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">Terima</button>
                                            <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }}, 2)">Tolak</button>
                                        @endif
                                    @endif
                                    @if (auth()->user()->role == 'hr')
                                        @if ($key->approve_hr == 0)
                                            <button class="btn btn-sm btn-success text-white mb-2" wire:click="updateStatus({{ $key->id }}, 1)">Terima</button>
                                            {{-- <button class="btn btn-sm btn-danger text-white mb-2" wire:click="updateStatus({{ $key->id }})">Tolak</button> --}}
                                        @endif
                                    @endif
                                    @if (auth()->user()->role == 'admin')
                                        @if ($key->status == 0)
                                            <button class="btn btn-sm btn-danger mb-2" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"><i class="fa fa-trash"></i></button>
                                        @endif
                                    @endif
                                </td>
                            @endif
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

        Livewire.on('modalDetailPengajuan', (event) => {
            $('#modalDetailPengajuan').modal(event.action);
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