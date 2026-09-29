<div id="migration-popup" class="migration-popup-overlay" style="display: none;">
    <div class="migration-popup-card">
        
        <button id="migration-popup-close" class="migration-popup-close-btn" aria-label="Close">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="migration-popup-left">
            <div class="megaphone-wrapper">
                <img src="{{ asset('assets/logo/megaphone.png') }}" alt="Megaphone">
            </div>
        </div>

        <div class="migration-popup-right">
            <h4 class="migration-popup-title">Perhatian!</h4>
            <p class="migration-popup-text">
                Alamat website LPK Paiton Selaras telah berubah. Kami kini menggunakan domain <strong>lpkpaiton.com</strong>. Domain lama (lpkpaiton.site) tidak lagi digunakan. Mohon simpan alamat baru ini.
            </p>
            <div class="migration-popup-actions">
                <button id="migration-popup-understand" class="migration-popup-btn">Ya, Mengerti</button>
            </div>
        </div>

    </div>
</div>

<style>
    .migration-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
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
        border-radius: 20px;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        position: relative;
        display: flex;
        flex-direction: row;
        overflow: hidden;
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .migration-popup-overlay.show .migration-popup-card {
        transform: translateY(0);
    }
    .migration-popup-left {
        width: 35%;
        background: linear-gradient(135deg, #f0f7ff 0%, #e0e7ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .megaphone-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        padding: 20px;
        filter: drop-shadow(4px 10px 8px rgba(59, 130, 246, 0.2));
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .megaphone-wrapper img {
        width: 100%;
        max-width: 180px;
        height: auto;
        object-fit: contain;
    }
    .migration-popup-card:hover .megaphone-wrapper {
        transform: scale(1.05) rotate(-3deg);
    }
    .migration-popup-right {
        width: 65%;
        padding: 40px 40px 40px 30px;
        text-align: left;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .migration-popup-close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        cursor: pointer;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        transition: background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }
    .migration-popup-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .migration-popup-title {
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
        margin-top: 0;
    }
    .migration-popup-text {
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 0.95rem;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .migration-popup-actions {
        display: flex;
        gap: 12px;
    }
    .migration-popup-btn {
        background: #3b82f6;
        color: #ffffff;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }
    .migration-popup-btn:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    .migration-popup-btn:active {
        transform: translateY(1px);
    }

    @media (max-width: 640px) {
        .migration-popup-card {
            flex-direction: column;
            max-width: 400px;
        }
        .migration-popup-left {
            width: 100%;
            padding: 30px 0;
        }
        .megaphone-wrapper {
            font-size: 4rem;
        }
        .migration-popup-right {
            width: 100%;
            padding: 30px 24px;
            text-align: center;
        }
        .migration-popup-actions {
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hasSeenPopup = localStorage.getItem('migration_popup_seen');
        
        if (!hasSeenPopup) {
            const popup = document.getElementById('migration-popup');
            const closeBtn = document.getElementById('migration-popup-close');
            const understandBtn = document.getElementById('migration-popup-understand');
            
            setTimeout(() => {
                popup.style.display = 'flex';
                popup.offsetHeight; // trigger reflow
                popup.classList.add('show');
            }, 500);

            function closePopup() {
                popup.classList.remove('show');
                setTimeout(() => {
                    popup.style.display = 'none';
                }, 300);
                localStorage.setItem('migration_popup_seen', 'true');
            }

            closeBtn.addEventListener('click', closePopup);
            understandBtn.addEventListener('click', closePopup);
            
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    closePopup();
                }
            });
        }
    });
</script>
