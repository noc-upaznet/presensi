@push('styles')
    <style>
        #map {
            height: 400px;
            width: 100%;
            display: block;
        }
    </style>
@endpush
<div>
    <div class="container" style="background-color: var(--bs-body-bg);">
        <div class="row" wire:ignore>
            <div class="col-md-12" style="color: var(--bs-body-color);">
                <h1>Map Component</h1>
                <div id="map" style="height: 400px;"></div>
            </div>
        </div>
        <div class="mt-2" style="color: var(--bs-body-color);">
            <div class="mb-3">
                <label>Latitude:</label>
                <input type="text" wire:model="latitude" class="form-control" placeholder="Masukkan Latitude">
                <label>Longitude:</label>
                <input type="text" wire:model="longitude" class="form-control" placeholder="Masukkan Longitude">
                <button wire:click="searchCoordinates" class="btn btn-primary mt-2">Search</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const interval = setInterval(() => {
            const mapEl = document.getElementById('map');
            if (mapEl) {
                clearInterval(interval);

                // Default
                let map = L.map('map').setView([-6.2, 106.8], 13);

                // Tambahkan tile
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                let marker;

                // dapatkan lokasi perangkat
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            // Geser map ke lokasi perangkat
                            map.setView([lat, lng], 16);

                            // Tambah marker di lokasi
                            marker = L.marker([lat, lng]).addTo(map)
                                .bindPopup("Lokasi Anda sekarang").openPopup();
                        },
                        function (error) {
                            console.warn('Gagal mendapatkan lokasi:', error.message);
                        }
                    );
                } else {
                    alert("Geolocation tidak didukung browser ini.");
                }

                // Klik di peta
                map.on('click', function(e) {
                    const { lat, lng } = e.latlng;

                    // Hapus marker lama
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Tambah marker baru
                    marker = L.marker([lat, lng]).addTo(map);

                    console.log('Coordinates:', lat, lng);

                    // Kirim ke Livewire
                    @this.call('setCoordinates', { lat: lat, lng: lng });
                });

                // Tangkap event
                Livewire.on('map:move-to-coordinates', event => {
                    if (Array.isArray(event)) {
                        event = event[0];
                    }
                    
                    if (event && event.lat && event.lng) {
                        map.setView([event.lat, event.lng], 16);
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        marker = L.marker([event.lat, event.lng]).addTo(map);
                    } else {
                        console.warn('Koordinat tidak lengkap:', event);
                    }
                });
            }
        }, 100);
    });
</script>
@endpush