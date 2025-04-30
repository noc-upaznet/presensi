<div>
    <div wire:ignore.self class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content" style="background-color: var(--bs-body-bg);">
            <div class="modal-header border-bottom" style="color: var(--bs-body-color);">
              <h5 class="modal-title text-primary fw-bold" id="modalTambahJadwalLabel">Tambah Jadwal</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="color: var(--bs-body-color);">
                {{-- Pilih Bulan --}}
                <div wire:ignore class="mb-3">
                    <label class="form-label fw-semibold">Bulan</label>
                    <input type="month" id="bulan" wire:model="bulan_tahun" class="form-control">
                </div>

                {{-- Pilih Karyawan --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Karyawan</label>
                    <select class="form-select" wire:model="selectedKaryawan">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select>
                </div>
      
                {{-- Pilih Template --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Template Mingguan</label>
                    <select wire:model="selectedTemplateId" wire:change="fillCalendarFromTemplate" class="form-select">
                        <option value="">-- Pilih Template --</option>
                        @foreach ($templateWeeks as $template)
                            <option value="{{ $template->id }}">{{ $template->nama_template }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kalender Jadwal --}}
                <div>
                    <label class="form-label fw-semibold">Kalender</label>
                    <div class="table-responsive">
                        @php
                            $tanggal = 1;
                        @endphp

                        <table class="table table-bordered" wire:key="kalender-{{ $kalenderVersion }}">
                            <thead>
                                <tr>
                                    <th>Minggu</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                    <th>Sabtu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $jumlahBaris; $i++)
                                    <tr>
                                        @for ($j = 0; $j < 7; $j++)
                                            @php
                                                $cellIndex = $i * 7 + $j;
                                            @endphp

                                            @if ($cellIndex < $hariPertama || $tanggal > $totalHari)
                                                <td></td>
                                            @else
                                                <td>
                                                    <div class="fw-semibold small">{{ $tanggal }}</div>
                                                    <select class="form-select form-select-sm mt-1 text-center" name="shift[{{ $tanggal }}]" wire:model.defer="kalender.{{ $tanggal }}">
                                                        <option value="">-- Pilih Shift --</option>
                                                        @foreach($jadwalShifts as $shift)
                                                            <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                                        @endforeach
                                                    </select>
                                                    @php $tanggal++; @endphp
                                                </td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
      
            </div>
            <div class="modal-footer">
              {{-- <button type="button" wire:click="store" class="btn btn-primary">Simpan</button> --}}
              <button type="button" class="btn btn-primary" wire:click='store' wire:loading.attr="disabled" wire:target="store">
                    <div wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove wire:target="store"><i class="fa fa-save"></i> Simpan</span>
                    <span wire:loading wire:target="store">Loading...</span>
                </button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditJadwal" tabindex="-1" aria-labelledby="modalEditJadwalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background-color: var(--bs-body-bg);">
                <div class="modal-header border-bottom" style="color: var(--bs-body-color);">
                    <h5 class="modal-title text-primary fw-bold" id="modalEditJadwalLabel">Edit Jadwal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" style="color: var(--bs-body-color);">
                    {{-- Pilih Bulan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bulan</label>
                        <input type="month" id="bulan2" wire:model="bulan_tahun" class="form-control">
                    </div>
                    {{-- Pilih Karyawan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select class="form-select" wire:model="selectedKaryawan">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}">
                                {{ $karyawan->nama_karyawan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
      
                    {{-- Pilih Template --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Template Mingguan</label>
                        <select class="form-select" wire:model="selectedTemplateId" wire:change="fillCalendarFromTemplate">
                            <option>-- Pilih Template --</option>
                            @foreach ($templateWeeks as $template)
                                <option value="{{ $template->id }}" @selected($selectedTemplateId == $template->id)>{{ $template->nama_template }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    {{-- Kalender Jadwal --}}
                    <div>
                        <label class="form-label fw-semibold">Kalender</label>
                        <div class="table-responsive">
                            @php
                                $bulan = $this->bulanTahun['bulan'];
                                $tahun = $this->bulanTahun['tahun'];

                                $totalHari = \Carbon\Carbon::create($tahun, $bulan)->daysInMonth;
                                $hariPertama = \Carbon\Carbon::create($tahun, $bulan, 1)->dayOfWeek;
                                $totalCell = $hariPertama + $totalHari;
                                $jumlahBaris = ceil($totalCell / 7);
                                $tanggal = 1;
                            @endphp
    
                            <table class="table table-bordered" wire:key="kalender-{{ $this->bulan_tahun }}">
                                <thead>
                                    <tr>
                                        <th>Minggu</th>
                                        <th>Senin</th>
                                        <th>Selasa</th>
                                        <th>Rabu</th>
                                        <th>Kamis</th>
                                        <th>Jumat</th>
                                        <th>Sabtu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < $jumlahBaris; $i++)
                                        <tr>
                                            @for ($j = 0; $j < 7; $j++)
                                                @php
                                                    $cellIndex = $i * 7 + $j;
                                                @endphp
    
                                                @if ($cellIndex < $hariPertama || $tanggal > $totalHari)
                                                    <td></td>
                                                @else
                                                <td>
                                                    <div class="fw-semibold small">{{ $tanggal }}</div>
                                                    <select class="form-select form-select-sm mt-1 text-center" name="shift[{{ $tanggal }}]" wire:model="kalender.{{ $tanggal }}">
                                                        <option value="">-- Pilih Shift --</option>
                                                        @foreach($jadwalShifts as $shift)
                                                            <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                                        @endforeach
                                                    </select>
                                                    @php $tanggal++; @endphp
                                                </td>
                                                @endif
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" wire:click='saveEdit' wire:loading.attr="disabled" wire:target="edit">
                        <div wire:loading wire:target="edit" class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span wire:loading.remove wire:target="edit"><i class="fa fa-save"></i> Simpan</span>
                        <span wire:loading wire:target="edit">Loading...</span>
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
                    Anda yakin ingin menghapus jadwal shift ini?
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputBulan = document.getElementById('bulan');
        if (inputBulan && !inputBulan.value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            inputBulan.value = `${year}-${month}`;
        }
        inputBulan.addEventListener('change', function () {
            Livewire.dispatch('bulanChanged', { value: this.value });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const inputBulan = document.getElementById('bulan2');
        if (inputBulan && !inputBulan.value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            inputBulan.value = `${year}-${month}`;
        }
        inputBulan.addEventListener('change', function () {
            Livewire.dispatch('bulanChanged', { value: this.value });
        });
    });

    Livewire.on('swal', (e) => {
        Swal.fire(e.params);
    });
</script>
@endpush
