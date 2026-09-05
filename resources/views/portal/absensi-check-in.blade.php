@extends('portal.layout')

@section('title', 'Absensi Harian')

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
        transform: scaleX(-1);
    }
    canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transform: scaleX(-1);
    }
    .status-panel {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #e2e8f0;
    }
    .check-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .check-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .check-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: #f1f5f9;
        color: #64748b;
    }
    .check-icon.success {
        background: #f0fdf4;
        color: #16a34a;
    }
    .check-icon.error {
        background: #fef2f2;
        color: #dc2626;
    }
    .check-icon.loading {
        background: #eff6ff;
        color: #3b82f6;
    }
    .btn-absen {
        width: 100%;
        padding: 15px;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }
    .btn-absen:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="page-header text-center">
    <h1 class="page-title">Check-In Absensi</h1>
    <p class="page-subtitle">Pastikan Anda berada di lokasi LPK dan wajah terlihat jelas.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fa-solid fa-circle-check me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="fa-solid fa-triangle-exclamation me-2 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if($attendance && $attendance->check_in && $attendance->check_out)
            <div class="alert alert-info text-center p-4">
                <i class="fa-solid fa-check-double text-primary mb-3" style="font-size: 3rem;"></i>
                <h4 class="alert-heading">Absensi Hari Ini Selesai</h4>
                <p>Anda sudah melakukan absensi masuk dan pulang hari ini.</p>
                <a href="{{ route('portal.absensi') }}" class="btn btn-primary mt-2">Lihat Riwayat Absensi</a>
            </div>
        @else
            <div class="camera-container" id="camera-container">
                <video id="video" autoplay muted playsinline></video>
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" id="camera-overlay" style="background: rgba(0,0,0,0.7); z-index: 10;">
                    <div class="spinner-border text-light mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h5 class="text-white text-center px-4" id="overlay-text">Menyiapkan sistem absensi...</h5>
                </div>
            </div>

            <div class="status-panel">
                <div class="check-item">
                    <div class="check-icon" id="icon-gps"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Validasi Lokasi (GPS)</div>
                        <div class="text-secondary" style="font-size: 0.85rem;" id="text-gps">Mencari lokasi Anda...</div>
                    </div>
                </div>
                
                <div class="check-item">
                    <div class="check-icon" id="icon-face"><i class="fa-solid fa-face-smile"></i></div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">Pencocokan Wajah</div>
                        <div class="text-secondary" style="font-size: 0.85rem;" id="text-face">Menunggu lokasi valid...</div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" id="btn-submit" class="btn btn-primary btn-absen" disabled>
                        <i class="fa-solid fa-fingerprint me-2"></i> 
                        {{ ($attendance && $attendance->check_in) ? 'Absen Pulang Sekarang' : 'Absen Masuk Sekarang' }}
                    </button>
                </div>
            </div>
        @endif
        
    </div>
</div>

<form id="form-absen" action="{{ route('portal.absensi.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="latitude" id="input-lat">
    <input type="hidden" name="longitude" id="input-lng">
    <input type="hidden" name="type" value="{{ ($attendance && $attendance->check_in) ? 'out' : 'in' }}">
</form>

@endsection

