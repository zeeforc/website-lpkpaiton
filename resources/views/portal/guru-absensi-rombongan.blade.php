@extends('portal.layout')

@section('title', 'Absensi Rombongan')

@push('styles')
<style>
    .cctv-container {
        position: relative;
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        border-radius: 12px;
        overflow: hidden;
        background: #000;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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
        border: 1px solid #e2e8f0;
        height: 100%;
    }
    .student-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .student-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }
    .student-item.detected {
        background: #f0fdf4;
        border-color: #dcfce7;
    }
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #64748b;
    }
    .student-item.detected .student-avatar {
        background: #16a34a;
        color: white;
    }
    .student-info {
        flex-grow: 1;
    }
    .student-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .student-status {
        font-size: 0.8rem;
        color: #64748b;
    }
    .student-item.detected .student-status {
        color: #16a34a;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="page-header text-center">
    <h1 class="page-title">Absensi Rombongan</h1>
    <p class="page-subtitle">Pindai wajah santri yang hadir secara bersamaan (Mode CCTV).</p>
</div>

@if(count($students) === 0)
<div class="alert alert-warning text-center">
    <i class="fa-solid fa-triangle-exclamation mb-2" style="font-size: 2rem;"></i>
    <h5>Tidak Ada Santri</h5>
    <p class="mb-0">Belum ada santri asuhan Anda yang mendaftarkan wajah di sistem.</p>
</div>
@else
<div class="row g-4">
    <div class="col-lg-8">
        <div class="cctv-container" id="camera-container">
            <video id="video" autoplay muted playsinline></video>
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" id="camera-overlay" style="background: rgba(0,0,0,0.8); z-index: 10;">
                <div class="spinner-border text-light mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <h5 class="text-white text-center px-4" id="overlay-text">Menyiapkan sistem AI...</h5>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3 bg-white p-3 rounded border">
            <div>
                <div class="fw-bold text-dark" id="gps-status-title"><i class="fa-solid fa-spinner fa-spin me-2 text-primary"></i> Mengecek Lokasi (GPS)...</div>
                <div class="text-secondary small" id="gps-status-desc">Menunggu kordinat...</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="status-panel d-flex flex-column">
            <h5 class="mb-3 d-flex justify-content-between align-items-center">
                Daftar Santri Hadir
                <span class="badge bg-primary rounded-pill" id="counter">0 / {{ count($students) }}</span>
            </h5>
            
            <div class="student-list flex-grow-1" id="student-list">
                @foreach($students as $student)
                <div class="student-item" id="student-{{ $student->user_id }}">
                    <div class="student-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="student-info">
                        <div class="student-name">{{ $student->user->name }}</div>
                        <div class="student-status">Belum terdeteksi</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex gap-2 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="absen_type" id="type_in" value="in" checked>
                        <label class="form-check-label fw-500" for="type_in">Absen Masuk</label>
                    </div>
                    <div class="form-check ms-3">
                        <input class="form-check-input" type="radio" name="absen_type" id="type_out" value="out">
                        <label class="form-check-label fw-500" for="type_out">Absen Pulang</label>
                    </div>
                </div>
                
                <button type="button" id="btn-submit" class="btn btn-primary w-100 py-2 fw-bold" disabled>
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Absensi
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if(count($students) > 0)
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
    // Data dari backend
    const TARGET_LAT = {{ $settings['lpk_latitude'] ?? '-7.7126' }};
    const TARGET_LNG = {{ $settings['lpk_longitude'] ?? '113.4687' }};
    const MAX_RADIUS = {{ $settings['absensi_radius'] ?? '150' }}; // in meters
    
    // Siapkan Labeled Descriptors dari PHP
    const studentData = [
        @foreach($students as $student)
        {
            id: "{{ $student->user_id }}",
            name: "{{ $student->user->name }}",
            descriptor: {!! $student->face_descriptor !!}
        },
        @endforeach
    ];

    const detectedStudentIds = new Set();
    
    // Elements
    const video = document.getElementById('video');
    const overlay = document.getElementById('camera-overlay');
    const overlayText = document.getElementById('overlay-text');
    const btnSubmit = document.getElementById('btn-submit');
    const counterText = document.getElementById('counter');
    
    let isGpsValid = false;
    let userLat = null;
    let userLng = null;
    let detectionLoop;
    let isDetecting = false;

    // 1. Haversine Formula for Distance Calculation
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371e3; 
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLon = (lon2 - lon1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; 
    }

    // 2. Init GPS
    function initGPS() {
        const title = document.getElementById('gps-status-title');
        const desc = document.getElementById('gps-status-desc');
        
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                function(position) {
                    userLat = position.coords.latitude;
                    userLng = position.coords.longitude;
                    
                    const distance = calculateDistance(userLat, userLng, TARGET_LAT, TARGET_LNG);
                    
                    if (distance <= MAX_RADIUS) {
                        isGpsValid = true;
                        title.innerHTML = '<i class="fa-solid fa-location-dot me-2 text-success"></i> Lokasi Valid';
                        title.className = 'fw-bold text-success';
                        desc.innerText = `Jarak: ${Math.round(distance)}m`;
                        checkEnableSubmit();
                    } else {
                        isGpsValid = false;
                        title.innerHTML = '<i class="fa-solid fa-xmark me-2 text-danger"></i> Di Luar LPK';
                        title.className = 'fw-bold text-danger';
                        desc.innerText = `Jarak: ${Math.round(distance)}m / ${MAX_RADIUS}m`;
                        checkEnableSubmit();
                    }
                },
                function(error) {
                    isGpsValid = false;
                    title.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i> GPS Error';
                    desc.innerText = 'Pastikan GPS menyala dan izinkan akses.';
                },
                { enableHighAccuracy: true, maximumAge: 0 }
            );
        }
    }

    // 3. Init Face API
    async function initFaceAPI() {
        try {
            overlayText.innerText = 'Memuat model AI...';
            const MODEL_URL = '{{ asset("models") }}';
            
            await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            
            startVideo();
        } catch (error) {
            overlayText.innerText = 'Gagal memuat AI.';
            console.error(error);
        }
    }

    function startVideo() {
        overlayText.innerText = 'Mengakses kamera...';
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } }) // Gunakan kamera belakang jika ada
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                // Fallback ke kamera depan jika belakang tidak ada
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                    .then(stream => {
                        video.srcObject = stream;
                    })
                    .catch(err2 => {
                        overlayText.innerText = 'Kamera tidak dapat diakses.';
                        console.error(err2);
                    });
            });
    }

    // 4. Proses Pencocokan Wajah
    video.addEventListener('play', () => {
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
        
        // Buat LabeledFaceDescriptors
        const labeledDescriptors = studentData.map(student => {
            const descArray = new Float32Array(student.descriptor);
            // Label is student ID
            return new faceapi.LabeledFaceDescriptors(student.id, [descArray]);
        });
        
        const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.55);

        async function detect() {
            if(video.paused || video.ended || isDetecting) return;
            isDetecting = true;

            try {
                const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 });
                const detections = await faceapi.detectAllFaces(video, options)
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                resizedDetections.forEach(detection => {
                    const match = faceMatcher.findBestMatch(detection.descriptor);
                    
                    let boxColor = '#dc2626'; // Red for unknown
                    let labelText = 'Tidak Dikenali';

                    if (match.label !== 'unknown') {
                        boxColor = '#16a34a'; // Green
                        
                        // Cari nama siswa
                        const matchedStudent = studentData.find(s => s.id === match.label);
                        if (matchedStudent) {
                            labelText = matchedStudent.name;
                            markStudentPresent(match.label);
                        }
                    }

                    const box = detection.detection.box;
                    const drawBox = new faceapi.draw.DrawBox(box, { 
                        label: labelText,
                        boxColor: boxColor
                    });
                    drawBox.draw(canvas);
                });

            } catch (error) {
                console.error(error);
            }

            isDetecting = false;
            detectionLoop = setTimeout(detect, 300); // scan every 300ms
        }
        
        detect();
    });

    function markStudentPresent(studentId) {
        if (!detectedStudentIds.has(studentId)) {
            detectedStudentIds.add(studentId);
            
            // Update UI
            const item = document.getElementById('student-' + studentId);
            if (item) {
                item.classList.add('detected');
                item.querySelector('.student-status').innerHTML = '<i class="fa-solid fa-check-circle me-1"></i> Hadir';
                item.querySelector('.student-avatar').innerHTML = '<i class="fa-solid fa-check"></i>';
            }
            
            counterText.innerText = `${detectedStudentIds.size} / ${studentData.length}`;
            checkEnableSubmit();
        }
    }

    function checkEnableSubmit() {
        if (isGpsValid && detectedStudentIds.size > 0) {
            btnSubmit.disabled = false;
        } else {
            btnSubmit.disabled = true;
        }
    }

    // Submit
    btnSubmit.addEventListener('click', function() {
        if (detectedStudentIds.size === 0) return;
        
        const type = document.querySelector('input[name="absen_type"]:checked').value;
        const studentIdsArray = Array.from(detectedStudentIds);
        
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';
        this.disabled = true;
        
        fetch('{{ route("portal.guru.absensi-rombongan.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_ids: studentIdsArray,
                latitude: userLat,
                longitude: userLng,
                type: type
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Terjadi kesalahan server');
            }
            return data;
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Absensi Tersimpan!',
                text: data.message,
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                // Refresh halaman untuk mereset
                window.location.reload();
            });
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: error.message
            });
            this.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Absensi';
            this.disabled = false;
        });
    });

    // Start
    document.addEventListener('DOMContentLoaded', () => {
        initGPS();
        initFaceAPI();
    });
</script>
@endif
@endpush
