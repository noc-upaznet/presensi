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
                            <input wire:model.debounce.300ms="search" type="text" class="form-control"
                                placeholder="Cari nama pegawai...">
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
                                        <a href="{{ route('create-slip-gaji', ['id' => $item->id]) }}"
                                            class="btn btn-sm btn-primary">
                                            Buat Slip Gaji
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Backdrop --}}
    <div class="modal-backdrop fade show"></div>
    @endif
</div>