<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Dashboard</h3>
        <div class="text-muted"><i class="fas fa-user"></i> Nadia Safira Khairunnisa</div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-3">
        <h5 class="text-primary fw-bold mb-0">07:50:56</h5>
    </div>

    <div class="row">
        <!-- Map Section -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.123456789!2d112.71234!3d-7.33245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z7Iuc6rCA6rWQ6rGg!5e0!3m2!1sen!2sid!4v1610000000000!5m2!1sen!2sid"
                        width="100%" height="300" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Clock In Info Section -->
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Notes</label>
                        <input type="text" class="form-control" value="Lokasi Kantor UNR Selatan" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Photo</label>
                        <div class="position-relative d-inline-block" id="photo-container">
                            <img id="photoImage" src="./assets/img/user4-128x128.jpg" alt="Selfie"
                                class="img-thumbnail rounded" width="100" height="100" style="object-fit: cover;">
                            <button type="button"
                                class="btn btn-danger p-0 d-flex justify-content-center align-items-center position-absolute start-100 translate-middle"
                                style="top: 6px; right: -6px; width: 24px; height: 24px; border-radius: 50%; font-size: 18px; line-height: 1;"
                                onclick="removePhoto()">
                                &minus;
                            </button>
                        </div>
                    </div>

                    <script>
                        function removePhoto() {
                            if (confirm('Ingin ambil ulang foto?')) {
                                const photoImage = document.getElementById('photoImage');
                                if (photoImage) {
                                    photoImage.src = ''; // Kosongkan gambar
                                }
                    
                                // Buka modal kamera
                                const modalEl = document.getElementById('cameraModal');
                                const modal = new bootstrap.Modal(modalEl);
                                modal.show();
                    
                                // Tunggu modal terbuka sebelum akses kamera
                                modalEl.addEventListener('shown.bs.modal', function () {
                                    const video = document.getElementById('video');
                                    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                                        navigator.mediaDevices.getUserMedia({ video: true })
                                            .then(stream => {
                                                video.srcObject = stream;
                                            })
                                            .catch(error => {
                                                console.error('Gagal akses kamera', error);
                                            });
                                    } else {
                                        console.log('Media Devices API tidak didukung browser ini');
                                    }
                                });
                    
                                // Hentikan kamera saat modal ditutup
                                modalEl.addEventListener('hidden.bs.modal', function () {
                                    const video = document.getElementById('video');
                                    const stream = video.srcObject;
                                    if (stream) {
                                        const tracks = stream.getTracks();
                                        tracks.forEach(track => track.stop());
                                        video.srcObject = null;
                                    }
                                });
                            }
                        }
                    </script>


                    <button class="btn btn-primary w-100 mt-3">Clock In</button>
                </div>
            </div>
        </div>
    </div>
</div>