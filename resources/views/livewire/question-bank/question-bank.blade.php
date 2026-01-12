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
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Bank Soal</h4>
    </div>

    <div class="p-0 table-responsive">
        @role('admin')
            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary mb-2 me-2 btn-sm" wire:click="showAdd">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                    <button class="btn btn-success mb-2 me-2 btn-sm" wire:click="import">
                        <i class="bi bi-file-earmark-spreadsheet"></i> Import
                    </button>

                    {{-- <input type="month" class="form-control" style="width: 150px;" id="bulanPicker" placeholder="Bulan"
                        wire:model.lazy="filterBulan"> --}}
                </div>
            </div>
        @endrole
        <table class="table table-bordered mb-0" style="background-color: var(--bs-body-bg);">
            <thead>
                <tr>
                    <th>Divisi</th>
                    <th>Pertanyaan</th>
                    <th class="text-center">Jawaban</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datas as $question)
                    <tr>
                        <td>{{ $question->divisi }}</td>
                        <td>{{ $question->name }}</td>

                        <td>
                            <div class="list-group">
                                @foreach ($question->answers as $answer)
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex">
                                                <i class="bi bi-caret-right-fill"></i> {{ $answer->name }}
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

                        <td class="text-center" style="color: var(--bs-body-color);">
                            {{-- <button class="btn btn-sm btn-warning"
                                wire:click="showEdit('{{ Crypt::encrypt($question->id) }}')"><i
                                    class="fa-solid fa-pen-to-square"></i></button> --}}
                            <button class="btn btn-sm btn-danger"
                                wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($question->id) }}',action:'show'})"><i
                                    class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $datas->links() }}
        </div>
    </div>
    <livewire:question-bank.modal-question />
</div>

@push('scripts')
    <script>
        Livewire.on('modalAddQuestion', (event) => {
            $('#modalAddQuestion').modal(event.action);
        });

        Livewire.on('modalImport', (event) => {
            $('#modalImport').modal(event.action);
        });

        Livewire.on('modalEditQuestion', (event) => {
            $('#modalEditQuestion').modal(event.action);
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
