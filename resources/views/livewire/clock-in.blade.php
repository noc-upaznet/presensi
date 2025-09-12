<div>
    <style>
        .clock-card {
            border-radius: 15px;
            color: white;
        }

        .clock-before {
            background-color: #0d6efd; /* Biru - sebelum Clock In */
        }

        .clock-in-progress {
            background-color: #28a745; /* Hijau - setelah Clock In */
        }

        .clock-done {
            background-color: #dc3545; /* Merah - setelah Clock Out */
        }

        .history-card {
            border-radius: 15px;
        }

        .clock-inner {
            background: white;
            color: black;
            border-radius: 10px;
            padding: 1rem;
        }

        #video {
            transform: scaleX(-1);
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 2rem 1rem;
            }

            .clock-card {
                max-width: 100%;
                margin-bottom: 1rem;
            }

            .modal-dialog {
                max-width: 90%;
            }
        }
    </style>
    
    <div class="content-wrapper p-4">
        <div class="d-flex justify-content-between align-items-center mb-3" style="color: var(--bs-body-color);">
            <h3>Dashboard</h3>
            {{-- <div class="text-muted"><i class="fas fa-user"></i> {{ $userName }}</div> --}}
        </div>

        <div class="text mb-4" style="color: var(--bs-body-color); justify-content: center; align-items: center;">
            <p class="fs-5">Halo, {{ $userName }}</p>
        </div>

        <div class="d-flex flex-column justify-content-center" style="justify-content: center; align-items: center;">
            @php
                if (!$hasClockedIn) {
                    $cardClass = 'clock-before'; // Biru
                } elseif ($hasClockedIn && !$hasClockedOut) {
                    $cardClass = 'clock-in-progress'; // Hijau
                } else {
                    $cardClass = 'clock-done'; // Merah
                }
            @endphp

            <div class="clock-card {{ $cardClass }} p-4 text-center w-100"
                style="max-width: 500px;" wire:ignore>
                <h6 class="text-white mb-3">Live Attendance</h6>
                <h1 class="fw-bold" id="live-clock">--:--:--</h1>
                <p class="mb-4" id="live-date">Tanggal</p>
                <div class="clock-inner">
                    <div class="fw-semibold mb-2">Normal</div>
                    <div class="fw-bold fs-5 mb-3">{{ \Carbon\Carbon::parse($jamMasuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($jamKeluar)->format('H:i') }}</div>
                    <div class="d-flex justify-content-center flex-wrap">
                        @if ($jamMasuk === '00:00:00' && $jamKeluar === '00:00:00')
                            <span class="badge bg-danger text-light me-2 px-2" style="font-size: 20px;">
                                Jadwal tidak ada
                            </span>
                        @elseif (!$hasClockedIn)
                            <button class="btn btn-success px-4 me-2" wire:click="showCamera">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i> Clock In
                            </button>
                        @elseif (!$hasClockedOut)
                            <button class="btn btn-primary px-4 me-2" wire:click="showClockOutModal">
                                <i class="fas fa-arrow-right-from-bracket me-2"></i> Clock Out
                            </button>
                        @else
                            <span class="badge bg-primary text-light me-2 px-2" style="font-size: 20px;">
                                Presensi selesai
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kamera -->
    <div wire:ignore.self class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <div id="inputWrapper" style="display: none;">
                        <input type="file" accept="image/*" capture="environment" wire:model="photo">
                    </div>

                    <div id="cameraWrapper">
                        <video id="video" autoplay playsinline style="width: 100%; max-width: 480px;"></video>
                        
                        <canvas id="canvas" style="display:none;"></canvas>
                    </div>
                    {{-- <video id="video" width="100%" style="border-radius: 10px; background: #eee;"></video>
                    <canvas id="canvas" style="display:none; margin-top: 10px;"></canvas> --}}
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" id="btnTake" class="btn btn-primary" onclick="takePhoto()">Ambil Foto</button>
                    {{-- <button type="button" id="btnRetake" style="display: none;" class="btn btn-primary" onclick="retakePhoto()">Ambil Ulang</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Clock-Out -->
    <div wire:ignore.self class="modal fade" id="clockOutModal" tabindex="-1" aria-labelledby="clockOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clockOutModalLabel">Clock Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @if (session('error'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-circle" style="color: orange; font-size: 80px;"></i>
                    <div>
                        <strong>Apakah anda ingin clock out?</strong>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <input type="hidden" wire:model.live="latitude">
                    <input type="hidden" wire:model.live="longitude">
                    <button type="button" id="btnTake" class="btn btn-danger" wire:click="clockOut">Clock Out</button>
                </div>
            </div>
        </div>
    </div>


</div>

@push('scripts')
    <script>
        let stream = null;
        let video, canvas, context, btnTake, btnRetake;

        window.onload = function () {
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            context = canvas.getContext('2d');
            btnTake = document.getElementById('btnTake');
            btnRetake = document.getElementById('btnRetake');

            // startCamera();
        };
    
        function startCamera() {
            const video = document.getElementById('video');
    
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((s) => {
                    stream = s;
                    video.srcObject = stream;
                    video.play();  // Mulai video
                })
                .catch((err) => {
                    console.error('Gagal akses kamera', err);
                    alert('Gagal mengakses kamera. Pastikan izin kamera diberikan.');
                });
        }
    
        function stopCamera() {
            const video = document.getElementById('video');
    
            // Hentikan semua tracks
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
    
            // Reset video element
            video.pause();
            video.srcObject = null;
            video.removeAttribute('src');
            video.load();  // Reset video element
    
            // Force close camera to ensure light turns off
            if (typeof video.srcObject !== 'undefined') {
                video.srcObject = null;
            }
        }
    
        function takePhoto() {
            if (video.readyState === 4) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                context.save();
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                context.restore();

                // Ambil lokasi GPS sekarang
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((pos) => {
                        const lat = pos.coords.latitude.toFixed(6);
                        const lon = pos.coords.longitude.toFixed(6);

                        // Tambahkan teks koordinat di atas foto
                        context.font = "24px Arial";
                        context.fillStyle = "yellow";
                        context.strokeStyle = "black"; // outline biar jelas
                        context.lineWidth = 3;

                        const text = `Lat: ${lat}, Lon: ${lon}`;
                        const x = 20;
                        const y = canvas.height - 20;

                        context.strokeText(text, x, y);
                        context.fillText(text, x, y);

                        // Convert canvas ke Data URL
                        const photoDataUrl = canvas.toDataURL('image/png');
                        console.log("Foto dengan koordinat:", photoDataUrl);

                        // Kirim ke Livewire
                        Livewire.dispatch('photoTaken', { photo: photoDataUrl });
                        video.pause();
                    }, (err) => {
                        console.error("Gagal ambil lokasi:", err);
                        alert("Tidak bisa mengambil lokasi GPS!");
                    });
                } else {
                    alert("Browser tidak support geolocation");
                }
            } else {
                alert("Kamera belum siap. Silakan coba lagi.");
            }
        }

        Livewire.on('cameraModal', (event) => {
            $('#cameraModal').modal(event.action);
        });

        Livewire.on('clockOutModal', (event) => {
            $('#clockOutModal').modal(event.action);
        });

        Livewire.on('refresh', () => {
            window.location.reload(); 
        });
    
        // Event untuk buka modal
        const cameraModal = document.getElementById('cameraModal');
        cameraModal.addEventListener('shown.bs.modal', startCamera);
    
        // Event untuk tutup modal
        cameraModal.addEventListener('hidden.bs.modal', stopCamera);

        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Panggil fungsi setiap 1 detik
        setInterval(updateClock, 1000);
        updateClock(); // panggil sekali di awal agar tidak delay 1 detik

        // Date format
        function updateDate() {
            const now = new Date();
            const options = {
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };

            const formatted = now.toLocaleDateString('id-ID', options);
            
            // Ganti "Jumat" jadi "Jum'at" jika kamu mau menyesuaikan ejaan
            const finalFormatted = formatted.replace('Jumat', "Jum'at");

            document.getElementById('live-date').textContent = finalFormatted;
        }

        updateDate();

        function submitPhoto() {
            const canvas = document.getElementById('canvas');
            const photo = canvas.toDataURL('image/png');

            Livewire.dispatch('photoTaken', { photo: photo });
        }

        Livewire.on('swal', (e) => {
            Swal.fire(e.params);
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Dapatkan input hidden dan isi nilainya
                    document.querySelector('input[wire\\:model\\.live="latitude"]').value = position.coords.latitude;
                    document.querySelector('input[wire\\:model\\.live="longitude"]').value = position.coords.longitude;

                    // Trigger Livewire update manual
                    const inputLat = document.querySelector('input[wire\\:model\\.live="latitude"]');
                    const inputLng = document.querySelector('input[wire\\:model\\.live="longitude"]');
                    inputLat.dispatchEvent(new Event('input'));
                    inputLng.dispatchEvent(new Event('input'));
                }, function (error) {
                    console.error('Gagal mengambil lokasi:', error);
                    alert('Gagal mengambil lokasi. Pastikan GPS aktif.');
                });
            } else {
                alert('Geolocation tidak didukung oleh browser ini.');
            }
        });
        
    </script>
    
@endpush