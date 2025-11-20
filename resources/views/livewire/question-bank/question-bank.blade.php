@push('styles')
    <style>
        @media (max-width: 425px) {

            /* Header breadcrumb stack */
            .app-content-header .row {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            /* Tombol tambah dan filter stack */
            .pengajuan-toolbar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.5rem;
            }

            .pengajuan-toolbar button,
            .pengajuan-toolbar select,
            .pengajuan-toolbar input {
                width: 100% !important;
                font-size: 0.8rem;
                padding: 0.4rem 0.5rem;
            }

            /* Tabel scrollable & text kecil */
            .table-responsive {
                overflow-x: auto;
            }

            .table th,
            .table td {
                font-size: 0.75rem;
                padding: 0.4rem 0.5rem;
                white-space: nowrap;
            }

            /* Badge dan button lebih kecil */
            .badge,
            .btn-sm {
                font-size: 0.65rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            /* Pagination dan search input stack */
            .pengajuan-table-controls {
                flex-direction: column !important;
                gap: 0.5rem;
                align-items: stretch !important;
            }
        }
    </style>
@endpush
<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Bank Soal</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Soal</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

    <div class="container mt-4">
        @role('admin')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary mb-2 me-2" wire:click="showAdd">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                    <button class="btn btn-danger mb-2 me-2" wire:click="import">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Import
                    </button>

                    {{-- <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan"
                        wire:model.lazy="filterBulan"> --}}
                </div>
            </div>
        @endrole
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
                        {{-- <input type="text" class="form-control form-control-sm rounded-end-0" placeholder="Tanggal"
                            wire:model.live="search"> --}}
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="background-color: var(--bs-body-bg);">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th class="text-center">Jawaban</th>
                                {{-- <th class="text-center">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($datas as $question)
                                <tr>
                                    <td>{{ $question->name }}</td>

                                    <td>
                                        <div class="list-group">
                                            @foreach ($question->answers as $answer)
                                                <div class="mb-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="flex">
                                                            {{ $answer->name }}
                                                        </div>
                                                        <div>
                                                            @if ($answer->is_correct)
                                                                <span class="badge bg-success">Benar</span>
                                                            @else
                                                                <span class="badge bg-secondary">Salah</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>

                                    {{-- <td class="text-center">
                                        <button class="btn btn-primary btn-sm"
                                            wire:click="edit({{ $question->id }})">Edit</button>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="delete({{ $question->id }})">Delete</button>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{-- {{ $pengajuans->links() }} --}}
                </div>
            </div>
        </div>
    </div>
    <livewire:question-bank.modal-question />
    <div class="modal fade" id="modalGambar" tabindex="-1" aria-labelledby="modalGambarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGambarLabel">Bukti Izin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Bukti" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('modalAddQuestion', (event) => {
            $('#modalAddQuestion').modal(event.action);
        });

        Livewire.on('modalEditPengajuan', (event) => {
            $('#modalEditPengajuan').modal(event.action);
        });

        function setModalImage(src) {
            document.getElementById('modalImage').src = src;
        }

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
