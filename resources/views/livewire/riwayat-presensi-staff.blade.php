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
                <div class="d-flex justify-content-end gap-2 flex-wrap mb-4">
                    <select class="form-select" wire:model.lazy="filterkaryawan" style="width: 150px;">
                        <option value="">Pilih Karyawan</option>
                        @foreach ($karyawanList as $karyawan)
                            <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select>

                    <select class="form-select" wire:model.lazy="filterStatus" style="width: 150px;">
                        <option value="">Pilih Status</option>
                        <option value="0">Tepat Waktu</option>
                        <option value="1">Terlambat</option>
                        <option value="2">Dispensasi</option>
                    </select>

                    <input type="month" class="form-control" style="width: 150px;" placeholder="Bulan" wire:model.lazy="filterBulan">

                    <input type="date" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Tanggal" wire:model.lazy="filterTanggal">
                </div>
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
                @php
                    $divisi = auth()->user()->karyawan->divisi ?? '-';
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Karyawan</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Lokasi</th>
                                <th>File</th>
                                <th>Status</th>
                                @role('spv-sales')
                                    <th>Approve</th>
                                @endrole
                                {{-- @if( (auth()->user()->hasRole('spv') && $divisi === 'Teknisi') 
                                    || auth()->user()->hasRole('hr')) --}}
                                @hasanyrole('spv-teknisi|hr')
                                    <th>Dispensasi</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($datas as $key)
                                <tr>
                                    <td>{{ $key->tanggal }}</td>

                                    <td>{{ $key->getUser->nama_karyawan }}</td>

                                    <td>{{ $key->clock_in }}</td>
                                    <td>{{ $key->clock_out }}</td>
                                    <td>
                                        <span>Clock-In   :</span> <span class="badge bg-primary"> {{ $key->lokasi_final}}</span><br>
                                        <span>Clock-Out  :</span> <span class="badge bg-danger">{{ $key->lokasi_clock_out }}</span>
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/'.$key->file) }}" style="max-width:100px" class="img-fluid" alt="Selfie">
                                    </td>
                                    <td>
                                        @switch($key->status)
                                            @case("0") <span class="badge bg-success">Tepat Waktu</span> @break
                                            @case("1") <span class="badge bg-danger">Terlambat</span> @break
                                            @case("2") <span class="badge bg-primary">Dispensasi</span> @break
                                            @default <span class="badge bg-secondary">Unknown</span>
                                        @endswitch
                                    </td>

                                    {{-- Approve untuk SPV --}}
                                    @role('spv-sales')
                                        <td>
                                            @if($key->lokasi_lock == 0)
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
                                    @endrole

                                    {{-- Dispensasi SPV Teknisi --}}
                                    @role('spv-teknisi')
                                        <td>
                                            @if ($key->status == "1")
                                                @if(is_null($key->approve_late_spv))
                                                    <button class="btn btn-primary btn-sm mt-2 mb-2" wire:click="approvePresensi({{ $key->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @elseif($key->approve_late_spv == 1)
                                                    <span class="badge bg-success">SPV APPROVED</span>
                                                @endif
                                                @if($key->approve_late_hr == 1)
                                                    <span class="badge bg-success">HR APPROVED</span>
                                                @endif
                                            @elseif($key->status == "2")
                                                <span class="badge bg-primary">Dispensasi Approved</span>
                                            @endif
                                        </td>
                                    @endrole

                                    {{-- Dispensasi HR --}}
                                    @role('hr')
                                        <td>
                                            @if ($key->status == "1")
                                                @if($key->approve_late_spv == 1)
                                                    <span class="badge bg-success">SPV APPROVED</span>
                                                @endif
                                                @if(is_null($key->approve_late_hr))
                                                    <button class="btn btn-primary btn-sm mt-2 mb-2" wire:click="approvePresensi({{ $key->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @elseif($key->approve_late_hr == 1)
                                                    <span class="badge bg-success">HR APPROVED</span>
                                                @endif
                                            @elseif($key->status == "2")
                                                <span class="badge bg-primary">Dispensasi Approved</span>
                                            @endif
                                        </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Data tidak ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $datas->links() }}
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