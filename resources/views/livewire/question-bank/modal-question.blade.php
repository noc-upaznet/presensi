<div>
    <div wire:ignore.self class="modal fade" id="modalAddQuestion" tabindex="-1" aria-labelledby="modalAddQuestionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalAddQuestionLabel">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="divisi" class="form-label">Divisi</label>
                        <select type="text" class="form-select" id="divisi" wire:model="divisi">
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisies as $item)
                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('divisi')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Pertanyaan</label>
                        <input type="text" class="form-control" id="name" wire:model="name">
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer1_radio"
                                    value="answer1" wire:model="correct_answer">
                                <label class="form-check-label" for="answer1_radio">Jawaban 1</label>
                            </div>
                            <input type="text" class="form-control" id="answer1_text" wire:model="answer1">
                            @error('answer1')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer2_radio"
                                    value="answer2" wire:model="correct_answer">
                                <label class="form-check-label" for="answer2_radio">Jawaban 2</label>
                            </div>
                            <input type="text" class="form-control" id="answer2_text" wire:model="answer2">
                            @error('answer2')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer3_radio"
                                    value="answer3" wire:model="correct_answer">
                                <label class="form-check-label" for="answer3_radio">Jawaban 3</label>
                            </div>
                            <input type="text" class="form-control" id="answer3_text" wire:model="answer3">
                            @error('answer3')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer4_radio"
                                    value="answer4" wire:model="correct_answer">
                                <label class="form-check-label" for="answer4_radio">Jawaban 4</label>
                            </div>
                            <input type="text" class="form-control" id="answer4_text" wire:model="answer4">
                            @error('answer4')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @error('correct_answer')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click="store" wire:loading.attr="disabled" class="btn btn-primary"
                        style="border-radius: 8px;">
                        <span wire:loading wire:target="store" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="fa-solid fa-save"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditQuestion" tabindex="-1"
        aria-labelledby="modalEditQuestionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 0.375rem; border-top: 4px solid #007bff; border-left: 1px solid #dee2e6;
                        border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditQuestionLabel">Edit Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="divisi" class="form-label">Divisi</label>
                        <select type="text" class="form-select" id="divisi" wire:model="divisi">
                            <option value="">Pilih Divisi</option>
                            @foreach ($divisies as $item)
                                <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        @error('divisi')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Pertanyaan</label>
                        <input type="text" class="form-control" id="name" wire:model="name">
                        @error('name')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer1_radio"
                                    value="answer1" wire:model="correct_answer">
                                <label class="form-check-label" for="answer1_radio">Jawaban 1</label>
                            </div>
                            <input type="text" class="form-control" id="answer1_text" wire:model="answer1">
                            @error('answer1')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer2_radio"
                                    value="answer2" wire:model="correct_answer">
                                <label class="form-check-label" for="answer2_radio">Jawaban 2</label>
                            </div>
                            <input type="text" class="form-control" id="answer2_text" wire:model="answer2">
                            @error('answer2')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer3_radio"
                                    value="answer3" wire:model="correct_answer">
                                <label class="form-check-label" for="answer3_radio">Jawaban 3</label>
                            </div>
                            <input type="text" class="form-control" id="answer3_text" wire:model="answer3">
                            @error('answer3')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="correct" id="answer4_radio"
                                    value="answer4" wire:model="correct_answer">
                                <label class="form-check-label" for="answer4_radio">Jawaban 4</label>
                            </div>
                            <input type="text" class="form-control" id="answer4_text" wire:model="answer4">
                            @error('answer4')
                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @error('correct_answer')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 8px;">Batal</button>
                    <button wire:click="store" wire:loading.attr="disabled" class="btn btn-primary"
                        style="border-radius: 8px;">
                        <span wire:loading wire:target="store" class="spinner-border spinner-border-sm me-1"></span>
                        <i class="fa-solid fa-save"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm-delete" tabindex="-1" wire:ignore.self data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhatian!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin ingin menghapus soal ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-danger" wire:ignore.self id="btn-confirm-delete"
                        wire:loading.attr="disabled">
                        <div wire:loading class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImport" wire:ignore.self tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header" style="color: var(--bs-body-color);">
                    <h5 class="modal-title">Import Data Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveImport">
                    <div class="modal-body" style="color: var(--bs-body-color);">
                        <div class="container">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Form Upload</label>
                                <input class="form-control" type="file" id="formFile" wire:model="file"
                                    accept=".xlsx, .xls">
                                <div wire:loading wire:target="file" class="text-warning mt-2">
                                    Sedang upload file, mohon tunggu...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <div wire:loading class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });
    </script>
@endpush
