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
    
        <div class="container d-flex justify-content">
            <div class="clock-card p-4 text-center w-100" style="max-width: 400px;">
                <h6 class="text-white mb-3">Live Attendance</h6>
    
                {{-- Jam live pakai Livewire polling --}}
                <h1 class="fw-bold" wire:poll.1s>{{ now()->format('H:i:s') }}</h1>
                <p class="mb-4">{{ now()->format('D, d M Y') }}</p>
    
                <div class="clock-inner">
                    <div class="fw-semibold mb-2">Normal</div>
                    <div class="fw-bold fs-5 mb-3">08:00 - 16:00</div>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-primary">Clock In</button>
                        <button class="btn btn-primary">Clock Out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .clock-card {
            border-radius: 15px;
            background-color: #a32020;
            color: white;
        }
        .clock-inner {
            background: white;
            color: black;
            border-radius: 10px;
            padding: 1rem;
        }
    </style>
    
</div>