@push('scripts')
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    @if(!($attendance && $attendance->check_in && $attendance->check_out))
    
    // Data dari backend
    const TARGET_LAT = {{ $settings['lpk_latitude'] ?? '-7.7126' }};
    const TARGET_LNG = {{ $settings['lpk_longitude'] ?? '113.4687' }};
    const MAX_RADIUS = {{ $settings['absensi_radius'] ?? '50' }}; // in meters
    const SAVED_DESCRIPTOR = {!! $profile->face_descriptor ?? '[]' !!};
    
    // Elements
    const video = document.getElementById('video');
    const overlay = document.getElementById('camera-overlay');
    const overlayText = document.getElementById('overlay-text');
    const iconGps = document.getElementById('icon-gps');
    const textGps = document.getElementById('text-gps');
    const iconFace = document.getElementById('icon-face');
    const textFace = document.getElementById('text-face');
    const btnSubmit = document.getElementById('btn-submit');
    const formAbsen = document.getElementById('form-absen');
    
    let isGpsValid = false;
    let isFaceValid = false;
    let isDetecting = false;
    let detectionLoop;
    let userLat = null;
    let userLng = null;
    
    // 1. Haversine Formula for Distance Calculation
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371e3; // Radius bumi dalam meter
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLon = (lon2 - lon1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d; // meter
    }

    // 2. Init GPS
    function initGPS() {
        iconGps.className = 'check-icon loading';
        iconGps.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                function(position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    
                    const distance = calculateDistance(userLat, userLng, TARGET_LAT, TARGET_LNG);
                    
                    if (distance <= MAX_RADIUS) {
                        isGpsValid = true;
                        iconGps.className = 'check-icon success';
                        iconGps.innerHTML = '<i class="fa-solid fa-check"></i>';
                        textGps.innerHTML = `<span class="text-success">Lokasi valid (Jarak: ${Math.round(distance)}m)</span>`;
                        
                        // Lanjut ke pengecekan wajah
                        if (!isFaceValid) {
                            textFace.innerText = 'Menunggu wajah Anda...';
                            iconFace.className = 'check-icon loading';
                            iconFace.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                        }
                        
                        checkEnableButton();
                    } else {
                        isGpsValid = false;
                        iconGps.className = 'check-icon error';
                        iconGps.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                        textGps.innerHTML = `<span class="text-danger">Di luar jangkauan (Jarak: ${Math.round(distance)}m / ${MAX_RADIUS}m)</span>`;
                        checkEnableButton();
                    }
                },
                function(error) {
                    isGpsValid = false;
                    iconGps.className = 'check-icon error';
                    iconGps.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                    textGps.innerHTML = '<span class="text-danger">Akses lokasi ditolak / Gagal. Pastikan GPS aktif.</span>';
                },
                { enableHighAccuracy: true, maximumAge: 0 }
            );
        } else {
            textGps.innerHTML = '<span class="text-danger">Browser tidak mendukung geolokasi.</span>';
        }
    }

    // 3. Init Face API
    async function initFaceAPI() {
        try {
            overlayText.innerText = 'Memuat model AI...';
            const MODEL_URL = '/models';
            
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            
            startVideo();
        } catch (error) {
            overlayText.innerText = 'Gagal memuat AI. Refresh halaman.';
            console.error(error);
        }
    }

    function startVideo() {
        overlayText.innerText = 'Mengakses kamera...';
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                overlayText.innerText = 'Kamera tidak dapat diakses.';
                console.error(err);
            });
    }

    // 4. Proses Pencocokan Wajah
    video.addEventListener('play', () => {
        // Sembunyikan loading overlay saat video mulai jalan
        overlay.classList.remove('d-flex');
        overlay.classList.add('d-none');

        const canvas = faceapi.createCanvasFromMedia(video);
        document.getElementById('camera-container').append(canvas);
        
        const displaySize = { width: video.clientWidth, height: video.clientHeight };
        faceapi.matchDimensions(canvas, displaySize);

        new ResizeObserver(() => {
            const newDisplaySize = { width: video.clientWidth, height: video.clientHeight };
            faceapi.matchDimensions(canvas, newDisplaySize);
        }).observe(video);
        
        // Konversi saved descriptor (array biasa -> Float32Array)
        const referenceDescriptor = new Float32Array(SAVED_DESCRIPTOR);
        const labeledDescriptor = new faceapi.LabeledFaceDescriptors('Siswa', [referenceDescriptor]);
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptor, 0.5); // Threshold 0.5 (makin kecil makin ketat)

        async function detect() {
            if(video.paused || video.ended || isDetecting) return;
            isDetecting = true;

            try {
                // Jangan cek wajah jika GPS belum valid (opsional, tapi hemat resource)
                if (isGpsValid && !isFaceValid) {
                    const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 });
                    const detections = await faceapi.detectAllFaces(video, options)
                        .withFaceLandmarks()
                        .withFaceDescriptors();

                    const resizedDetections = faceapi.resizeResults(detections, displaySize);
                    
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    if (detections.length === 1) {
                        const descriptor = detections[0].descriptor;
                        const match = faceMatcher.findBestMatch(descriptor);
                        
                        // Draw box
                        const box = resizedDetections[0].detection.box;
                        const drawBox = new faceapi.draw.DrawBox(box, { 
                            label: match.toString(),
                            boxColor: match.label === 'Siswa' ? '#16a34a' : '#dc2626'
                        });
                        drawBox.draw(canvas);

                        if (match.label === 'Siswa') {
                            isFaceValid = true;
                            iconFace.className = 'check-icon success';
                            iconFace.innerHTML = '<i class="fa-solid fa-check"></i>';
                            textFace.innerHTML = '<span class="text-success">Wajah Cocok!</span>';
                            checkEnableButton();
                        } else {
                            textFace.innerHTML = '<span class="text-danger">Wajah tidak dikenali!</span>';
                        }
                    } else if (detections.length > 1) {
                        textFace.innerHTML = '<span class="text-danger">Lebih dari 1 wajah terdeteksi.</span>';
                    } else {
                        textFace.innerText = 'Posisikan wajah Anda di kamera...';
                    }
                }
            } catch (error) {
                console.error(error);
            }

            isDetecting = false;
            if (!isFaceValid) {
                detectionLoop = setTimeout(detect, 300);
            }
        }
        
        detect();
    });

    function checkEnableButton() {
        if (isGpsValid && isFaceValid) {
            btnSubmit.disabled = false;
        } else {
            btnSubmit.disabled = true;
        }
    }

    btnSubmit.addEventListener('click', function() {
        document.getElementById('input-lat').value = userLat;
        document.getElementById('input-lng').value = userLng;
        
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';
        this.disabled = true;
        
        formAbsen.submit();
    });

    // Start everything
    document.addEventListener('DOMContentLoaded', () => {
        initGPS();
        initFaceAPI();
    });
    
    @endif
</script>
@endpush
