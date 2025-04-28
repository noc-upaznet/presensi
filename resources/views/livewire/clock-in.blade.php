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

        <div class="container d-flex justify-content-center">
            <div class="clock-card p-4 text-center w-100" style="max-width: 400px;">
                <h6 class="text-white mb-3">Live Attendance</h6>
                <h1 class="fw-bold">07:57:21</h1>
                <p class="mb-4">Fri, 25 Apr 2025</p>

                <div class="clock-inner">
                    <div class="fw-semibold mb-2">Normal</div>
                    <div class="fw-bold fs-5 mb-3">08:00 - 16:00</div>
                    <div class="d-flex justify-content-center flex-wrap">
                        <button class="btn btn-primary px-4 me-2" data-bs-toggle="modal" data-bs-target="#cameraModal">
                            <i class="fas fa-arrow-right-to-bracket me-2"></i> Clock In
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kamera -->
    <div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cameraModalLabel">Clock In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="stopCamera()"></button>
                </div>

                <div class="modal-body text-center">
                    <video id="video" width="100%" autoplay style="border-radius: 10px; background: #eee;"></video>
                    <canvas id="canvas" style="display:none; margin-top: 10px;"></canvas>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary" onclick="takePhoto()">Ambil Foto</button>
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

        /* Responsif untuk mobile */
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
</div>

<script>
    let stream = null;

    function startCamera() {
      const video = document.getElementById('video');

      navigator.mediaDevices.getUserMedia({ video: true })
        .then((s) => {
          stream = s;
          video.srcObject = stream;
        })
        .catch((err) => {
          console.error('Gagal akses kamera', err);
        });
    }

    // Mulai kamera setiap kali modal dibuka
    document.getElementById('cameraModal').addEventListener('shown.bs.modal', startCamera);

    // Hentikan kamera pas modal ditutup
    function stopCamera() {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    }

    function takePhoto() {
      const video = document.getElementById('video');
      const canvas = document.getElementById('canvas');
      const context = canvas.getContext('2d');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Contoh: hasil foto diubah jadi data URL
      const photo = canvas.toDataURL('image/png');
      console.log('Foto diambil:', photo);

      alert('Foto sudah diambil!');
    }
</script>