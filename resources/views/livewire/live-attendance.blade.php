<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard</h3>
        <div class="text-muted"><i class="fas fa-user"></i> Nadia Safira Khairunnisa</div>
    </div>

    <div class="text mb-4">
        <h1 class="fw-bold">Welcome!</h1>
        <p class="fs-5">Halo, Nadia Safira Khairunnisa</p>
    </div>

    <div>
        <div class="d-flex flex-column">
            <div class="full-card w-100" style="max-width: 450px; border-radius: 20px; overflow: hidden;">

                <!-- Mulai Card Attendance -->
                <div class="attendance-card p-0 text-center">

                    <!-- Alert di dalam card -->
                    <div class="custom-alert alert-info text-center">
                        Presensi telah diselesaikan.
                    </div>

                    <div class="p-4">
                        <h6 class="text-white mb-3">Live Attendance</h6>

                        <h1 class="fw-bold">14:20:01</h1>
                        <p class="mb-4">Fri, 25 Apr 2025</p>

                        <div class="inner-box mx-auto p-3">
                            <div class="fw-semibold text-muted mb-2">Normal</div>
                            <div class="fw-bold fs-5 mb-0">08:00 - 16:00</div>
                        </div>
                    </div>

                </div>

                <!-- Log Box -->
                <div class="log-box p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <strong>Attendance Log</strong>
                        <a href="{{ route('riwayat-presensi') }}" class="text-primary text-decoration-none">View Log</a>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">Clock In</div>
                            <small class="text-muted">07:50:56</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold">Clock Out</div>
                            <small class="text-muted">16:10:00</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .attendance-card {
            background-color: #a32020;
            color: white;
        }

        .custom-alert {
            background-color: #d1f2ff;
            padding: 12px 16px;
            font-weight: 500;
            font-size: 16px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
            border-radius: 0;
        }

        .inner-box {
            background: white;
            border-radius: 15px;
            color: black;
            max-width: 320px;
            margin-top: 1rem;
        }

        .log-box {
            background: white;
            color: black;
        }

        .btn-primary {
            border-radius: 8px;
        }

        .full-card {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsif untuk mobile */
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 2rem 1rem;
            }

            .full-card {
                max-width: 100%;
                margin-bottom: 1rem;
            }

            .attendance-card {
                padding: 2rem;
            }

            .inner-box {
                max-width: 100%;
                padding: 1rem;
            }

            .log-box {
                padding: 1rem;
            }

            .text-end {
                text-align: left;
            }

            .btn-primary {
                width: 100%;
            }
        }
    </style>
</div>
