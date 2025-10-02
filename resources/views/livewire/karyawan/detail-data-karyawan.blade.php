<div>
    <style>
        .bio-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .bio-table th,
        .bio-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .bio-label {
            background: #d9e6f5;
            font-weight: bold;
            width: 200px;
        }
    </style>
    <div class="p-4">
        <h5 class="fw-bold mb-3" style="color: var(--bs-body-color);">Detail Data Karyawan</h5>
    
        <div class="border rounded-4 p-4">
            <h6 class="fw-bold text-primary">PERSOAL DATA (DATA DIRI KARYAWAN)</h6>
            <div class="row align-items-start mt-3">
                <div class="col-md-12" style="color: var(--bs-body-color);">
                    <table class="bio-table">
                        <tr>
                            <td class="bio-label">NAMA LENGKAP<br><small>(FULL NAME)</small></td>
                            <td>: {{ $karyawan->nama_karyawan }}</td>
                            <td class="bio-label">NO KTP<br><small>(ID NUMBER)</small></td>
                            <td>: {{ $karyawan->nik }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">TEMPAT LAHIR<br><small>(PLACE OF BIRTH)</small></td>
                            <td>: {{ $karyawan->tempat_lahir }}</td>
                            <td class="bio-label">TANGGAL LAHIR<br><small>(DATE OF BIRTH)</small></td>
                            <td>: {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">JENIS KELAMIN<br><small>(GENDER)</small></td>
                            <td>: {{ $karyawan->jenis_kelamin }}</td>
                            <td class="bio-label">AGAMA<br><small>(RELIGION)</small></td>
                            <td>: {{ $karyawan->agama }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">KEWARGANEGARAAN<br><small>(NATIONALITY)</small></td>
                            <td>: WNI</td>
                            <td class="bio-label">STATUS PERNIKAHAN<br><small>(MARITAL STATUS)</small></td>
                            <td>: {{ $karyawan->status_perkawinan }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">TELEPON &amp; HP<br><small>(PHONE &amp; MOBILE)</small></td>
                            <td>: {{ $karyawan->no_hp }}</td>
                            <td class="bio-label">ALAMAT E-MAIL<br><small>(E-MAIL ADDRESS)</small></td>
                            <td>: {{ $karyawan->email }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">FACEBOOK</td>
                            <td>:</td>
                            <td class="bio-label">INSTAGRAM</td>
                            <td>:</td>
                        </tr>
                        <tr>
                            <td class="bio-label">TWITTER</td>
                            <td>:</td>
                            <td class="bio-label">LINKEDIN</td>
                            <td>:</td>
                        </tr>
                        <tr>
                            <td class="bio-label">ALAMAT SESUAI KTP<br><small>(ID CARD ADDRESS)</small></td>
                            <td colspan="3">: {{ $karyawan->alamat_ktp }}</td>
                        </tr>
                        <tr>
                            <td class="bio-label">ALAMAT DOMISILI<br><small>(FULL ADDRESS)</small></td>
                            <td colspan="3">: {{ $karyawan->alamat_domisili }}</td>
                        </tr>
                    </table>
                    {{-- <div class="row">
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Nama</span> <span>: {{ $karyawan->nama_karyawan ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tempat Lahir</span> <span>: {{ $karyawan->tempat_lahir ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tanggal Lahir</span> <span>: {{ $karyawan->tanggal_lahir ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Status Pernikahan</span> <span>: {{ $karyawan->status_perkawinan ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Kewarganegaraan</span> <span>: Indonesia</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Gol. Darah</span> <span>: {{ $karyawan->gol_darah ?? '-' }}</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Berat Badan</span> <span>: 55</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Tinggi Badan</span> <span>: 155</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Ukuran Sepatu</span> <span>: 39</span>
                            </div>
                            <div class="mb-1 d-flex justify-content-between">
                                <span>Ukuran Baju</span> <span>: M</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
    
            <hr class="my-4">
    
            {{-- <div class="row" style="color: var(--bs-body-color);">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Pribadi</h6>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Jenis Kelamin</span> <span>: {{ $karyawan->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>NIK</span> <span>: {{ $karyawan->nik ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Agama</span> <span>: {{ $karyawan->agama ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Divisi</span> <span>: {{ $karyawan->divisi ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Jabatan</span> <span>: {{ $karyawan->jabatan ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Level</span> <span>: {{ $karyawan->level ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Entitas</span> <span>: {{ $karyawan->entitas ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Tanggal Kontrak</span> <span>: {{ $karyawan->tgl_masuk ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Tanggal Habis Kontrak</span> <span>: {{ $karyawan->tgl_keluar ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Status Hubungan Kerja</span> <span>: {{ $karyawan->status_karyawan ?? '-' }}</span>
                    </div>
                </div>
    
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary">Informasi Kontak</h6>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>No.HP</span> <span>: {{ $karyawan->no_hp ?? '-' }}</span>
                    </div>
                    <div class="mb-1 d-flex justify-content-between">
                        <span>Email</span> <span>: {{ $karyawan->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
    
            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Informasi Alamat</h6>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat KTP</span>
                <span>: {{ $karyawan->alamat_ktp ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <span>Alamat Domisili</span>
                <span>: {{ $karyawan->alamat_domisili ?? '-' }}</span>
            </div> --}}

            <hr class="my-4">

            <h6 class="fw-bold text-primary mb-3">DATA KELUARGA (FAMILY MEMBER OF KK) </h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>HUBUNGAN KELUARGA</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>JENIS KELAMIN</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL LAHIR</th>
                            <th>AGAMA</th>
                            <th>PENDIDIKAN</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataFamilys as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->relationships }}</td>
                                <td>{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->nik ?? '-' }}</td>
                                <td>{{ $item->gender ?? '-' }}</td>
                                <td>{{ $item->place_of_birth ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date_of_birth)->locale('id')->translatedFormat('d F Y') }}</td>
                                <td>{{ $item->religion ?? '-' }}</td>
                                <td>{{ $item->education ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm mt-2" wire:click="showEditKeluarga('{{ $item->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" wire:click="deleteKeluarga('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data keluarga</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th rowspan="2">NO</th>
                            <th rowspan="2">STATUS PERKAWINAN</th>
                            <th rowspan="2">TANGGAL PERKAWINAN</th>
                            <th rowspan="2">STATUS HUBUNGAN<br>DALAM KELUARGA</th>
                            <th rowspan="2">KEWARGANEGARAAN</th>
                            <th colspan="2">NAMA ORANG TUA</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr>
                            <th>AYAH</th>
                            <th>IBU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataFamilys as $item)
                            <tr>
                                @php
                                    var_dump($item->wedding_date);
                                @endphp
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->marital_status }}</td>
                                @if ($item->wedding_date == null)
                                    <td>-</td>
                                @else
                                    <td>{{ \Carbon\Carbon::parse($item->wedding_date)->locale('id')->translatedFormat('d F Y') }}</td>
                                @endif
                                <td>{{ $item->relationship_in_family }}</td>
                                <td>{{ $item->citizenship }}</td>
                                <td>{{ $item->father }}</td>
                                <td>{{ $item->mother }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm mt-2" wire:click="showEditRelationship('{{ $item->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" wire:click="deleteKeluarga('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data keluarga</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3 mb-3">
                    <button type="button" class="btn btn-primary btn-sm"
                            wire:click="showAdd">
                        <i class="bi bi-plus-square"></i> Tambah
                    </button>
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 mt-3">Data Tanggungan (Suami/Istri/Anak) (Dependent Data of Husband/Wife/Children) </h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>HUBUNGAN KELUARGA</th>
                            <th>NAMA</th>
                            <th>JENIS KELAMIN</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL LAHIR</th>
                            <th>PENDIDIKAN</th>
                            <th>PEKERJAAN</th>
                            <th>NO TELEPHONE</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataDependents as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->relationships }}</td>
                                <td>{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->gender ?? '-' }}</td>
                                <td>{{ $item->place_of_birth ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date_of_birth)->locale('id')->translatedFormat('d F Y') }}</td>
                                <td>{{ $item->education ?? '-' }}</td>
                                <td>{{ $item->profession ?? '-' }}</td>
                                <td>{{ $item->no_telp ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm mt-2" wire:click="showEditTanggungan('{{ $item->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" wire:click="deleteTanggungan('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data tanggungan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 mb-3">
                    <button type="button" class="btn btn-primary btn-sm"
                            wire:click="showAddTanggungan">
                        <i class="bi bi-plus-square"></i> Tambah
                    </button>
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 mt-3">RIWAYAT PENDIDIKAN (EDUCATIONAL BACKGROUND) </h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>JENJANG PENDIDIKAN</th>
                            <th>NAMA SEKOLAH / INSTITUT</th>
                            <th>TAHUN MULAI</th>
                            <th>TAHUN AKHIR</th>
                            <th>JURUSAN</th>
                            <th>NILAI/IPK (CGPA)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataEducations as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->level_of_education }}</td>
                                <td>{{ $item->institution ?? '-' }}</td>
                                <td>{{ $item->start_date ?? '-' }}</td>
                                <td>{{ $item->end_date ?? '-' }}</td>
                                <td>{{ $item->major ?? '-' }}</td>
                                <td>{{ $item->nilai ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm mt-2" wire:click="showEditEducation('{{ $item->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" wire:click="deletePendidikan('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data pendidikan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3 mb-3">
                    <button type="button" class="btn btn-primary btn-sm"
                            wire:click="showAddPendidikan">
                        <i class="bi bi-plus-square"></i> Tambah
                    </button>
                </div>
            </div>

            <h6 class="fw-bold text-primary mb-3 mt-3">PENGALAMAN KERJA</h6>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>NO</th>
                            <th>PERUSAHAAN</th>
                            <th>LAMA KERJA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dataWorkExperience as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->company }}</td>
                                <td>{{ $item->employment_period ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm mt-2" wire:click="showEditExperience('{{ $item->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mt-2" wire:click="deleteExperience('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">Belum ada data pengalaman kerja</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 mb-3">
                    <button type="button" class="btn btn-primary btn-sm"
                            wire:click="showAddExperience">
                        <i class="bi bi-plus-square"></i> Tambah
                    </button>
                </div>
            </div>

            <hr class="my-4">

            <h6 class="fw-bold text-primary mb-3 mt-3">KETERANGAN TAMBAHAN</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th colspan="1">NO</th>
                            <th>NAMA</th>
                            <th></th>
                            <th>KETERANGAN</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $fields = [
                                'dress_size'        => 'UKURAN BAJU',
                                'shoe_size'         => 'UKURAN SEPATU',
                                'height'            => 'TINGGI BADAN',
                                'weight'            => 'BERAT BADAN',
                                'nip'               => 'NIP',
                                'start_date'        => 'MULAI MASUK',
                                'personality'       => 'PERSONALITY',
                                'iq'                => 'IQ',
                                'parent_address'    => 'ALAMAT ORANG TUA',
                                'inlaw_address'     => 'ALAMAT MERTUA',
                                'history_of_illness'=> 'RIWAYAT PENYAKIT',
                                'name_father_in_law'=> 'NAMA MERTUA LAKI-LAKI',
                                'name_mother_in_law'=> 'NAMA MERTUA PEREMPUAN',
                            ];
                            $no = 1;
                        @endphp
                        @foreach($fields as $key => $label)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $label }}</td>
                                <td>:</td>
                                <td>
                                    @if($editing[$key])
                                        <input type="text" class="form-control" wire:model.defer="values.{{ $key }}">
                                    @else
                                        {{ $values[$key] ?: '-' }}
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn {{ $editing[$key] ? 'btn-success' : 'btn-warning' }}"
                                            wire:click="toggleEdit('{{ $key }}')">
                                        @if($editing[$key])
                                            <i class="bi bi-save"></i> Simpan
                                        @else
                                            <i class="bi bi-pencil-square"></i> Edit
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="my-4">
    
            <h6 class="fw-bold text-primary">Data Gamifikasi</h6>
            <div style="color: var(--bs-body-color);">
                <span>Jumlah Poin</span>
                <span>: {{ $karyawan->poin ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between" style="color: var(--bs-body-color);">
                <div class="row">
                    <div class="mt-3 col-md-6">
                        <input type="number" wire:model.lazy="jml_poin" class="form-control" id="jumlah-poin" placeholder="Masukkan Jml. Poin">
                    </div>
                    <div class="mt-3 col-md-6">
                        <button class="btn btn-primary" wire:click="updateGamifikasi"><i class="bi bi-save"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div align="right" class="mt-3">
            <a href="/data-karyawan" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
        </div>
    </div>

    {{-- MODAL --}}
    <div wire:ignore.self class="modal fade" id="modalTambahKeluarga" tabindex="-1" aria-labelledby="modalTambahKeluargaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahKeluargaLabel">DATA KELUARGA (FAMILY MEMBER OF KK)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan Keluarga</label>
                            <select class="form-select" wire:model="relationships">
                                <option value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="SUAMI">SUAMI</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control"
                                wire:model="name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" wire:model="nik" id="nik" name="nik">
                            @error('form.nik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="gender" id="gender" name="gender">
                                <option selected value="">-- Pilih --</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            @error('form.gender') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="name" wire:model="place_of_birth" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_of_birth" wire:model="date_of_birth" name="date_of_birth">
                            @error('form.date_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="religion" id="agama" name="agama">
                                <option selected>-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('form.agama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Pendidikan</label>
                            <select class="form-select" wire:model="education" id="agama" name="agama">
                                <option selected value="">-- Pilih --</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP">SMP</option>
                                <option value="SD">SD</option>
                                <option value="DIPLOMA IV/STRATA 1">DIPLOMA IV/STRATA 1</option>
                            </select>
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="relationships" class="form-label">Status Perkawinan <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="marital_status" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="KAWIN TERCATAT">KAWIN TERCATAT</option>
                                <option value="BELUM KAWIN">BELUM KAWIN</option>
                            </select>
                            @error('form.relationships') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="wedding_date" class="form-label">Tanggal Perkawinan</label>
                            <input type="date" class="form-control" id="wedding_date" wire:model="wedding_date" name="wedding_date">
                            @error('form.wedding_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="relationships" class="form-label">Status Hubungan Keluarga <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="relationship_in_family" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                            @error('form.relationships') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Kewarganegaraan</label>
                            <select class="form-select" wire:model="citizenship" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="WNI">WNI</option>
                                <option value="WNA">WNA</option>
                            </select>
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Ayah</label>
                            <input type="text" class="form-control" id="name" wire:model="father" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Ibu</label>
                            <input type="text" class="form-control" id="name" wire:model="mother" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='saveKeluarga' wire:loading.attr="disabled" wire:target="saveKeluarga">
                            <div wire:loading wire:target="saveKeluarga" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveKeluarga"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="saveKeluarga">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalTambahTanggungan" tabindex="-1" aria-labelledby="modalTambahTanggunganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahTanggunganLabel">Data Tanggungan (Suami/Istri/Anak)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan Keluarga</label>
                            <select class="form-select" wire:model="relationships">
                                <option value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="SUAMI">SUAMI</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control"
                                wire:model="name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="gender" id="gender" name="gender">
                                <option selected value="">-- Pilih --</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            @error('form.gender') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="place_of_birth" wire:model="place_of_birth" name="place_of_birth">
                            @error('form.place_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_of_birth" wire:model="date_of_birth" name="date_of_birth">
                            @error('form.date_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="education" class="form-label">Pendidikan</label>
                            <select class="form-select" wire:model="education" id="education" name="education">
                                <option selected value="">-- Pilih --</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP">SMP</option>
                                <option value="SD">SD</option>
                                <option value="DIPLOMA IV/STRATA 1">DIPLOMA IV/STRATA 1</option>
                            </select>
                            @error('form.education') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="profession" wire:model="profession" name="profession">
                            @error('form.profession') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">No Telephone</label>
                            <input type="text" class="form-control" id="no_telp" wire:model="no_telp" name="no_telp">
                            @error('form.no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='saveTanggungan' wire:loading.attr="disabled" wire:target="saveTanggungan">
                            <div wire:loading wire:target="saveTanggungan" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveTanggungan"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="saveTanggungan">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalTambahPendidikan" tabindex="-1" aria-labelledby="modalTambahPendidikanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahPendidikanLabel">RIWAYAT PENDIDIKAN (EDUCATIONAL BACKGROUND)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="level_of_education" class="form-label">Jenjang</label>
                            <input type="text" class="form-control" id="level_of_education" wire:model="level_of_education" name="level_of_education" placeholder="SMA/STRATA 1">
                            @error('form.level_of_education') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="institution" class="form-label">Nama Sekolah/Institut/Universitas</label>
                            <input type="text" class="form-control" id="institution" wire:model="institution" name="institution">
                            @error('form.institution') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tahun Mulai</label>
                            <input type="date" class="form-control" id="start_date" wire:model="start_date" name="start_date">
                            @error('form.start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tahun Akhir</label>
                            <input type="date" class="form-control" id="end_date" wire:model="end_date" name="end_date">
                            @error('form.end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="major" wire:model="major" name="major">
                            @error('form.major') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nilai" class="form-label">Nilai/IPK(CGPA)</label>
                            <input type="text" class="form-control" id="nilai" wire:model="nilai" name="nilai">
                            @error('form.nilai') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='savePendidikan' wire:loading.attr="disabled" wire:target="savePendidikan">
                            <div wire:loading wire:target="savePendidikan" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="savePendidikan"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="savePendidikan">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalTambahExperience" tabindex="-1" aria-labelledby="modalTambahExperienceLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalTambahExperienceLabel">PENGALAMAN KERJA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Perusahaan</label>
                            <input type="text" class="form-control" id="company" wire:model="company" name="company" placeholder="PT. xxx">
                            @error('form.company') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="employment_period" class="form-label">Periode Kerja</label>
                            <input type="text" class="form-control" id="employment_period" wire:model="employment_period" name="employment_period" placeholder="Jan 2025 - Des 2025">
                            @error('form.employment_period') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='saveExperience' wire:loading.attr="disabled" wire:target="saveExperience">
                            <div wire:loading wire:target="saveExperience" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="saveExperience"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="saveExperience">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditKeluarga" tabindex="-1" aria-labelledby="modalEditKeluargaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditKeluargaLabel">DATA KELUARGA (FAMILY MEMBER OF KK)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan Keluarga</label>
                            <select class="form-select" wire:model="relationships">
                                <option value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="SUAMI">SUAMI</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control"
                                wire:model="name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" wire:model="nik" id="nik" name="nik">
                            @error('form.nik') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="gender" id="gender" name="gender">
                                <option selected value="">-- Pilih --</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            @error('form.gender') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="name" wire:model="place_of_birth" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="name" wire:model="date_of_birth" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="agama" class="form-label">Agama <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="religion" id="agama" name="agama">
                                <option selected>-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            @error('form.agama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Pendidikan</label>
                            <select class="form-select" wire:model="education" id="agama" name="agama">
                                <option selected value="">-- Pilih --</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP">SMP</option>
                                <option value="SD">SD</option>
                                <option value="DIPLOMA IV/STRATA 1">DIPLOMA IV/STRATA 1</option>
                            </select>
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='updateKeluarga' wire:loading.attr="disabled" wire:target="updateKeluarga">
                            <div wire:loading wire:target="updateKeluarga" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="updateKeluarga"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="updateKeluarga">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditRelationship" tabindex="-1" aria-labelledby="modalEditRelationshipLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditRelationshipLabel">DATA KELUARGA (FAMILY MEMBER OF KK)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="relationships" class="form-label">Status Perkawinan <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="marital_status" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="KAWIN TERCATAT">KAWIN TERCATAT</option>
                                <option value="BELUM KAWIN">BELUM KAWIN</option>
                            </select>
                            @error('form.relationships') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tanggal Perkawinan</label>
                            <input type="date" class="form-control" id="name" wire:model="wedding_date" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="relationships" class="form-label">Status Hubungan Keluarga <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="relationship_in_family" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                            @error('form.relationships') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Kewarganegaraan</label>
                            <select class="form-select" wire:model="citizenship" id="relationships" name="relationships">
                                <option selected value="">-- Pilih --</option>
                                <option value="WNI">WNI</option>
                                <option value="WNA">WNA</option>
                            </select>
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Ayah</label>
                            <input type="text" class="form-control" id="name" wire:model="father" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Ibu</label>
                            <input type="text" class="form-control" id="name" wire:model="mother" name="name">
                            @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='updateRelationship' wire:loading.attr="disabled" wire:target="updateRelationship">
                            <div wire:loading wire:target="updateRelationship" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="updateRelationship"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="updateRelationship">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditTanggungan" tabindex="-1" aria-labelledby="modalEditTanggunganLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditTanggunganLabel">Data Tanggungan (Suami/Istri/Anak)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hubungan Keluarga</label>
                            <select class="form-select" wire:model="relationships">
                                <option value="">-- Pilih --</option>
                                <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                                <option value="SUAMI">SUAMI</option>
                                <option value="ISTRI">ISTRI</option>
                                <option value="ANAK">ANAK</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control"
                                wire:model="name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <small class="text-danger">*</small></label>
                            <select class="form-select" wire:model="gender" id="gender" name="gender">
                                <option selected value="">-- Pilih --</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                            </select>
                            @error('form.gender') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" id="place_of_birth" wire:model="place_of_birth" name="place_of_birth">
                            @error('form.place_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="date_of_birth" wire:model="date_of_birth" name="date_of_birth">
                            @error('form.date_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="education" class="form-label">Pendidikan</label>
                            <select class="form-select" wire:model="education" id="education" name="education">
                                <option selected value="">-- Pilih --</option>
                                <option value="SMA">SMA</option>
                                <option value="SMP">SMP</option>
                                <option value="SD">SD</option>
                                <option value="DIPLOMA IV/STRATA 1">DIPLOMA IV/STRATA 1</option>
                            </select>
                            @error('form.education') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="profession" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="profession" wire:model="profession" name="profession">
                            @error('form.profession') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="no_telp" class="form-label">No Telephone</label>
                            <input type="text" class="form-control" id="no_telp" wire:model="no_telp" name="no_telp">
                            @error('form.no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='updateTanggungan' wire:loading.attr="disabled" wire:target="updateTanggungan">
                            <div wire:loading wire:target="updateTanggungan" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="updateTanggungan"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="updateTanggungan">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditPendidikan" tabindex="-1" aria-labelledby="modalEditPendidikanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditPendidikanLabel">RIWAYAT PENDIDIKAN (EDUCATIONAL BACKGROUND)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="level_of_education" class="form-label">Jenjang</label>
                            <input type="text" class="form-control" id="level_of_education" wire:model="level_of_education" name="level_of_education" placeholder="SMA/STRATA 1">
                            @error('form.level_of_education') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="institution" class="form-label">Nama Sekolah/Institut/Universitas</label>
                            <input type="text" class="form-control" id="institution" wire:model="institution" name="institution">
                            @error('form.institution') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tahun Mulai</label>
                            <input type="date" class="form-control" id="start_date" wire:model="start_date" name="start_date">
                            @error('form.start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tahun Akhir</label>
                            <input type="date" class="form-control" id="end_date" wire:model="end_date" name="end_date">
                            @error('form.end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major" class="form-label">Jurusan</label>
                            <input type="text" class="form-control" id="major" wire:model="major" name="major">
                            @error('form.major') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nilai" class="form-label">Nilai/IPK(CGPA)</label>
                            <input type="text" class="form-control" id="nilai" wire:model="nilai" name="nilai">
                            @error('form.nilai') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='updateEducation' wire:loading.attr="disabled" wire:target="updateEducation">
                            <div wire:loading wire:target="updateEducation" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="updateEducation"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="updateEducation">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modalEditExperience" tabindex="-1" aria-labelledby="modalEditExperienceLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white" id="modalEditExperienceLabel">PENGALAMAN KERJA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="mt-3 p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Perusahaan</label>
                            <input type="text" class="form-control" id="company" wire:model="company" name="company" placeholder="PT. xxx">
                            @error('form.company') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="employment_period" class="form-label">Periode Kerja</label>
                            <input type="text" class="form-control" id="employment_period" wire:model="employment_period" name="employment_period" placeholder="Jan 2025 - Des 2025">
                            @error('form.employment_period') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-md-auto" wire:click='updateExperience' wire:loading.attr="disabled" wire:target="updateExperience">
                            <div wire:loading wire:target="updateExperience" class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target="updateExperience"><i class="fa fa-save"></i> Simpan</span>
                            <span wire:loading wire:target="updateExperience">Loading...</span>
                        </button>
                        <button type="button" class="btn btn-secondary w-md-auto"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        Livewire.on('modalTambahKeluarga', (event) => {
            $('#modalTambahKeluarga').modal(event.action);
        });

        Livewire.on('modalTambahTanggungan', (event) => {
            $('#modalTambahTanggungan').modal(event.action);
        });

        Livewire.on('modalTambahPendidikan', (event) => {
            $('#modalTambahPendidikan').modal(event.action);
        });

        Livewire.on('modalTambahExperience', (event) => {
            $('#modalTambahExperience').modal(event.action);
        });

        Livewire.on('modalEditKeluarga', (event) => {
            $('#modalEditKeluarga').modal(event.action);
        });

        Livewire.on('modalEditRelationship', (event) => {
            $('#modalEditRelationship').modal(event.action);
        });

        Livewire.on('modalEditTanggungan', (event) => {
            $('#modalEditTanggungan').modal(event.action);
        });

        Livewire.on('modalEditPendidikan', (event) => {
            $('#modalEditPendidikan').modal(event.action);
        });

        Livewire.on('modalEditExperience', (event) => {
            $('#modalEditExperience').modal(event.action);
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-edit').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const row  = btn.closest('tr');
                    const cell = row.querySelector('.value-cell');
                    const field = row.dataset.field; // ambil nama field dari tr

                    if (!cell.querySelector('input')) {
                        const currentValue = cell.textContent.trim();

                        // cek apakah field adalah Mulai Masuk → pakai type="month"
                        if (field === 'start_date') {
                            // format ke YYYY-MM
                            let dateValue = currentValue !== '-' ? currentValue : '';
                            cell.innerHTML = `<input type="month" class="form-control form-control-sm" value="${dateValue}">`;
                        } else {
                            cell.innerHTML = `<input type="text" class="form-control form-control-sm" value="${currentValue === '-' ? '' : currentValue}">`;
                        }

                        btn.innerHTML = '<i class="bi bi-save"></i> Simpan';
                        btn.classList.remove('btn-warning');
                        btn.classList.add('btn-success');
                    } else {
                        // Mode save → ambil nilai, kirim ke server (ajax/Livewire)
                        const newValue = cell.querySelector('input').value;

                        fetch(`/karyawan/additional/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                karyawan_id: {{ $karyawan->id }},
                                field: field,
                                value: newValue
                            })
                        })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                cell.textContent = newValue || '-';
                                btn.innerHTML = '<i class="bi bi-pencil-square"></i>';
                                btn.classList.remove('btn-success');
                                btn.classList.add('btn-warning');
                            } else {
                                alert('Gagal menyimpan data');
                            }
                        });
                    }
                });
            });
        });

    </script>
@endpush
