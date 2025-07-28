<div>
    <div class="app-content-header">
      <!--begin::Container-->
      <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
          <div class="col-sm-6 mt-5"><h3 class="mb-0" style="color: var(--bs-body-color);">Data Users</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Data Users</li>
            </ol>
          </div>
        </div>
        <!--end::Row-->
      </div>
      <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
      <!--begin::Container-->
      <div class="container-fluid">
        <div class="mt-2 mb-4">
          <a href="{{ route('tambah-pembagian-shift') }}"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button></a>
          <!-- /.card-header -->
        </div>
        <div class="mb-3">
            <input type="text" class="form-control form-control-sm" placeholder="Cari nama..."
                wire:model.live="search">
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-striped align-middle">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role Saat Ini</th>
                        <th>Password Expired</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ strtoupper($user->current_role) }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input type="checkbox" class="form-check-input"
                                        wire:change="togglePasswordExpired({{ $user->id }})"
                                        @if($user->password_expired) checked @endif>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
