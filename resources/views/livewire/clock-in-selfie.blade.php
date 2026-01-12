@assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endassets
<div>
    <style>
        #video {
            transform: scaleX(-1);
        }
    </style>
    <div class="content-wrapper p-4">
        @if (session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3" style="color: var(--bs-body-color);">
            <h3>Dashboard</h3>
        </div>
        <div wire:ignore>
            <div class="d-flex justify-content-end align-items-center mb-3">
                <h5 class="text-primary fw-bold mb-0" id="live-clock" wire:model="clockInTime">--:--:--</h5>
            </div>
            <div class="d-flex justify-content-end align-items-center" style="color: var(--bs-body-color);">
                <p class="mb-4" id="live-date" wire:model="tanggal">Tanggal</p>
            </div>
        </div>
        @if ($errorMessage)
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" x-data>
                {{ $errorMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @else
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert" x-data>
                <i class="bi bi-check-circle"></i> {{ $successMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Clock In Info Section -->
            <div class="col-md-6 mb-3">
                <div class="card" style="background-color: var(--bs-body-bg);">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <div class="mb-3">
                                <span>Lokasi : </span><br>
                                @if ($latitude && $longitude)
                                    <li class="badge bg-success text-light me-1">
                                        Koordinat: {{ $latitude }}, {{ $longitude }}
                                    </li>
                                @endif
                                <br>
                                @foreach ($lokasisTerdekat as $lokasi)
                                    <li class="badge bg-info text-dark me-1">
                                        {{ $lokasi->nama_lokasi }}
                                        <small class="text-muted">({{ number_format($lokasi->jarak * 1000, 0) }}
                                            m)</small>
                                    </li>
                                @endforeach
                            </div>
                            {{-- <button class="btn btn-danger btn-small" wire:click="reloadLocation"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="reloadLocation">
                                    <i class="bi bi-arrow-clockwise"></i> Lokasi
                                </span>

                                <span wire:loading wire:target="reloadLocation">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Memuat Lokasi...
                                </span>
                            </button> --}}
                        </div>
                        <div class="mb-1">
                            <label class="form-label fw-semibold d-block">Photo</label>
                            <div class="position-relative d-inline-block" id="photo-container">
                                @if ($photo)
                                    <img id="photoImage" src="{{ asset('storage/' . $photo) }}" alt="Selfie Photo"
                                        style="max-width: 100%; border-radius: 10px;" />
                                @else
                                    <img id="photoImage" src="" style="max-width: 100%; border-radius: 10px;" />
                                @endif
                                <div id="cameraWrapper">
                                    <video id="video" autoplay playsinline
                                        style="width: 100%; max-width: 480px;"></video>
                                    <canvas id="canvas" style="display:none;"></canvas>
                                    <div id="photoInfo" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <button id="clockInBtn"
                            class="btn w-100 mt-3 {{ $canClockIn ? 'btn-success' : 'btn-secondary' }}"
                            wire:click="clockIn" wire:loading.attr="disabled" wire:target="clockIn"
                            {{ $canClockIn ? '' : 'disabled' }}>
                            <span wire:loading.remove wire:target="clockIn">
                                {{ $canClockIn ? 'Ambil Foto' : 'Menunggu Lokasi...' }}
                            </span>
                            <span wire:loading wire:target="clockIn">
                                <i class="fas fa-spinner fa-spin me-2"></i> Proses Clock-In...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Map Section -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" wire:model.live="latitude">
                        <input type="hidden" wire:model.live="longitude">
                        <div wire:ignore>
                            <div id="map" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map = null;
        let userMarker = null;
        let markersGroup = null;
        let lokasiInterval = null;

        window.initMapOnce = function() {
            if (window.mapInitialized) return; // cegah init ganda
            window.mapInitialized = true;

            if (!navigator.geolocation) {
                Livewire.dispatch('lokasiError', {
                    message: 'Browser tidak mendukung geolokasi.',
                });
                return;
            }

            function updateLocation() {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        Livewire.dispatch('lokasiAwal', {
                            latitude: lat,
                            longitude: lng
                        });
                        console.log('📍 Lokasi Saat Ini:', lat, lng);

                        // === Inisialisasi map hanya sekali ===
                        if (!map) {
                            map = L.map('map').setView([lat, lng], 18);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors',
                            }).addTo(map);
                        }

                        // === Hapus marker user lama (kalau ada) ===
                        if (userMarker) {
                            map.removeLayer(userMarker);
                        }

                        // === Tambahkan marker baru ===
                        userMarker = L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                                iconSize: [32, 32],
                            }),
                        }).addTo(map).bindPopup('Posisi Anda').openPopup();

                        // === Geser peta ke posisi baru ===
                        map.panTo([lat, lng]);
                    },
                    (error) => {
                        console.error('❌ Gagal mendapatkan lokasi:', error);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0,
                    }
                );
            }

            updateLocation();
            lokasiInterval = setInterval(updateLocation, 2000);

            Livewire.on('lokasiStop', (data) => {
                console.log('Lokasi cukup dekat, hentikan update lokasi');
                clearInterval(lokasiInterval);
            });
        };

        // Jalankan sekali saat Livewire siap
        document.addEventListener('livewire:initialized', () => {
            initMapOnce();
            Livewire.on('ambilUlangLokasi', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            console.log('Lokasi diperbarui:', lat, lng);

                            Livewire.dispatch('lokasiAwal', {
                                latitude: lat,
                                longitude: lng
                            });
                        },
                        (error) => {
                            console.error('Gagal memperbarui lokasi:', error);
                            Livewire.dispatch('lokasiError', {
                                message: 'Tidak dapat memperbarui lokasi. Aktifkan GPS Anda.'
                            });
                        }
                    );
                } else {
                    Livewire.dispatch('lokasiError', {
                        message: 'Browser Anda tidak mendukung geolokasi.'
                    });
                }
            });
        });

        window.addEventListener('lokasi-terdekat-diperbarui', (event) => {
            const lokasisTerdekat = event.detail.lokasi || [];
            const radiusMeter = event.detail.radius || 40;

            if (!map) return;

            if (markersGroup) map.removeLayer(markersGroup);

            const layers = [];
            lokasisTerdekat.forEach((lokasi) => {
                if (!lokasi.koordinat) return;
                const [lat, lng] = lokasi.koordinat.split(',').map(Number);
                const marker = L.marker([lat, lng]).bindPopup(`<b>${lokasi.nama_lokasi}</b>`);
                const circle = L.circle([lat, lng], {
                    color: 'green',
                    fillColor: '#2ecc71',
                    fillOpacity: 0.25,
                    radius: radiusMeter,
                });
                layers.push(marker, circle);
            });

            if (layers.length > 0) {
                markersGroup = L.featureGroup(layers).addTo(map);
            }
        });

        document.addEventListener('DOMContentLoaded', async () => {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photoImage = document.getElementById('photoImage');
            const clockInBtn = document.getElementById('clockInBtn');
            let stream = null;

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user'
                    }
                });
                video.srcObject = stream;
                await new Promise(resolve => video.onloadeddata = resolve);
            } catch (error) {
                alert('Tidak dapat mengakses kamera. Pastikan izin kamera aktif.');
                return;
            }

            clockInBtn.addEventListener('click', async () => {
                if (!video.srcObject) {
                    alert('Kamera belum aktif.');
                    return;
                }

                clockInBtn.disabled = true;
                const originalText = clockInBtn.innerHTML;
                clockInBtn.innerHTML =
                    `<i class="fas fa-spinner fa-spin me-2"></i> Proses Clock-In...`;

                // Pastikan frame siap
                if (video.readyState < 2) {
                    await new Promise(resolve => video.onloadeddata = resolve);
                }

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;

                // Gambar frame dari kamera (mirror)
                context.save();
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                context.restore();

                // Ambil koordinat Livewire
                const latitude = @this.get('latitude');
                const longitude = @this.get('longitude');

                // Ambil waktu saat ini
                const now = new Date();
                const datetime = now.toLocaleString('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'medium'
                });

                // Watermak
                context.font = '20px Arial';
                context.fillStyle = 'rgba(0, 0, 0, 0.6)';

                context.strokeStyle = 'black';
                context.lineWidth = 3;

                context.fillStyle = 'yellow';

                const textTime = `🕒 ${datetime}`;
                const textY1 = canvas.height - 45;

                context.strokeText(textTime, 20, textY1);
                context.fillText(textTime, 20, textY1);

                const textLocation = `📍 Lat: ${latitude ?? '-'}, Lon: ${longitude ?? '-'}`;
                const textY2 = canvas.height - 20;

                context.strokeText(textLocation, 20, textY2);
                context.fillText(textLocation, 20, textY2);

                // Ambil hasil gambar
                const dataURL = canvas.toDataURL('image/jpeg', 0.9);
                photoImage.src = dataURL;

                // Kirim ke Livewire
                Livewire.dispatch('photoTaken', {
                    photo: dataURL,
                    latitude,
                    longitude,
                    datetime,
                });

                // Matikan kamera
                stream.getTracks().forEach(track => track.stop());
                video.style.display = 'none';
                clockInBtn.innerHTML = 'Foto Berhasil Diambil ✅';
            });
        });



        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

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
    </script>


</div>
