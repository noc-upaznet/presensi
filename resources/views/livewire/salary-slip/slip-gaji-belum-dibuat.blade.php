<div>
    @if ($showModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block;" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Daftar Slip Gaji Belum Dibuat</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex justify-content-end">
                        <div style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Cari nama atau bulan..." wire:model.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Bulan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slipGajiList as $item)
                                <tr>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->periode }}</td>
                                    <td><span class="badge bg-danger">Belum Dibuat</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="createSlipGaji({{ $item->id }})">
                                            Buat Slip Gaji
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Backdrop --}}
    <div class="modal-backdrop fade show"></div>
    @endif
</div>