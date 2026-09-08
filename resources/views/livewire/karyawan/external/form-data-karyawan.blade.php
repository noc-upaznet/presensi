<div class="employee-form">
    <style>
        /* =========================================================
       EMPLOYEE DATA FORM
    ========================================================= */

        .employee-form {
            background: #f5f7fb;
            min-height: 100vh;
            padding: 32px 0 60px;
        }

        .employee-form .container {
            max-width: 1200px;
        }

        /* ================= HEADER ================= */

        .employee-form .employee-header {
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e9edf5;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
        }

        .employee-form .employee-header::before {
            content: "";
            display: block;
            height: 5px;
            background: linear-gradient(90deg, #2563eb, #4f46e5);
        }

        .employee-form .employee-header .card-body {
            padding: 28px;
        }

        .employee-form .employee-title {
            font-size: 25px;
            font-weight: 750;
            color: #172033;
            letter-spacing: -.4px;
        }

        .employee-form .employee-subtitle {
            color: #7b8497;
            font-size: 14px;
        }

        .employee-form .employee-identity {
            background: #f7f9fc;
            border: 1px solid #edf0f5;
            border-radius: 12px;
            padding: 16px 18px;
        }

        .employee-form .employee-identity small {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            color: #8992a5;
        }

        .employee-form .employee-identity .fw-semibold {
            color: #273247;
            font-size: 15px;
        }

        /* ================= ALERT ================= */

        .employee-form .alert {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        }

        /* ================= MAIN CARD ================= */

        .employee-form .employee-main-card {
            background: #ffffff;
            border: 1px solid #e9edf5;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .employee-form .employee-main-card .card-body {
            padding: 28px;
        }

        /* ================= TAB ================= */

        .employee-form .employee-tabs {
            display: flex;
            gap: 8px;
            padding: 6px;
            margin-bottom: 28px;
            background: #f5f7fb;
            border-radius: 12px;
            overflow-x: auto;
        }

        .employee-form .employee-tabs .nav-item {
            flex: 1;
            min-width: max-content;
        }

        .employee-form .employee-tabs .nav-link {
            width: 100%;
            border: 0;
            border-radius: 9px;
            padding: 11px 18px;
            color: #697386;
            font-size: 14px;
            font-weight: 600;
            background: transparent;
            transition: all .2s ease;
        }

        .employee-form .employee-tabs .nav-link:hover {
            color: #2563eb;
            background: #ffffff;
        }

        .employee-form .employee-tabs .nav-link.active {
            color: #ffffff;
            background: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .22);
        }

        /* ================= SECTION ================= */

        .employee-form .section-heading h5 {
            color: #172033;
            font-size: 18px;
            font-weight: 700;
        }

        .employee-form .section-heading small {
            color: #8a93a5;
        }

        /* ================= DATA CARD ================= */

        .employee-form .data-card {
            background: #ffffff;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 16px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .employee-form .data-card:hover {
            border-color: #d8dfeb;
            box-shadow: 0 5px 18px rgba(15, 23, 42, .04);
        }

        .employee-form .data-card strong {
            color: #273247;
            font-size: 14px;
        }

        /* ================= FORM ================= */

        .employee-form .form-label {
            color: #4b5568;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .employee-form .form-control,
        .employee-form .form-select {
            min-height: 43px;
            border: 1px solid #dfe4ec;
            border-radius: 9px;
            color: #273247;
            font-size: 14px;
            box-shadow: none;
            transition: all .2s ease;
        }

        .employee-form .form-control::placeholder {
            color: #a4acba;
        }

        .employee-form .form-control:focus,
        .employee-form .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
        }

        /* ================= BUTTON ================= */

        .employee-form .btn {
            border-radius: 9px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .employee-form .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }

        .employee-form .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .employee-form .btn-success:hover,
        .employee-form .btn-outline-danger:hover {
            transform: translateY(-1px);
        }

        /* ================= EMPTY ================= */

        .employee-form .empty-state {
            padding: 45px 20px;
            border: 1px dashed #dfe4ec;
            border-radius: 14px;
            background: #fafbfc;
            color: #8b94a5;
        }

        /* ================= SAVE ================= */

        .employee-form .save-area {
            border-top: 1px solid #edf0f5;
            margin-top: 28px;
            padding-top: 22px;
        }

        /* ================= MOBILE ================= */

        @media (max-width: 767.98px) {

            .employee-form {
                padding: 16px 0 40px;
            }

            .employee-form .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .employee-form .employee-header .card-body,
            .employee-form .employee-main-card .card-body {
                padding: 18px;
            }

            .employee-form .employee-title {
                font-size: 21px;
            }

            .employee-form .employee-tabs {
                margin-right: -18px;
                margin-left: -18px;
                padding-left: 18px;
                padding-right: 18px;
                border-radius: 0;
            }

            .employee-form .employee-tabs .nav-item {
                flex: 0 0 auto;
            }

            .employee-form .employee-tabs .nav-link {
                width: auto;
                padding: 10px 14px;
                font-size: 13px;
            }

            .employee-form .data-card {
                padding: 16px;
            }

            .employee-form .d-flex.justify-content-between {
                align-items: flex-start !important;
                gap: 12px;
            }

            .employee-form .d-flex.justify-content-between .btn {
                flex-shrink: 0;
            }

            .employee-form .save-area {
                text-align: center !important;
            }

            .employee-form .save-area .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {

            .employee-form .employee-title {
                font-size: 19px;
            }

            .employee-form .employee-subtitle {
                font-size: 13px;
            }

            .employee-form .data-card {
                padding: 14px;
            }

            .employee-form .form-control,
            .employee-form .form-select {
                min-height: 42px;
                font-size: 13px;
            }
        }
    </style>
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="card employee-header border-0 shadow-sm mb-4">

            <div class="card-body">

                <h3 class="employee-title mb-1">
                    Lengkapi Data Karyawan
                </h3>

                <p class="employee-subtitle mb-3">
                    Silakan lengkapi data pribadi Anda dengan benar.
                </p>

                <div class="employee-identity">

                    <div class="row">

                        <div class="col-md-6">

                            <small>
                                Nama Karyawan
                            </small>

                            <div class="fw-semibold">
                                {{ $karyawan->nama_karyawan ?? '-' }}
                            </div>

                        </div>
                        <div class="col-md-6">

                            <small>
                                Divisi
                            </small>

                            <div class="fw-semibold">
                                {{ $karyawan->divisi ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- SUCCESS --}}
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        {{-- TAB --}}
        <div class="card employee-main-card border-0 shadow-sm">

            <div class="card-body">

                <ul class="nav nav-pills employee-tabs mb-4">

                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $activeTab === 'family' ? 'active' : '' }}"
                            wire:click="setTab('family')">
                            Data Keluarga
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $activeTab === 'dependent' ? 'active' : '' }}"
                            wire:click="setTab('dependent')">
                            Data Tanggungan
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $activeTab === 'education' ? 'active' : '' }}"
                            wire:click="setTab('education')">
                            Pendidikan
                        </button>
                    </li>

                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $activeTab === 'experience' ? 'active' : '' }}"
                            wire:click="setTab('experience')">
                            Pengalaman Kerja
                        </button>
                    </li>

                </ul>


                {{-- =====================================================
                     DATA KELUARGA
                ====================================================== --}}

                @if ($activeTab === 'family')

                    <div class="section-heading d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Data Keluarga
                            </h5>

                            <small>
                                Isi data anggota keluarga Anda.
                            </small>

                        </div>

                        <button type="button" class="btn btn-primary btn-sm" wire:click="addFamily">
                            + Tambah Keluarga
                        </button>

                    </div>


                    @forelse ($familys as $index => $family)
                        <div class="data-card">

                            <div class="d-flex justify-content-between mb-3">

                                <strong>
                                    Data Keluarga #{{ $index + 1 }}
                                </strong>

                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    wire:click="removeFamily({{ $index }})"
                                    wire:confirm="Hapus data keluarga ini?">
                                    Hapus
                                </button>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Hubungan Keluarga
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.relationships"
                                        placeholder="Contoh: Ayah">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Nama
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        NIK
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.nik">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Jenis Kelamin
                                    </label>

                                    <select class="form-select" wire:model="familys.{{ $index }}.gender">

                                        <option value="">
                                            -- Pilih --
                                        </option>

                                        <option value="Laki-laki">
                                            Laki-laki
                                        </option>

                                        <option value="Perempuan">
                                            Perempuan
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tempat Lahir
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.place_of_birth">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tanggal Lahir
                                    </label>

                                    <input type="date" class="form-control"
                                        wire:model="familys.{{ $index }}.date_of_birth">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Agama
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.religion">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Pendidikan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.education">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Status Perkawinan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.marital_status">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tanggal Perkawinan
                                    </label>

                                    <input type="date" class="form-control"
                                        wire:model="familys.{{ $index }}.wedding_date">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Status Hubungan Dalam Keluarga
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.relationship_in_family">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Kewarganegaraan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.citizenship">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Ayah
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.father">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Ibu
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="familys.{{ $index }}.mother">
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-state text-center">

                            <div class="mb-2">
                                Belum ada data keluarga.
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" wire:click="addFamily">
                                + Tambah Data Keluarga
                            </button>

                        </div>
                    @endforelse

                @endif


                {{-- =====================================================
                     TANGGUNGAN
                ====================================================== --}}

                @if ($activeTab === 'dependent')

                    <div class="section-heading d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Data Tanggungan
                            </h5>

                            <small>
                                Data suami, istri, atau anak yang menjadi tanggungan.
                            </small>

                        </div>

                        <button type="button" class="btn btn-primary btn-sm" wire:click="addDependent">
                            + Tambah Tanggungan
                        </button>

                    </div>


                    @forelse ($dependents as $index => $dependent)
                        <div class="data-card">

                            <div class="d-flex justify-content-between mb-3">

                                <strong>
                                    Tanggungan #{{ $index + 1 }}
                                </strong>

                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    wire:click="removeDependent({{ $index }})"
                                    wire:confirm="Hapus data tanggungan ini?">
                                    Hapus
                                </button>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Hubungan Keluarga
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.relationships"
                                        placeholder="Contoh: Anak">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Nama
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.name">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Jenis Kelamin
                                    </label>

                                    <select class="form-select" wire:model="dependents.{{ $index }}.gender">

                                        <option value="">
                                            -- Pilih --
                                        </option>

                                        <option value="Laki-laki">
                                            Laki-laki
                                        </option>

                                        <option value="Perempuan">
                                            Perempuan
                                        </option>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tempat Lahir
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.place_of_birth">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Tanggal Lahir
                                    </label>

                                    <input type="date" class="form-control"
                                        wire:model="dependents.{{ $index }}.date_of_birth">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Pendidikan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.education">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Pekerjaan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.profession">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        No Telephone
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="dependents.{{ $index }}.no_telp">
                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-state text-center">

                            Belum ada data tanggungan.

                            <div class="mt-2">

                                <button type="button" class="btn btn-primary btn-sm" wire:click="addDependent">
                                    + Tambah Tanggungan
                                </button>

                            </div>

                        </div>
                    @endforelse

                @endif


                {{-- =====================================================
                     PENDIDIKAN
                ====================================================== --}}

                @if ($activeTab === 'education')

                    <div class="section-heading d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Riwayat Pendidikan
                            </h5>

                            <small>
                                Masukkan seluruh riwayat pendidikan.
                            </small>

                        </div>

                        <button type="button" class="btn btn-primary btn-sm" wire:click="addEducation">
                            + Tambah Pendidikan
                        </button>

                    </div>


                    @forelse ($educations as $index => $education)
                        <div class="data-card">

                            <div class="d-flex justify-content-between mb-3">

                                <strong>
                                    Pendidikan #{{ $index + 1 }}
                                </strong>

                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    wire:click="removeEducation({{ $index }})"
                                    wire:confirm="Hapus data pendidikan ini?">
                                    Hapus
                                </button>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Jenjang Pendidikan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.level_of_education"
                                        placeholder="Contoh: S1">

                                </div>

                                <div class="col-md-8">

                                    <label class="form-label">
                                        Nama Sekolah / Institut
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.institution">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Tahun Mulai
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.start_date"
                                        placeholder="Contoh: 2018">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Tahun Akhir
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.end_date"
                                        placeholder="Contoh: 2022">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Jurusan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.major">

                                </div>

                                <div class="col-md-4">

                                    <label class="form-label">
                                        Nilai / IPK (CGPA)
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="educations.{{ $index }}.nilai">

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-state text-center">

                            Belum ada data pendidikan.

                            <div class="mt-2">

                                <button type="button" class="btn btn-primary btn-sm" wire:click="addEducation">
                                    + Tambah Pendidikan
                                </button>

                            </div>

                        </div>
                    @endforelse

                @endif


                {{-- =====================================================
                     PENGALAMAN KERJA
                ====================================================== --}}

                @if ($activeTab === 'experience')

                    <div class="section-heading d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Pengalaman Kerja
                            </h5>

                            <small>
                                Masukkan pengalaman pekerjaan sebelumnya.
                            </small>

                        </div>

                        <button type="button" class="btn btn-primary btn-sm" wire:click="addWorkExperience">
                            + Tambah Pengalaman
                        </button>

                    </div>


                    @forelse ($workExperiences as $index => $experience)
                        <div class="data-card">

                            <div class="d-flex justify-content-between mb-3">

                                <strong>
                                    Pengalaman Kerja #{{ $index + 1 }}
                                </strong>

                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    wire:click="removeWorkExperience({{ $index }})"
                                    wire:confirm="Hapus pengalaman kerja ini?">
                                    Hapus
                                </button>

                            </div>


                            <div class="row g-3">

                                <div class="col-md-7">

                                    <label class="form-label">
                                        Perusahaan
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="workExperiences.{{ $index }}.company">

                                </div>

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Lama Kerja
                                    </label>

                                    <input type="text" class="form-control"
                                        wire:model="workExperiences.{{ $index }}.employment_period"
                                        placeholder="Contoh: 2 Tahun">

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-state text-center">

                            Belum ada pengalaman kerja.

                            <div class="mt-2">

                                <button type="button" class="btn btn-primary btn-sm" wire:click="addWorkExperience">
                                    + Tambah Pengalaman
                                </button>

                            </div>

                        </div>
                    @endforelse

                @endif


                {{-- SAVE --}}
                <div class="save-area text-end">

                    <button type="button" class="btn btn-success px-4" wire:click="saveAll"
                        wire:loading.attr="disabled">

                        <span wire:loading.remove wire:target="saveAll">
                            Simpan Semua Data
                        </span>

                        <span wire:loading wire:target="saveAll">
                            Menyimpan...
                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
