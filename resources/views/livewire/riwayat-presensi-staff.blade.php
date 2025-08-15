<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Riwayat Presensi Staff</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Riwayat Presensi Staff</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card shadow-sm p-4 rounded" style="background-color: var(--bs-body-bg);">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label>Show <select class="form-select form-select-sm d-inline-block w-auto">
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                            </select> entries per page</label>
                    </div>
                    <div>
                        <input type="search" class="form-control form-control-sm" placeholder="Search..." wire:model.live="search">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Karyawan</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Approve</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($datas->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center" style="color: var(--bs-body-color);">Data tidak ditemukan</td>
                                </tr>
                            @else
                                @foreach($datas as $key)
                                    <tr>
                                        <td style="color: var(--bs-body-color);">{{ $key->tanggal }}</td>
                                        @if (auth()->user()->current_role != 'user')
                                            <td style="color: var(--bs-body-color);">{{ $key->getUser->nama_karyawan }}</td>
                                        @endif
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_in }}</td>
                                        <td style="color: var(--bs-body-color);">{{ $key->clock_out }}</td>
                                        <td style="color: var(--bs-body-color);">
                                            <img src="{{ asset('storage/' . $key->file) }}" style="max-width: 100px;" alt="Selfie" class="img-fluid" />
                                        </td>
                                        <td>
                                            @if ($key->status == "0")
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @elseif ($key->status == "1")
                                                <span class="badge bg-danger">Terlambat</span>
                                            @elseif ($key->status == "2")
                                                <span class="badge bg-primary">Dispensasi</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        
                                            
                                        <td>
                                            @if ($key->lokasi_lock == 0)
                                                @if ($key->approve == 0)
                                                    <button class="btn btn-success btn-sm mt-2 mb-2" wire:click="approve({{ $key->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm" wire:click="reject({{ $key->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif ($key->approve == 1)
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($key->approve == 2)
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $datas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    Livewire.on('editModal', (event) => {
        $('#editModal').modal(event.action);
    });

  Livewire.on('swal', (e) => {
      Swal.fire(e.params);
  });
</script>
    
@endpush