<div>
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <h3 class="mb-3">Gaji {{ $currentEntitas }}</h3>
                <table class="table table-bordered mb-0 align-middle mb-3" style="font-size: 14px;">

                    <thead>
                        <tr style="background-color: #b8cce4;">
                            <th class="text-center fw-bold px-3 py-2" style="width: 50px;">NO</th>
                            <th class="text-center fw-bold px-3 py-2">INDIKATOR</th>
                            @foreach ($months as $month)
                                <th class="text-center fw-bold px-3 py-2">{{ $month }}</th>
                            @endforeach
                            <th class="text-center fw-bold px-3 py-2">TOTAL</th>
                            @can('note-edit')
                                <th class="text-center fw-bold px-3 py-2" style="width: 100px;">Action
                                </th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>

                        {{-- ================= AUTO ROW ================= --}}
                        @foreach ($indicators as $index => $row)
                            <tr>
                                <td class="text-center px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2">{{ $row['label'] }}</td>

                                @foreach ($months as $month)
                                    <td class="text-end px-3 py-2">
                                        Rp
                                        {{ number_format($row['values'][$month] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td
                                    class="text-end px-3 py-2 fw-bold {{ ($row['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                    {{ ($row['total'] ?? 0) < 0 ? '-' : '' }}
                                    Rp{{ number_format(abs($row['total'] ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach


                        {{-- ================= BIAYA TAMBAHAN NON TITIP ================= --}}
                        <tr>
                            <td class="text-center px-3 py-2">{{ count($indicators) + 1 }}</td>
                            <td class="px-3 py-2">Biaya Tambahan Baru</td>

                            @foreach ($months as $month)
                                <td class="text-end px-3 py-2">

                                    {{-- VIEW --}}
                                    @if (!$editMode['non_titip']['biaya_tambahan'])
                                        <span>
                                            Rp{{ number_format($staticRows['non_titip']['biaya_tambahan']['value_' . $month] ?? 0, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    {{-- EDIT --}}
                                    @if ($editMode['non_titip']['biaya_tambahan'])
                                        <input type="number" class="form-control form-control-sm text-end"
                                            wire:model.lazy="staticRows.non_titip.biaya_tambahan.value_{{ $month }}">
                                    @endif

                                </td>
                            @endforeach

                            <td
                                class="text-end px-3 py-2 fw-bold {{ ($staticRows['non_titip']['biaya_tambahan']['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                {{ ($staticRows['non_titip']['biaya_tambahan']['total'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($staticRows['non_titip']['biaya_tambahan']['total'] ?? 0), 0, ',', '.') }}
                            </td>
                            @can('note-edit')
                                <td class="text-center px-3 py-2" style="width: 100px;">

                                    {{-- EDIT --}}
                                    <button class="btn btn-sm btn-outline-warning"
                                        wire:click="toggleEdit('non_titip','biaya_tambahan')"
                                        @if ($editMode['non_titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- SAVE --}}
                                    <button class="btn btn-sm btn-success"
                                        wire:click="saveStaticBatch('non_titip','biaya_tambahan')"
                                        @if (!$editMode['non_titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- CANCEL --}}
                                    <button class="btn btn-sm btn-outline-secondary"
                                        wire:click="toggleEdit('non_titip','biaya_tambahan')"
                                        @if (!$editMode['non_titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </td>
                            @endcan
                        </tr>


                        {{-- ================= KENAIKAN GAJI NON TITIP ================= --}}
                        <tr>
                            <td class="text-center px-3 py-2">{{ count($indicators) + 2 }}
                            </td>
                            <td class="px-3 py-2">Kenaikan Gaji Karyawan/Bonus</td>

                            @foreach ($months as $month)
                                <td class="text-end px-3 py-2">

                                    {{-- VIEW --}}
                                    @if (!$editMode['non_titip']['kenaikan_gaji'])
                                        <span>
                                            Rp{{ number_format($staticRows['non_titip']['kenaikan_gaji']['value_' . $month] ?? 0, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    {{-- EDIT --}}
                                    @if ($editMode['non_titip']['kenaikan_gaji'])
                                        <input type="number" class="form-control form-control-sm text-end"
                                            wire:model.lazy="staticRows.non_titip.kenaikan_gaji.value_{{ $month }}">
                                    @endif

                                </td>
                            @endforeach

                            <td
                                class="text-end px-3 py-2 fw-bold {{ ($staticRows['non_titip']['kenaikan_gaji']['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                {{ ($staticRows['non_titip']['kenaikan_gaji']['total'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($staticRows['non_titip']['kenaikan_gaji']['total'] ?? 0), 0, ',', '.') }}
                            </td>
                            @can('note-edit')
                                <td class="text-center px-3 py-2" style="width: 100px;">

                                    {{-- EDIT --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        wire:click="toggleEdit('non_titip','kenaikan_gaji')"
                                        @if ($editMode['non_titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- SAVE --}}
                                    <button type="button" class="btn btn-sm btn-success"
                                        wire:click="saveStaticBatch('non_titip','kenaikan_gaji')"
                                        @if (!$editMode['non_titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- CANCEL --}}
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        wire:click="toggleEdit('non_titip','kenaikan_gaji')"
                                        @if (!$editMode['non_titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </td>
                            @endcan
                        </tr>


                        {{-- ================= TOTAL NON TITIP ================= --}}
                        <tr>
                            <td colspan="2" class="text-center fw-bold px-3 py-2"
                                style="background-color: #e06c6c; color: white;">
                                Total Gaji
                            </td>

                            @foreach ($months as $month)
                                <td class="text-end fw-bold px-3 py-2" style="background-color: #e06c6c; color: white;">
                                    Rp{{ number_format($totals[$month] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            <td class="text-end fw-bold px-3 py-2" style="background-color: #d4956a; color: white;">
                                {{ ($totals['grand'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($totals['grand'] ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>

                    </tbody>
                </table>

                <h3 class="mb-3">Gaji {{ $currentEntitas }} Titip</h3>
                <table class="table table-bordered mb-0 align-middle mb-3" style="font-size: 14px;">

                    <thead>
                        <tr style="background-color: #b8cce4;">
                            <th class="text-center fw-bold px-3 py-2" style="width: 50px;">NO</th>
                            <th class="text-center fw-bold px-3 py-2">INDIKATOR</th>
                            @foreach ($months as $month)
                                <th class="text-center fw-bold px-3 py-2">{{ $month }}</th>
                            @endforeach
                            <th class="text-center fw-bold px-3 py-2">TOTAL</th>
                            @can('note-edit')
                                <th class="text-center fw-bold px-3 py-2" style="width: 100px;">Action
                                </th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>

                        {{-- ================= AUTO ROW ================= --}}
                        @foreach ($indicatorsTitip as $index => $row)
                            <tr>
                                <td class="text-center px-3 py-2">{{ $index + 1 }}</td>
                                <td class="px-3 py-2">{{ $row['label'] }}</td>

                                @foreach ($months as $month)
                                    <td class="text-end px-3 py-2">
                                        Rp
                                        {{ number_format($row['values'][$month] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach

                                <td
                                    class="text-end px-3 py-2 fw-bold {{ ($row['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                    {{ ($row['total'] ?? 0) < 0 ? '-' : '' }}
                                    Rp{{ number_format(abs($row['total'] ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach


                        {{-- ================= BIAYA TAMBAHAN TITIP ================= --}}
                        <tr>
                            <td class="text-center px-3 py-2">{{ count($indicators) + 1 }}</td>
                            <td class="px-3 py-2">Biaya Tambahan Karyawan Baru</td>

                            @foreach ($months as $month)
                                <td class="text-end px-3 py-2">

                                    {{-- VIEW --}}
                                    @if (!$editMode['titip']['biaya_tambahan'])
                                        <span>
                                            Rp{{ number_format($staticRows['titip']['biaya_tambahan']['value_' . $month] ?? 0, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    {{-- EDIT --}}
                                    @if ($editMode['titip']['biaya_tambahan'])
                                        <input type="number" class="form-control form-control-sm text-end"
                                            wire:model.lazy="staticRows.titip.biaya_tambahan.value_{{ $month }}">
                                    @endif

                                </td>
                            @endforeach

                            <td
                                class="text-end px-3 py-2 fw-bold {{ ($staticRows['titip']['biaya_tambahan']['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                {{ ($staticRows['titip']['biaya_tambahan']['total'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($staticRows['titip']['biaya_tambahan']['total'] ?? 0), 0, ',', '.') }}
                            </td>
                            @can('note-edit')
                                <td class="text-center px-3 py-2" style="width: 100px;">

                                    {{-- EDIT --}}
                                    <button class="btn btn-sm btn-outline-warning"
                                        wire:click="toggleEdit('titip','biaya_tambahan')"
                                        @if ($editMode['titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- SAVE --}}
                                    <button class="btn btn-sm btn-success"
                                        wire:click="saveStaticBatch('titip','biaya_tambahan')"
                                        @if (!$editMode['titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- CANCEL --}}
                                    <button class="btn btn-sm btn-outline-secondary"
                                        wire:click="toggleEdit('titip','biaya_tambahan')"
                                        @if (!$editMode['titip']['biaya_tambahan']) style="display:none;" @endif>
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </td>
                            @endcan
                        </tr>


                        {{-- ================= KENAIKAN GAJI TITIP ================= --}}
                        <tr>
                            <td class="text-center px-3 py-2">{{ count($indicators) + 2 }}
                            </td>
                            <td class="px-3 py-2">Kenaikan Gaji Karyawan/Bonus</td>

                            @foreach ($months as $month)
                                <td class="text-end px-3 py-2">

                                    {{-- VIEW --}}
                                    @if (!$editMode['titip']['kenaikan_gaji'])
                                        <span>
                                            Rp{{ number_format($staticRows['titip']['kenaikan_gaji']['value_' . $month] ?? 0, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    {{-- EDIT --}}
                                    @if ($editMode['titip']['kenaikan_gaji'])
                                        <input type="number" class="form-control form-control-sm text-end"
                                            wire:model.lazy="staticRows.titip.kenaikan_gaji.value_{{ $month }}">
                                    @endif

                                </td>
                            @endforeach

                            <td
                                class="text-end px-3 py-2 fw-bold {{ ($staticRows['titip']['kenaikan_gaji']['total'] ?? 0) < 0 ? 'text-danger' : '' }}">
                                {{ ($staticRows['titip']['kenaikan_gaji']['total'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($staticRows['titip']['kenaikan_gaji']['total'] ?? 0), 0, ',', '.') }}
                            </td>
                            @can('note-edit')
                                <td class="text-center px-3 py-2" style="width: 100px;">

                                    {{-- EDIT --}}
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                        wire:click="toggleEdit('titip','kenaikan_gaji')"
                                        @if ($editMode['titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- SAVE --}}
                                    <button type="button" class="btn btn-sm btn-success"
                                        wire:click="saveStaticBatch('titip','kenaikan_gaji')"
                                        @if (!$editMode['titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-check-lg"></i>
                                    </button>

                                    {{-- CANCEL --}}
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        wire:click="toggleEdit('titip','kenaikan_gaji')"
                                        @if (!$editMode['titip']['kenaikan_gaji']) style="display:none;" @endif>
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </td>
                            @endcan
                        </tr>


                        {{-- ================= TOTAL TITIP ================= --}}
                        <tr>
                            <td colspan="2" class="text-center fw-bold px-3 py-2"
                                style="background-color: #e06c6c; color: white;">
                                Total Gaji
                            </td>

                            @foreach ($months as $month)
                                <td class="text-end fw-bold px-3 py-2"
                                    style="background-color: #e06c6c; color: white;">
                                    Rp{{ number_format($totalsTitip[$month] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach

                            <td class="text-end fw-bold px-3 py-2" style="background-color: #d4956a; color: white;">
                                {{ ($totalsTitip['grand'] ?? 0) < 0 ? '-' : '' }}
                                Rp{{ number_format(abs($totalsTitip['grand'] ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @can('note-create')
        <div class="mb-3">
            <button class="btn btn-primary btn-sm" wire:click="showAddNoteModal"> <i class="bi bi-plus-circle"></i>
                Tambah Catatan</button>
        </div>
    @endcan
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-journal-text me-2 text-primary"></i>Daftar Catatan
            </h6>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse ($notes as $index => $item)
                    <li class="list-group-item px-4 py-3">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 32px; height: 32px; font-size: 13px; font-weight: 600;">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex-grow-1">
                                @if ($item->tittle)
                                    <h6 class="mb-1 fw-semibold">{{ $item->tittle }}</h6>
                                @endif
                                <p class="mb-1">{{ $item->note }}</p>
                            </div>
                            @can('note-edit')
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <button class="btn btn-sm btn-outline-warning"
                                        wire:click="showEditNoteModal({{ $item->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="confirmDeleteNote({{ $item->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </li>
                @empty
                    <li class="list-group-item px-4 py-4 text-center text-muted">
                        <i class="bi bi-inbox me-2"></i>Belum ada catatan.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-muted">
                        <div class="mb-4">
                            <label for="tittle" class="form-label fw-semibold">Judul</label>
                            <input type="text" class="form-control" id="tittle" wire:model="tittle"
                                placeholder="Masukkan judul catatan (opsional)">
                        </div>
                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold">Isi Catatan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi" rows="5" wire:model="note"
                                placeholder="Tulis catatan di sini..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" wire:click="saveNote" class="btn btn-primary"
                            wire:loading.attr="disabled" wire:target="saveNote">
                            <div wire:loading wire:target="saveNote" class="spinner-border spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveNote">
                                <i class="fa fa-save"></i> Simpan
                            </span>
                            <span wire:loading wire:target="saveNote">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" wire:ignore.self id="EditNoteModal" tabindex="-1" aria-labelledby="EditNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-muted">
                        <div class="mb-4">
                            <label for="tittle" class="form-label fw-semibold">Judul</label>
                            <input type="text" class="form-control" id="tittle" wire:model="tittle"
                                placeholder="Masukkan judul catatan (opsional)">
                        </div>
                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold">
                                Isi Catatan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="isi" rows="5" wire:model="note"
                                placeholder="Tulis catatan di sini..."></textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" wire:click="UpdateNote" class="btn btn-primary"
                            wire:loading.attr="disabled" wire:target="UpdateNote">
                            <div wire:loading wire:target="UpdateNote" class="spinner-border spinner-border-sm"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="UpdateNote">
                                <i class="fa fa-save"></i> Simpan
                            </span>
                            <span wire:loading wire:target="UpdateNote">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="deleteNoteModal" tabindex="-1"
        aria-labelledby="deleteNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #dc3545; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger" id="deleteNoteModalLabel">Hapus Catatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data catatan ini?</p>
                    <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button type="button" class="btn btn-danger" wire:click="deletedNote"
                        style="border-radius: 8px;" data-bs-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
