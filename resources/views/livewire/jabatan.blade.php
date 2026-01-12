<div>
    <div class="mb-4">
        <h4 class="mb-4" style="color: var(--bs-body-color);">Jabatan</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd"><i
                class="fa-solid fa-plus"></i> Tambah Jabatan</button>
        <!-- /.card-header -->
    </div>
    <div class="p-0 table-responsive">
        <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
            <thead>
                <th>Jabatan</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jabatans as $key)
                    <tr>
                        <td style="color: var(--bs-body-color);">{{ $key->nama_jabatan }}</td>
                        <td>
                            <button type="button" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"
                                class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" wire:click="delete('{{ Crypt::encrypt($key->id) }}')"
                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $jabatans->links('') }}
    </div>
</div>
