<style>
    /* Simulation Modal */
    .simulation-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease-out;
    }
    .simulation-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    .simulation-modal {
        background: white;
        width: 90%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(10px);
        transition: all 0.2s ease-out;
        overflow: hidden;
        position: relative;
    }
    .simulation-overlay.show .simulation-modal {
        transform: translateY(0);
    }
    .btn-close-sim {
        position: absolute;
        top: 15px; right: 15px;
        background: none; border: none;
        font-size: 28px; color: #999;
        cursor: pointer;
        z-index: 10;
        line-height: 1;
        padding: 0;
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
    }
    .btn-close-sim:hover { color: #333; background: #f0f0f0; }
    
    .sim-header {
        padding: 24px 24px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    .sim-header h3 { color: #111827; font-weight: 600; font-size: 1.25rem; }
    
    .sim-body {
        padding: 24px;
        position: relative;
        min-height: 240px;
    }
    .sim-step {
        display: none;
    }
    .sim-step.active {
        display: block;
    }
    .sim-icon {
        color: #fd7a2a;
        margin-bottom: 16px;
    }
    
    .sim-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fcfcfc;
    }
    .sim-indicators {
        display: flex; gap: 10px;
    }
    .sim-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #d1d5db;
        transition: background-color 0.2s;
    }
    .sim-dot.active {
        background: #fd7a2a;
    }
    
    /* Floating Action Button */
    .btn-floating-sim {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #111827;
        color: white;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        cursor: pointer;
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: translateY(100px);
        transition: transform 0.3s ease;
    }
    .btn-floating-sim.show {
        transform: translateY(0);
    }
    .btn-floating-sim:hover {
        background: #374151;
    }
</style>

<!-- Simulation Guide Modal -->
<div class="simulation-overlay" id="simOverlay">
    <div class="simulation-modal">
        <button class="btn-close-sim" onclick="closeSimulation()" title="Tutup">×</button>
        <div class="sim-header">
            <h3 class="mb-0">Panduan Pendaftaran</h3>
            <p class="text-muted small mb-0">Alur Magang & PKL</p>
        </div>
        
        <div class="sim-body">
            <!-- Step 1 -->
            <div class="sim-step active" id="step1">
                <div class="sim-icon"><i data-feather="edit-3" style="width: 32px; height: 32px;"></i></div>
                <h4 class="fw-bold">Tahap 1: Mengisi Formulir</h4>
                <p class="text-secondary small">Isi data diri Anda pada formulir pendaftaran.</p>
                <div class="alert alert-warning small border-warning">
                    <strong>Penting:</strong> Gunakan email aktif. Alamat email hanya berlaku untuk satu kali pendaftaran dan Anda tidak dapat menggunakan email selain yang telah terdaftar.
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="sim-step" id="step2">
                <div class="sim-icon"><i data-feather="clock" style="width: 32px; height: 32px;"></i></div>
                <h4 class="fw-bold">Tahap 2: Menunggu Persetujuan</h4>
                <p class="text-secondary small">Admin kami akan memeriksa kuota dan kesesuaian jurusan Anda.</p>
                <p class="text-secondary small mb-0">Kami akan mengirimkan hasilnya melalui email. Anda juga bisa memantau statusnya di halaman Cek Status.</p>
            </div>
            
            <!-- Step 3 -->
            <div class="sim-step" id="step3">
                <div class="sim-icon"><i data-feather="file-text" style="width: 32px; height: 32px;"></i></div>
                <h4 class="fw-bold">Tahap 3: Upload Dokumen</h4>
                <p class="text-secondary small">Jika disetujui, Anda perlu melengkapi dokumen persyaratan.</p>
                <p class="text-secondary small mb-0">Siapkan fotocopy KTP atau kartu pelajar, surat keterangan sehat, dan dokumen pendukung lainnya sesuai instruksi di email.</p>
            </div>

            <!-- Step 4 -->
            <div class="sim-step" id="step4">
                <div class="sim-icon"><i data-feather="check-circle" style="width: 32px; height: 32px;"></i></div>
                <h4 class="fw-bold">Tahap 4: Selesai</h4>
                <p class="text-secondary small">Setelah dokumen diverifikasi, pendaftaran selesai.</p>
                <p class="text-secondary small mb-0">Kami akan mengirimkan username dan password agar Anda bisa masuk ke sistem pembelajaran kami.</p>
            </div>
        </div>
        
        <div class="sim-footer">
            <button class="btn btn-outline-secondary" id="btnPrevSim" onclick="prevSimStep()" disabled>Sebelumnya</button>
            <div class="sim-indicators">
                <span class="sim-dot active"></span>
                <span class="sim-dot"></span>
                <span class="sim-dot"></span>
                <span class="sim-dot"></span>
            </div>
            <button class="btn btn-primary" id="btnNextSim" onclick="nextSimStep()">Selanjutnya</button>
        </div>
    </div>
</div>

<button class="btn-floating-sim" id="fabSim" onclick="openSimulation()" title="Buka Panduan">
    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
</button>

<script>
// Simulation Logic
let currentSimStep = 1;
const totalSimSteps = 4;

function initSimulation() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    // Check session storage
    if (!sessionStorage.getItem('simClosed')) {
        setTimeout(openSimulation, 500); // Auto open first time in session
    } else {
        document.getElementById('fabSim').classList.add('show');
    }
    updateSimUI();
}

window.openSimulation = function() {
    document.getElementById('simOverlay').classList.add('show');
    document.getElementById('fabSim').classList.remove('show');
    currentSimStep = 1;
    updateSimUI();
}

window.closeSimulation = function() {
    document.getElementById('simOverlay').classList.remove('show');
    document.getElementById('fabSim').classList.add('show');
    sessionStorage.setItem('simClosed', 'true');
}

window.nextSimStep = function() {
    if (currentSimStep < totalSimSteps) {
        currentSimStep++;
        updateSimUI();
    } else {
        closeSimulation();
    }
}

window.prevSimStep = function() {
    if (currentSimStep > 1) {
        currentSimStep--;
        updateSimUI();
    }
}

function updateSimUI() {
    // Update Steps
    document.querySelectorAll('.sim-step').forEach((el, index) => {
        el.classList.toggle('active', index + 1 === currentSimStep);
    });
    
    // Update Dots
    document.querySelectorAll('.sim-dot').forEach((el, index) => {
        el.classList.toggle('active', index + 1 === currentSimStep);
    });
    
    // Update Buttons
    document.getElementById('btnPrevSim').disabled = currentSimStep === 1;
    
    const nextBtn = document.getElementById('btnNextSim');
    if (currentSimStep === totalSimSteps) {
        nextBtn.innerText = 'Tutup Panduan';
        nextBtn.classList.replace('btn-primary', 'btn-success');
    } else {
        nextBtn.innerText = 'Selanjutnya';
        nextBtn.classList.replace('btn-success', 'btn-primary');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initSimulation);
</script>
