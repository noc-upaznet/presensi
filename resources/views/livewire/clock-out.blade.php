<div>
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

                    <div class="attendance-card p-4 text-center">
                        <h6 class="text-white mb-3">Live Attendance</h6>

                        {{-- Jam --}}
                        <h1 class="fw-bold">14:20:01</h1>
                        <p class="mb-4">Fri, 25 Apr 2025</p>


                        <div class="inner-box mx-auto p-3">
                            <div class="fw-semibold text-muted mb-2">Normal</div>
                            <div class="fw-bold fs-5 mb-3">08:00 - 16:00</div>
                            <button class="btn btn-success px-4">
                                <i class="fas fa-arrow-right-from-bracket me-2"></i> Clock Out
                            </button>
                        </div>
                    </div>
                    <div class="log-box p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Attendance Log</strong><br>
                                <small>07:50:56</small> - Clock In
                            </div>
                            <a href="#" class="text-primary text-decoration-none">View Log</a>
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

            .inner-box {
                background: white;
                border-radius: 15px;
                color: black;
                max-width: 320px;
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
        </style>
    </div>