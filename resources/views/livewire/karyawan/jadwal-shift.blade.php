<div>
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0" style="color: var(--bs-body-color);">Jadwal Shift Karyawan</h3></div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Jadwal Shift Karyawan</li>
              </ol>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" wire:click="showAdd">
                <i class="bi bi-plus"></i> Tambah
            </button>
    
            <div class="d-flex gap-2">
                <select class="form-select" style="width: 150px;">
                    <option selected>Pilih Karyawan</option>
                    @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                    @endforeach
                </select>
    
                <select class="form-select" style="width: 120px;">
                    <option selected>Bulan Ini</option>
                    <option>April 2025</option>
                    <option>Maret 2025</option>
                </select>
    
                <button class="btn btn-light" disabled>Pilih Waktu</button>
            </div>
        </div>
    
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Nama Karyawan</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwals as $key)
                    <tr>
                        <td>{{ $key->bulan_tahun }}</td>
                        <td>{{ $key->getKaryawan->nama_karyawan }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-sm btn-warning" wire:click="showEdit('{{ Crypt::encrypt($key->id) }}')"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger" wire:click="$dispatch('modal-confirm-delete',{id:'{{ Crypt::encrypt($key->id) }}',action:'show'})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
    </div>

    <livewire:karyawan.modal-jadwal-shift />

</div>

@push('scripts')
    <script>
        Livewire.on('modalTambahJadwal', () => {
            let modal = new bootstrap.Modal(document.getElementById('modalTambahJadwal'));
            modal.show();
            // setTimeout(() => {
            //     // Destroy dulu kalau sudah pernah diinisialisasi
            //     if ($.fn.select2 && $('#selectKaryawan').hasClass("select2-hidden-accessible")) {
            //         $('#selectKaryawan').select2('destroy');
            //     }

            //     // Re-init Select2
            //     $('#selectKaryawan').select2({
            //         placeholder: "-- Pilih Karyawan --",
            //         dropdownParent: $('#modalTambahJadwal') // penting jika pakai modal
            //     });

            //     // Sync ke Livewire
            //     $('#selectKaryawan').on('change', function (e) {
            //         Livewire.dispatch('setKaryawan', $(this).val());
            //     });
            // }, 200);

            setTimeout(() => {
                $('#bulanPicker').datepicker({
                    format: "yyyy-mm",
                    startView: "months",
                    minViewMode: "months",
                    autoclose: true
                });
            }, 300);
        });

        Livewire.on('closeModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambahJadwal'));
            modal.hide();
        });

        Livewire.on('modalEditJadwal', () => {
            let modal = new bootstrap.Modal(document.getElementById('modalEditJadwal'));
            modal.show();

            // setTimeout(() => {
            //     $('#bulan').datepicker({
            //         format: "yyyy-mm",
            //         startView: "months",
            //         minViewMode: "months",
            //         autoclose: true
            //     });
            // }, 300);
        });

        Livewire.on('closeModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditJadwal'));
            modal.hide();
        });

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

