<style>
    body.sim-open {
        overflow: hidden;
    }
    .simulation-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, 0.62);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .simulation-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    .simulation-modal {
        position: relative;
        display: flex;
        flex-direction: column;
        width: min(100%, 540px);
        max-height: min(720px, 90vh);
        overflow: auto;
        border: 1px solid #dfe5ef;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 24px 64px rgba(15, 23, 42, 0.28);
        transform: translateY(8px);
        transition: transform 0.2s ease;
    }
    .simulation-overlay.show .simulation-modal {
        transform: translateY(0);
    }
    .simulation-modal:focus {
        outline: 3px solid #0b5ed7;
        outline-offset: 3px;
    }
    .simulation-modal :focus-visible,
    .btn-floating-sim:focus-visible {
        outline: 3px solid #0b5ed7;
        outline-offset: 3px;
    }
    .sim-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 24px 24px 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .sim-header h2 {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 1.3rem;
        font-weight: 700;
    }
    .sim-header p {
        margin: 0;
        color: #475569;
    }
    .btn-close-sim {
        display: inline-flex;
        flex: 0 0 44px;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border: 1px solid #64748b;
        border-radius: 10px;
        background: #ffffff;
        color: #334155;
        font-size: 1.6rem;
        line-height: 1;
    }
    .btn-close-sim:hover {
        background: #f1f5f9;
    }
    .sim-body {
        min-height: 240px;
        padding: 28px 24px;
    }
    .sim-step {
        display: none;
    }
    .sim-step.active {
        display: block;
    }
    .sim-step-count {
        margin-bottom: 12px;
        color: #b84f0d;
        font-size: 0.9rem;
        font-weight: 700;
    }
    .sim-step h3 {
        margin-bottom: 10px;
        color: #0f172a;
        font-size: 1.2rem;
        font-weight: 700;
    }
    .sim-step p {
        margin-bottom: 12px;
        color: #334155;
        line-height: 1.65;
    }
    .sim-step p:last-child {
        margin-bottom: 0;
    }
    .sim-note {
        margin-top: 18px;
        padding: 14px 16px;
        border: 1px solid #fdba74;
        border-radius: 10px;
        background: #fff7ed;
        color: #7c2d12;
        line-height: 1.55;
    }
    .sim-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 24px 22px;
        border-top: 1px solid #e2e8f0;
    }
    .sim-footer button {
        min-height: 44px;
        padding: 9px 16px;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-prev-sim {
        border: 1px solid #64748b;
        background: #ffffff;
        color: #334155;
    }
    .btn-prev-sim:hover:not(:disabled) {
        background: #f1f5f9;
    }
    .btn-prev-sim:disabled {
        border-color: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
    }
    .sim-indicators {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sim-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #64748b;
    }
    .sim-dot.active {
        background: #b84f0d;
    }
    .btn-next-sim {
        border: 1px solid #0b5ed7;
        background: #0b5ed7;
        color: #ffffff;
    }
    .btn-next-sim:hover {
        border-color: #084db3;
        background: #084db3;
    }
    .btn-floating-sim {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 56px;
        height: 56px;
        border: 0;
        border-radius: 50%;
        background: #0b5ed7;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
        transform: translateY(96px);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .btn-floating-sim.show {
        transform: translateY(0);
    }
    .btn-floating-sim:hover {
        background: #084db3;
    }
    @media (max-width: 575.98px) {
        .simulation-modal {
            max-height: 92vh;
            border-radius: 16px;
        }
        .sim-header,
        .sim-footer {
            padding-right: 18px;
            padding-left: 18px;
        }
        .sim-body {
            min-height: 220px;
            padding: 24px 18px;
        }
        .sim-footer {
            flex-wrap: wrap;
        }
        .sim-indicators {
            order: 1;
            width: 100%;
            justify-content: center;
        }
        .btn-floating-sim {
            right: 16px;
            bottom: 16px;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .simulation-overlay,
        .simulation-modal,
        .btn-floating-sim {
            transition: none;
        }
    }
</style>

<div class="simulation-overlay" id="simOverlay" aria-hidden="true">
    <section class="simulation-modal" role="dialog" aria-modal="true" aria-labelledby="simTitle" aria-describedby="simDescription" tabindex="-1">
        <header class="sim-header">
            <div>
                <h2 id="simTitle">Panduan pendaftaran</h2>
                <p id="simDescription">Alur pendaftaran PKL dan magang</p>
            </div>
            <button class="btn-close-sim" id="btnCloseSim" type="button" aria-label="Tutup panduan">×</button>
        </header>

        <div class="sim-body">
            <p class="visually-hidden" id="simStepStatus" aria-live="polite">Tahap 1 dari 4</p>
            <div class="sim-step active" id="step1">
                <div class="sim-step-count">Tahap 1 dari 4</div>
                <h3>Isi formulir pendaftaran</h3>
                <p>Masukkan data sesuai identitas sekolah atau kampus. Gunakan email aktif untuk menerima surat balasan dan informasi akses portal siswa.</p>
                <div class="sim-note">Jika nama dan NISN atau NIM Anda sudah tercatat, hubungi admin untuk menghapus data pendaftaran lama sebelum mendaftar kembali.</div>
            </div>

            <div class="sim-step" id="step2" aria-hidden="true">
                <div class="sim-step-count">Tahap 2 dari 4</div>
                <h3>Tunggu pemeriksaan permohonan</h3>
                <p>Admin akan memeriksa permohonan dan kesesuaian jurusan. Hasil pemeriksaan dikirim melalui email. Anda juga dapat memantau status di halaman Cek Status.</p>
            </div>

            <div class="sim-step" id="step3" aria-hidden="true">
                <div class="sim-step-count">Tahap 3 dari 4</div>
                <h3>Unggah dokumen persyaratan</h3>
                <p>Jika permohonan diterima, buka tautan yang dikirim melalui email dan unggah dokumen sesuai petunjuk.</p>
            </div>

            <div class="sim-step" id="step4" aria-hidden="true">
                <div class="sim-step-count">Tahap 4 dari 4</div>
                <h3>Pantau hasil verifikasi</h3>
                <p>Setelah dokumen diperiksa, pantau email untuk informasi selanjutnya dan akses ke portal siswa.</p>
            </div>
        </div>

        <footer class="sim-footer">
            <button class="btn-prev-sim" id="btnPrevSim" type="button" disabled>Sebelumnya</button>
            <div class="sim-indicators" aria-hidden="true">
                <span class="sim-dot active"></span>
                <span class="sim-dot"></span>
                <span class="sim-dot"></span>
                <span class="sim-dot"></span>
            </div>
            <button class="btn-next-sim" id="btnNextSim" type="button">Selanjutnya</button>
        </footer>
    </section>
</div>

<button class="btn-floating-sim" id="fabSim" type="button" aria-label="Buka panduan pendaftaran" aria-controls="simOverlay" aria-expanded="false">
    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
        <line x1="12" y1="17" x2="12.01" y2="17"></line>
    </svg>
</button>

<script>
let currentSimStep = 1;
const totalSimSteps = 4;

function initSimulation() {
    const overlay = document.getElementById('simOverlay');
    const modal = overlay.querySelector('.simulation-modal');
    const floatingButton = document.getElementById('fabSim');
    const closeButton = document.getElementById('btnCloseSim');
    let previouslyFocusedElement = null;

    function updateSimUI() {
        document.querySelectorAll('.sim-step').forEach((step, index) => {
            const isCurrentStep = index + 1 === currentSimStep;
            step.classList.toggle('active', isCurrentStep);
            step.setAttribute('aria-hidden', String(!isCurrentStep));
        });

        document.querySelectorAll('.sim-dot').forEach((dot, index) => {
            dot.classList.toggle('active', index + 1 === currentSimStep);
        });

        document.getElementById('simStepStatus').textContent = `Tahap ${currentSimStep} dari ${totalSimSteps}`;
        document.getElementById('btnPrevSim').disabled = currentSimStep === 1;
        document.getElementById('btnNextSim').textContent = currentSimStep === totalSimSteps ? 'Tutup panduan' : 'Selanjutnya';
    }

    function openSimulation() {
        previouslyFocusedElement = document.activeElement;
        overlay.classList.add('show');
        overlay.setAttribute('aria-hidden', 'false');
        floatingButton.setAttribute('aria-expanded', 'true');
        floatingButton.classList.remove('show');
        document.body.classList.add('sim-open');
        currentSimStep = 1;
        updateSimUI();
        modal.focus();
    }

    function closeSimulation() {
        overlay.classList.remove('show');
        overlay.setAttribute('aria-hidden', 'true');
        floatingButton.setAttribute('aria-expanded', 'false');
        floatingButton.classList.add('show');
        document.body.classList.remove('sim-open');
        sessionStorage.setItem('simClosed', 'true');
        const focusTarget = previouslyFocusedElement instanceof HTMLElement && previouslyFocusedElement !== document.body
            ? previouslyFocusedElement
            : floatingButton;
        focusTarget.focus();
    }

    floatingButton.addEventListener('click', openSimulation);
    closeButton.addEventListener('click', closeSimulation);
    document.getElementById('btnPrevSim').addEventListener('click', () => {
        if (currentSimStep > 1) {
            currentSimStep -= 1;
            updateSimUI();
        }
    });
    document.getElementById('btnNextSim').addEventListener('click', () => {
        if (currentSimStep < totalSimSteps) {
            currentSimStep += 1;
            updateSimUI();
        } else {
            closeSimulation();
        }
    });
    overlay.addEventListener('click', event => {
        if (event.target === overlay) {
            closeSimulation();
        }
    });
    overlay.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeSimulation();
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        const focusableElements = modal.querySelectorAll('button:not(:disabled)');
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && (document.activeElement === firstElement || document.activeElement === modal)) {
            event.preventDefault();
            lastElement.focus();
        } else if (!event.shiftKey && (document.activeElement === lastElement || document.activeElement === modal)) {
            event.preventDefault();
            firstElement.focus();
        }
    });

    if (!sessionStorage.getItem('simClosed')) {
        window.setTimeout(openSimulation, 500);
    } else {
        floatingButton.classList.add('show');
    }

    updateSimUI();
}

document.addEventListener('DOMContentLoaded', initSimulation);
</script>
