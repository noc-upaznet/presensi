<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Menentukan Lokasi Presensi Karyawan</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menentukan Lokasi Presensi Karyawan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="mb-4">
                <button class="btn btn-primary" wire:click="showAdd">
                    <i class="bi bi-plus"></i> Tambah
                </button>
                <!-- /.card-header -->
            </div>
            <div class="p-0 table-responsive">
                <table class="table table-striped table-hover mb-0" style="background-color: var(--bs-body-bg);">
                    <thead>
                        <tr class="users-table-info">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <label>Show <select class="form-select form-select-sm d-inline-block w-auto">
                                            <option>5</option>
                                            <option>10</option>
                                            <option>20</option>
                                        </select> entries per page</label>
                                </div>
                                <div>
                                    <input type="search" class="form-control form-control-sm" placeholder="Search...">
                                </div>
                            </div>
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Karyawan</th>
                                        <th>Lock</th>
                                        <th>Lokasi Presensi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lokasiList as $roleLokasi)
                                    <tr>
                                        <td>{{ $roleLokasi->nama_karyawan }}</td>
                                        <td>
                                            <span class="badge bg-{{ $roleLokasi->lock ? 'info' : 'secondary' }}">
                                                {{ $roleLokasi->lock ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td>{{ $roleLokasi->lokasi_presensi }}</td>
                                        <td>
                                            <button wire:click.prevent="editLokasi({{ $roleLokasi->id }})"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click.prevent="confirmHapusLokasi({{ $roleLokasi->id }})"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <span>Showing {{ $lokasiList->firstItem() }} to {{ $lokasiList->lastItem() }} of {{
                    $lokasiList->total()
                    }}
                    entries</span>
                {{ $lokasiList->links() }}
            </div>
        </div>
    </div>
</div>