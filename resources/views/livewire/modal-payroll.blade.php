<div> 
    <div wire:ignore.self class="modal fade" id="modalPayroll" tabindex="-1" aria-labelledby="modalPayrollLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalPayrollLabel">Slip Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex justify-content-end">
                        <div style="width: 250px;">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Cari nama pegawai...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->isEmpty())
                                    <tr>
                                        <td colspan="12" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                @else
                                    @foreach($data as $item)
                                        <tr>
                                            <td>{{ $item->nama_karyawan }}</td>
                                            <td>{{ $item->nip_karyawan }}</td>
                                            <td>
                                                @can('payroll-create')
                                                    <a href="{{ route('create-slip-gaji', ['id' => encrypt($item->id), 'month' => $periode, 'year' => $periode]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-money-bill"></i> Slip Gaji
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalPayrollEks" tabindex="-1" aria-labelledby="modalPayrollEksLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalPayrollEksLabel">Slip Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex justify-content-end">
                        <div style="width: 250px;">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Cari nama pegawai...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dataEks->isEmpty())
                                    <tr>
                                        <td colspan="12" class="text-center">Data tidak ditemukan.</td>
                                    </tr>
                                @else
                                    @foreach($dataEks as $item)
                                        <tr>
                                            <td>{{ $item->nama_karyawan }}</td>
                                            <td>{{ $item->nip_karyawan }}</td>
                                            <td>
                                                @can('payroll-create')
                                                    <a href="{{ route('create-slip-gaji', ['id' => encrypt($item->id)]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-money-bill"></i> Slip Gaji
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>