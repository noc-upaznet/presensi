<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Dashboard Payroll</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard Payroll</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-3">
                <input type="month" wire:model.live="periode" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($total_gaji, 0, ',', '.') }}</h4>
                        <span><b>Total Gaji {{ $currentEntitas }}</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($total_gaji_titip, 0, ',', '.') }}</h4>
                        <span><b>Total Gaji {{ $currentEntitas }} Titip</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($bpjs_kes_pt, 0, ',', '.') }}</h4>
                        <span><b>BPJS Kes PT {{ $currentEntitas }}</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($bpjs_jht_pt, 0, ',', '.') }}</h4>
                        <span><b>BPJS JHT PT {{ $currentEntitas }}</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($potongan_terlambat, 0, ',', '.') }}</h4>
                        <span><b> Potongan <br> Terlambat {{ $currentEntitas }}</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($potongan_terlambat_titip, 0, ',', '.') }}</h4>
                        <span><b> Potongan <br> Terlambat {{ $currentEntitas }} Titip</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($churn, 0, ',', '.') }}</h4>
                        <span><b> Churn <br> {{ $currentEntitas }}</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success">
                    <div class="inner pe-5">
                        <h4>Rp. {{ number_format($churn_titip, 0, ',', '.') }}</h4>
                        <span><b> Churn <br> {{ $currentEntitas }} Titip</b></span>
                    </div>
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M12 1.5a.75.75 0 01.75.75V3c2.48.33 4.25 1.87 4.25 3.75a.75.75 0 01-1.5 0c0-1.24-1.74-2.25-3.5-2.25S8.5 5.51 8.5 6.75c0 1.1 1.1 1.77 3.18 2.31l.64.17c2.48.65 4.68 1.68 4.68 4.02 0 2.04-1.77 3.58-4.25 3.9v.85a.75.75 0 01-1.5 0v-.85c-2.63-.33-4.5-1.92-4.5-4a.75.75 0 011.5 0c0 1.38 1.85 2.5 3.75 2.5s3.75-1.12 3.75-2.5c0-1.29-1.32-1.94-3.5-2.5l-.7-.18C9.2 9.53 7 8.5 7 6.75c0-1.88 1.77-3.42 4.25-3.75V2.25a.75.75 0 01.75-.75z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
