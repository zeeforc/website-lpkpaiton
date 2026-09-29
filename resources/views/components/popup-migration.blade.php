<div id="migration-popup" class="migration-popup-overlay" style="display: none;">
    <div class="migration-popup-card">
        <button id="migration-popup-close" class="migration-popup-close-btn" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="migration-popup-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
        </div>
        <h4 class="migration-popup-title">Alamat Website Berubah</h4>
        <p class="migration-popup-text">
            LPK Paiton Selaras kini menggunakan domain <strong>lpkpaiton.com</strong>. Domain lama (lpkpaiton.site) tidak lagi digunakan. Mohon simpan alamat baru ini.
        </p>
        <button id="migration-popup-understand" class="migration-popup-btn">Mengerti</button>
    </div>
</div>

<style>
    .migration-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .migration-popup-overlay.show {
        opacity: 1;
    }
    .migration-popup-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 30px 24px 24px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        position: relative;
        text-align: center;
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .migration-popup-overlay.show .migration-popup-card {
        transform: translateY(0);
    }
    .migration-popup-close-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .migration-popup-close-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .migration-popup-icon {
        margin-bottom: 16px;
        display: flex;
        justify-content: center;
    }
    .migration-popup-title {
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
        margin-top: 0;
    }
    .migration-popup-text {
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 24px;
    }
    .migration-popup-btn {
        width: 100%;
        background: #0f172a;
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }
    .migration-popup-btn:hover {
        background: #1e293b;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user has already seen the popup
        const hasSeenPopup = localStorage.getItem('migration_popup_seen');
        
        if (!hasSeenPopup) {
            const popup = document.getElementById('migration-popup');
            const closeBtn = document.getElementById('migration-popup-close');
            const understandBtn = document.getElementById('migration-popup-understand');
            
            // Show popup with slight delay for smoother experience
            setTimeout(() => {
                popup.style.display = 'flex';
                // Trigger reflow
                popup.offsetHeight;
                popup.classList.add('show');
            }, 500);

            function closePopup() {
                popup.classList.remove('show');
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 300);
                // Save to local storage so it doesn't show again
                localStorage.setItem('migration_popup_seen', 'true');
            }

            closeBtn.addEventListener('click', closePopup);
            understandBtn.addEventListener('click', closePopup);
            
            // Close on overlay click
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    closePopup();
                }
            });
        }
    });
</script>
