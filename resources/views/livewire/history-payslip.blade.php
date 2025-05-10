<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Histori Slip Gaji</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">History Payslip</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Dokumen</th>
                                        <th>Periode Gaji</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><img src="./assets/img/pdf.png" alt="PDF"
                                                style="width: 30px; margin-right: 10px;"> Hana Nur Safira.pdf</td>
                                        <td>Mei 2025</td>
                                        <td>6 Juni 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-info"
                                                onclick="location.href='{{ route('slip-gaji') }}'">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <button wire:click="downloadSlip" class="btn btn-sm btn-success">
                                                <i class="fa fa-download"></i>
                                            </button>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./assets/img/pdf.png" alt="PDF"
                                                style="width: 30px; margin-right: 10px;"> Hana Nur Safira.pdf</td>
                                        <td>April 2025</td>
                                        <td>5 Mei 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-info"
                                                onclick="location.href='{{ route('slip-gaji') }}'">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                            <button class="btn btn-success btn-sm"><i
                                                    class="fa fa-download"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><img src="./assets/img/pdf.png" alt="PDF"
                                                style="width: 30px; margin-right: 10px;"> Hana Nur Safira.pdf</td>
                                        <td>Maret 2025</td>
                                         <td>8 April 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-info"
                                                onclick="location.href='{{ route('slip-gaji') }}'">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>

                                            <button class="btn btn-success btn-sm"><i
                                                    class="fa fa-download"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Showing 1 to 5 of 5 entries</span>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link">Previous</a></li>
                                    <li class="page-item active"><a class="page-link">1</a></li>
                                    <li class="page-item"><a class="page-link">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>