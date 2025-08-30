@assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endassets
<div>
    <style>
        #video {
            transform: scaleX(-1); /* Ini memastikan tidak mirror */
        }
    </style>
    <div class="content-wrapper p-4">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3" style="color: var(--bs-body-color);">
            <h3>Dashboard</h3>
            {{-- <div class="text-muted"><i class="fas fa-user"></i> Nadia Safira Khairunnisa</div> --}}
        </div>
        <div wire:ignore>
            <div class="d-flex justify-content-end align-items-center mb-3">
                <h5 class="text-primary fw-bold mb-0" id="live-clock" wire:model="clockInTime">--:--:--</h5>
            </div>
            <div class="d-flex justify-content-end align-items-center mb-3" style="color: var(--bs-body-color);">
                <p class="mb-4" id="live-date" wire:model="tanggal">Tanggal</p>
            </div>
        </div>
        

        <div class="row">
            <!-- Map Section -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" wire:model.live="latitude">
                        <input type="hidden" wire:model.live="longitude">
                        <div id="map" style="height: 300px; width: 100%;" wire:ignore></div>
                    </div>
                </div>
            </div>

            <!-- Clock In Info Section -->
            <div class="col-md-6 mb-3">
                <div class="card" style="background-color: var(--bs-body-bg);">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Notes</label>
                            <div class="mb-3">
                                <span>Lokasi : </span>
                                @foreach ($lokasisTerdekat as $lokasi)
                                    <li class="badge bg-info text-dark me-1">
                                        {{ $lokasi->nama_lokasi }}
                                        <small class="text-muted" wire:ignore>({{ number_format($lokasi->jarak * 1000, 0) }} m)</small>
                                    </li>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Photo</label>
                            <div class="position-relative d-inline-block" id="photo-container">
                                @if ($photo)
                                    <img id="photoImage" src="{{ asset('storage/' . $photo) }}" alt="Selfie Photo" style="max-width: 100%; border-radius: 10px;" />
                                @else
                                    <img id="photoImage" src="" alt="Belum ada foto" style="max-width: 100%; border-radius: 10px;" />
                                @endif
                                <button type="button"
                                    class="btn btn-danger p-0 d-flex justify-content-center align-items-center position-absolute start-100 translate-middle"
                                    style="top: 6px; right: -6px; width: 24px; height: 24px; border-radius: 50%; font-size: 18px; line-height: 1;"
                                    onclick="removePhoto()">
                                    &minus;
                                </button>
                            </div>
                        </div>

                        <!-- Modal Kamera -->
                        <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="cameraModalLabel">Ambil Foto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="inputWrapper" style="display: none;">
                                            <input type="file" accept="image/*" capture="environment" wire:model="photo">
                                        </div>
                    
                                        <div id="cameraWrapper">
                                            <video id="video" autoplay playsinline style="width: 100%; max-width: 480px;"></video>
                                            <!--<button onclick="takePhoto()">Ambil Foto</button>-->
                                            <!--<button type="button" id="btnTake" class="btn btn-primary" onclick="takePhoto()">Ambil Foto</button>-->
                                            <canvas id="canvas" style="display:none;"></canvas>
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" id="btnTake" class="btn btn-primary" onclick="capturePhoto()">Ambil Foto</button>
                                        <!--{{-- <button type="button" id="btnRetake" style="display: none;" class="btn btn-primary" onclick="retakePhoto()">Ambil Ulang</button> --}}-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-3" wire:click="clockIn">Clock In</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let videoStream = null;

        function removePhoto() {
            if (confirm('Ingin ambil ulang foto?')) {
                const photoImage = document.getElementById('photoImage');
                const video = document.getElementById('video');
                const modalEl = document.getElementById('cameraModal');
                
                // if (photoImage) {
                //     photoImage.src = './assets/img/user4-128x128.jpg';
                // }

                if (!modalEl || !video) {
                    console.error('Elemen modal atau video tidak ditemukan');
                    return;
                }

                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                modalEl.addEventListener('shown.bs.modal', function handleModalOpen() {
                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                        navigator.mediaDevices.getUserMedia({ video: true })
                            .then(stream => {
                                video.srcObject = stream;
                                videoStream = stream;
                                video.play();
                            })
                            .catch(error => {
                                console.error('Gagal akses kamera', error);
                            });
                    } else {
                        console.log('Media Devices API tidak didukung browser ini');
                    }

                    modalEl.removeEventListener('shown.bs.modal', handleModalOpen);
                });

                modalEl.addEventListener('hidden.bs.modal', function handleModalClose() {
                    if (videoStream) {
                        const tracks = videoStream.getTracks();
                        tracks.forEach(track => track.stop());
                        videoStream = null;
                        video.srcObject = null;
                    }

                    modalEl.removeEventListener('hidden.bs.modal', handleModalClose);
                });
            }
        }

        function capturePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const photoImage = document.getElementById('photoImage');

            if (video && canvas && photoImage) {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                // Balik horizontal saat menggambar ke canvas (agar tidak mirror)
                context.save();
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                context.restore();

                const dataURL = canvas.toDataURL('image/png');
                photoImage.src = dataURL;

                // Kirim ke Livewire
                Livewire.dispatch('photoTaken', { photo: dataURL });

                const modalEl = document.getElementById('cameraModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();
                }
            }
        }

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

        var map = null;
        var markersGroup = null;
    
        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
        
                        // Isi input tersembunyi Livewire
                        const inputLat = document.querySelector('input[wire\\:model\\.live="latitude"]');
                        const inputLng = document.querySelector('input[wire\\:model\\.live="longitude"]');
        
                        if (inputLat && inputLng) {
                            inputLat.value = lat;
                            inputLng.value = lng;
                            inputLat.dispatchEvent(new Event('input', { bubbles: true }));
                            inputLng.dispatchEvent(new Event('input', { bubbles: true }));
                        }
        
                        console.log("Lokasi berhasil:", lat, lng);
        
                        // Inisialisasi peta jika belum ada
                        if (!window.map) {
                            window.map = L.map('map').setView([lat, lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(map);
                        }
        
                        // Tambahkan marker posisi pengguna
                        L.marker([lat, lng], {
                            icon: L.icon({
                                iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                                iconSize: [32, 32]
                            })
                        }).addTo(map).bindPopup('Posisi Anda');
                    },
                    function (error) {
                        console.error("Gagal mendapatkan lokasi:", error);
                        alert("Gagal mengambil lokasi. Aktifkan GPS dan izinkan akses lokasi.");
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                alert("Browser tidak mendukung geolocation.");
            }
        });
    
        // Render marker dari event Livewire
        window.addEventListener('lokasi-terdekat-diperbarui', function (event) {
            const lokasiData = event.detail.lokasi;
    
            if (!map) {
                map = L.map('map').setView([-7.290293, 112.727097], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
            }
    
            if (markersGroup) {
                markersGroup.clearLayers();
            }
    
            const markers = lokasiData.map(lokasi => {
                if (!lokasi.koordinat) return null;
            
                const [lat, lng] = lokasi.koordinat.split(',').map(Number);
                return L.marker([lat, lng]).bindPopup(`<b>${lokasi.nama_lokasi}</b>`);
            }).filter(Boolean);
    
            markersGroup = L.featureGroup(markers).addTo(map);
            map.fitBounds(markersGroup.getBounds().pad(0.1));
        });
    </script>
</div>
