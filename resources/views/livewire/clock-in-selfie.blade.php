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
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert" x-data
                x-init="setTimeout(() => $el.remove(), 4000)">
                {{ $errorMessage }}
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
                            <button class="btn btn-danger btn-small" wire:click="reloadLocation"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="reloadLocation">
                                    <i class="bi bi-arrow-clockwise"></i> Lokasi
                                </span>

                                <span wire:loading wire:target="reloadLocation">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    Memuat Lokasi...
                                </span>
                            </button>
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
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success w-100 mt-3" id="clockInBtn">
                            <span id="btnText">Ambil Foto</span>
                            <span id="btnSpinner" class="d-none">
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
                        console.log('📍 Lokasi dikirim:', lat, lng);

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

            // Jalankan pertama kali dan tiap 5 detik
            updateLocation();
            lokasiInterval = setInterval(updateLocation, 2000);

            Livewire.on('lokasiStop', (data) => {
                console.log('⛔ Lokasi cukup dekat, hentikan update:', data.message);
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

                            // Kirim ke Livewire lagi
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

        // === Event lokasi kantor ===
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
                // ⚠️ Jangan pakai fitBounds() otomatis, agar tidak re-render user marker
                // map.fitBounds(markersGroup.getBounds().pad(0.3));
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
                console.log('Kamera aktif');
            } catch (error) {
                console.error('Gagal mengakses kamera:', error);
                alert('Tidak dapat mengakses kamera. Pastikan izin kamera diaktifkan.');
                return;
            }

            clockInBtn.addEventListener('click', () => {
                if (!video.srcObject) {
                    alert('Kamera belum aktif.');
                    return;
                }

                clockInBtn.disabled = true;
                const originalText = clockInBtn.innerHTML;
                clockInBtn.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i> Proses Clock-In...`;

                if (video && canvas && photoImage) {
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Balik horizontal (supaya tidak mirror)
                    context.save();
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    context.restore();

                    // Ambil lokasi GPS
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((pos) => {
                            const lat = pos.coords.latitude.toFixed(6);
                            const lon = pos.coords.longitude.toFixed(6);

                            // Format tanggal & jam
                            const now = new Date();
                            const dateTime = now.toLocaleString('id-ID', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });

                            // Tambahkan teks koordinat + tanggal di atas foto
                            context.font = "24px Arial";
                            context.fillStyle = "yellow";
                            context.strokeStyle = "black"; // outline biar jelas
                            context.lineWidth = 3;

                            const coordText = `Lat: ${lat}, Lon: ${lon}`;
                            const timeText = dateTime;

                            // Koordinat di bawah
                            const coordX = 20;
                            const coordY = canvas.height - 20;
                            context.strokeText(coordText, coordX, coordY);
                            context.fillText(coordText, coordX, coordY);

                            // Tanggal & jam sedikit di atas koordinat
                            const timeX = 20;
                            const timeY = canvas.height - 50;
                            context.strokeText(timeText, timeX, timeY);
                            context.fillText(timeText, timeX, timeY);

                            // Convert ke Data URL
                            const dataURL = canvas.toDataURL('image/jpeg', 0.7);
                            photoImage.src = dataURL;

                            // Kirim ke Livewire + data
                            Livewire.dispatch('photoTaken', {
                                photo: dataURL,
                                latitude: lat,
                                longitude: lon,
                                datetime: dateTime
                            });

                            // Tutup modal kamera
                            // const modalEl = document.getElementById('cameraModal');
                            // if (modalEl) {
                            //     const modal = bootstrap.Modal.getInstance(modalEl);
                            //     modal.hide();
                            // }
                        }, (err) => {
                            console.error("Gagal ambil lokasi:", err);
                            alert("Tidak bisa mengambil lokasi GPS!");
                        });
                    } else {
                        alert("Browser tidak support geolocation");
                    }

                    // Matikan kamera setelah capture
                    const tracks = stream.getTracks();
                    tracks.forEach(track => track.stop());
                    video.style.display = 'none';
                }
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
