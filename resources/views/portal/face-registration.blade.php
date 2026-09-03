@extends('portal.layout')

@section('title', 'Pendaftaran Wajah')

@push('styles')
<style>
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    #video {
        width: 100%;
        height: auto;
        display: block;
        transform: scaleX(-1); /* Mirror camera */
    }
    canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: scaleX(-1);
    }
    .overlay-message {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 0.9rem;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .loader-spinner {
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="page-header text-center">
    <h1 class="page-title">Pendaftaran Wajah (Face AI)</h1>
    <p class="page-subtitle">Arahkan wajah Anda ke kamera untuk didaftarkan ke sistem absensi.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom text-center">
            <div class="card-body-custom p-4">
                
                @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <div class="camera-container mb-4" id="camera-container">
                    <video id="video" autoplay muted playsinline></video>
                    <div class="overlay-message" id="status-message">
                        <div class="loader-spinner"></div>
                        <span id="status-text">Memuat AI Models...</span>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" id="btn-scan" class="btn btn-primary px-4 py-2" disabled>
                        <i class="fa-solid fa-camera me-2"></i> Pindai Wajah Sekarang
                    </button>
                    <a href="{{ route('portal.biodata') }}" class="btn btn-light px-4 py-2">
                        Batal
                    </a>
                </div>

            </div>
        </div>

        <div class="alert alert-info mt-4">
            <i class="fa-solid fa-circle-info me-2"></i> <strong>Tips:</strong> Pastikan pencahayaan cukup, tidak memakai masker, kacamata hitam, atau topi yang menutupi wajah.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Face API JS -->
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    const video = document.getElementById('video');
    const statusText = document.getElementById('status-text');
    const statusMessage = document.getElementById('status-message');
    const btnScan = document.getElementById('btn-scan');
    let faceDescriptorToSave = null;
    let isDetecting = false;
    let detectionLoop;

    // Pastikan models path sesuai
    const MODEL_URL = '{{ asset("models") }}';

    async function initFaceAPI() {
        try {
            statusText.innerText = 'Memuat AI Models... (1/2)';
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            
            statusText.innerText = 'Memuat AI Models... (2/2)';
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

            startVideo();
        } catch (error) {
            console.error("Gagal memuat model:", error);
            statusText.innerText = 'Gagal memuat sistem AI. Coba muat ulang halaman.';
            statusMessage.style.background = 'rgba(220, 53, 69, 0.8)';
            document.querySelector('.loader-spinner').style.display = 'none';
        }
    }

    function startVideo() {
        statusText.innerText = 'Menyalakan kamera...';
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing camera:", err);
                statusText.innerText = 'Akses kamera ditolak atau tidak ditemukan.';
                statusMessage.style.background = 'rgba(220, 53, 69, 0.8)';
                document.querySelector('.loader-spinner').style.display = 'none';
            });
    }

    video.addEventListener('play', () => {
        statusText.innerText = 'Menganalisis wajah...';
        
        // Buat canvas untuk menggambar kotak deteksi
        const canvas = faceapi.createCanvasFromMedia(video);
        document.getElementById('camera-container').append(canvas);
        
        const displaySize = { width: video.clientWidth, height: video.clientHeight };
        faceapi.matchDimensions(canvas, displaySize);

        // Resize observer in case window resizes
        new ResizeObserver(() => {
            const newDisplaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(canvas, newDisplaySize);
        }).observe(video);

        // Optimasi: gunakan rekursif timeout daripada setInterval agar tidak freeze
        async function detect() {
            if(video.paused || video.ended || isDetecting) return;
            isDetecting = true;

            try {
                // Gunakan inputSize yang lebih kecil (misal 160 atau 224) agar lebih cepat
                const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 });
                
                const detections = await faceapi.detectAllFaces(video, options)
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, { width: video.clientWidth, height: video.clientHeight });
                
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                faceapi.draw.drawDetections(canvas, resizedDetections);
                faceapi.draw.drawFaceLandmarks(canvas, resizedDetections);

                if (detections.length === 0) {
                    statusText.innerText = 'Wajah tidak terdeteksi. Posisikan wajah Anda di tengah.';
                    statusMessage.style.background = 'rgba(0, 0, 0, 0.7)';
                    btnScan.disabled = true;
                    btnScan.innerHTML = '<i class="fa-solid fa-camera me-2"></i> Pindai Wajah Sekarang';
                    faceDescriptorToSave = null;
                } else if (detections.length > 1) {
                    statusText.innerText = 'Terdeteksi lebih dari 1 wajah. Harap sendirian.';
                    statusMessage.style.background = 'rgba(220, 53, 69, 0.8)';
                    btnScan.disabled = true;
                    btnScan.innerHTML = '<i class="fa-solid fa-camera me-2"></i> Pindai Wajah Sekarang';
                    faceDescriptorToSave = null;
                } else {
                    // Tepat 1 wajah terdeteksi
                    statusText.innerText = 'Wajah terdeteksi! Silakan klik "Pindai Wajah Sekarang"';
                    statusMessage.style.background = 'rgba(25, 135, 84, 0.8)'; // Hijau
                    
                    // Ambil descriptor (array 128 dimensi)
                    faceDescriptorToSave = detections[0].descriptor;
                    
                    btnScan.disabled = false;
                }
            } catch (err) {
                console.error("Detection error: ", err);
            }

            isDetecting = false;
            // Panggil lagi setelah jeda pendek
            detectionLoop = setTimeout(detect, 200);
        }

        // Mulai loop deteksi
        detect();
    });

    // Handle tombol simpan
    btnScan.addEventListener('click', function() {
        if (!faceDescriptorToSave) return;

        // Ubah state loading
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';
        clearTimeout(detectionLoop); // Hentikan loop deteksi

        // Konversi float32 array ke array biasa agar bisa di-JSON-kan
        const descriptorArray = Array.from(faceDescriptorToSave);
        
        fetch('{{ route("portal.face-registration.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                face_descriptor: JSON.stringify(descriptorArray)
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Terjadi kesalahan pada server');
            }
            return data;
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data wajah Anda berhasil disimpan.',
                confirmButtonText: 'Lanjutkan',
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                window.location.href = '{{ route("portal.biodata") }}';
            });
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: error.message || 'Gagal menyimpan wajah. Silakan coba lagi.'
            });
            this.disabled = false;
            this.innerHTML = '<i class="fa-solid fa-camera me-2"></i> Pindai Wajah Sekarang';
            
            // Lanjutkan loop deteksi
            detect();
        });
    });

    // Mulai inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', initFaceAPI);

</script>
@endpush
