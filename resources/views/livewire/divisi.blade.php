<div>
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Divisi</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddDivisi"><i
                class="fa-solid fa-plus"></i> Tambah Divisi</button>
    </div>
    <div class="p-0 table-responsive">
        <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
            <thead>
                <th>Divisi</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($divisies as $key)
                    <tr>
                        <td style="color: var(--bs-body-color);">{{ $key->nama }}</td>
                        <td>
                            <button type="button" wire:click="showEditDivisi('{{ Crypt::encrypt($key->id) }}')"
                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" wire:click="deleteDivisi('{{ Crypt::encrypt($key->id) }}')"
                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $divisies->links('') }}
    </div>
</div>
