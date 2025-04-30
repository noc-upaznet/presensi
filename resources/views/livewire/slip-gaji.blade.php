<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Slip Gaji</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Slip Gaji</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 bg-light">
        <div class="container px-0 px-md-3" style="max-width: 900px;">
            <div class="bg-white p-3 p-md-4 shadow rounded border border-light-subtle">
                <div class="bg-primary text-white px-3 px-md-4 py-2 rounded-top d-flex justify-content-between">
                    <div><strong>Slip Number : DJB/HR/130</strong></div>
                    <div>Maret 2025</div>
                </div>

                <div class="px-3 px-md-4 py-3 border-bottom d-flex align-items-center gap-3">
                    <img src="./assets/img/user4-128x128.jpg" width="50" class="rounded-circle" alt="Photo" />
                    <div>
                        <div><strong>Nadia Safira Khairunnisa</strong></div>
                        <div class="text-muted small">Admin HR</div>
                    </div>
                </div>

                <div class="row p-3 p-md-4">
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <h6 class="fw-bold text-primary">Pendapatan</h6>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between"><span>Upah
                                    Pokok</span><strong>Rp2.000.000</strong></li>
                            <li class="d-flex justify-content-between"><span>Tunjangan
                                    Jabatan</span><strong>Rp500.000</strong></li>
                            <li class="d-flex justify-content-between"><span>Tunjangan
                                    Kehadiran</span><strong>Rp0</strong></li>
                            <li class="d-flex justify-content-between"><span>Upah Lembur</span><strong>Rp0</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>Uang
                                    Makan</span><strong>Rp260.000</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>Uang
                                    Transport</span><strong>Rp0</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>Bonus</span><strong>Rp0</strong></li>
                            <li class="d-flex justify-content-between"><span>Tunjangan
                                    Kebudayaan</span><strong>Rp0</strong></li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-6">
                        <h6 class="fw-bold text-danger">Potongan</h6>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between"><span>Potongan
                                    Terlambat</span><strong>Rp0</strong></li>
                            <li class="d-flex justify-content-between"><span>Potongan
                                    Izin</span><strong>Rp0</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>Kas Bon</span><strong>Rp0</strong></li>
                            <li class="d-flex justify-content-between"><span>JKK</span><strong>-Rp20.000</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>PPh21</span><strong>-Rp25.000</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>JHT</span><strong>-Rp40.000</strong>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>Kesehatan</span><strong>-Rp20.000</strong>
                            </li>
                            <li class="d-flex justify-content-between"><span>Voucher</span><strong>Rp0</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="px-3 px-md-4 pb-4 text-center">
                    <hr>
                    <h6 class="fw-bold text-primary">Gaji Bersih</h6>
                    <h4 class="fw-bold">Rp2.655.000</h4>
                    <button wire:click="downloadSlip" class="btn btn-primary mt-2">
                        Download Slip Gaji Maret 2025
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.addEventListener('redirect-download', event => {
            window.open(event.detail.url, '_blank');
        });
</script>
@endpush