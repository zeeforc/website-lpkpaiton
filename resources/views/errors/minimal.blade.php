<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LPK Paiton Selaras</title>
    <!-- Framework & Fonts sesuai dengan antislop-ui -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fafafc; 
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .error-badge {
            background-color: #fef2f2; 
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            border: 1px solid #fee2e2;
        }
        
        .title {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 24px;
            color: #0f172a;
            letter-spacing: -1px;
        }
        
        .title span {
            color: #fd7a2a; /* Brand color */
        }
        
        .description {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 90%;
        }
        
        .btn-primary-custom {
            background-color: #fd7a2a;
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 500;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s, background-color 0.2s;
            text-decoration: none;
        }
        
        .btn-primary-custom:hover {
            background-color: #e66920;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-outline-custom {
            background-color: transparent;
            color: #475569;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 500;
            border: 1px solid #cbd5e1;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        
        .btn-outline-custom:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        
        .vector-wrapper {
            position: relative;
            padding: 20px;
        }
        
        .vector-wrapper svg {
            width: 100%;
            height: auto;
            max-height: 500px;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: #f8fafc;
            -webkit-text-stroke: 2px #e2e8f0;
            line-height: 1;
            margin-bottom: -40px;
            z-index: -1;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row align-items-center justify-content-between g-5">
            <!-- Konten Kiri -->
            <div class="col-lg-5 order-2 order-lg-1">
                <div class="error-code">@yield('code')</div>
                <div class="error-badge">
                    <i data-feather="alert-triangle" width="16" height="16"></i>
                    Kesalahan Sistem
                </div>
                
                <h1 class="title">
                    Oops! <br><span>@yield('message')</span>
                </h1>
                
                <p class="description">
                    Maaf, halaman yang Anda cari tidak ditemukan atau terjadi kesalahan pada sistem kami. Pastikan alamat URL sudah benar atau kembali ke halaman sebelumnya.
                </p>
                
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="{{ url('/') }}" class="btn-primary-custom">
                        <i data-feather="home" width="18" height="18"></i>
                        Beranda
                    </a>
                    <button onclick="window.history.back()" class="btn-outline-custom">
                        <i data-feather="arrow-left" width="18" height="18"></i>
                        Kembali
                    </button>
                </div>
            </div>
            
            <!-- Vektor Kanan -->
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="vector-wrapper text-center">
                    <!-- Inline SVG representing Error/Disconnected -->
                    <svg viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Latar belakang lingkaran aksen -->
                        <circle cx="250" cy="200" r="160" fill="#fef2f2" />
                        <circle cx="100" cy="90" r="30" fill="#ef4444" opacity="0.1" />
                        <circle cx="380" cy="320" r="45" fill="#ef4444" opacity="0.1" />
                        
                        <!-- Panel Kesalahan Utama -->
                        <rect x="100" y="100" width="300" height="200" rx="12" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />
                        
                        <!-- Header Panel (Menyerupai browser) -->
                        <path d="M 100 112 Q 100 100 112 100 L 388 100 Q 400 100 400 112 L 400 130 L 100 130 Z" fill="#f8fafc" />
                        <circle cx="120" cy="115" r="5" fill="#ef4444" />
                        <circle cx="140" cy="115" r="5" fill="#eab308" />
                        <circle cx="160" cy="115" r="5" fill="#22c55e" />
                        
                        <!-- Kabel terputus kiri -->
                        <path d="M 30 200 C 60 200, 70 180, 100 180" fill="none" stroke="#94a3b8" stroke-width="8" stroke-linecap="round" />
                        <path d="M 120 180 C 140 180, 150 200, 200 200" fill="none" stroke="#64748b" stroke-width="8" stroke-linecap="round" />
                        
                        <!-- Kabel terputus kanan -->
                        <path d="M 470 240 C 440 240, 430 260, 400 260" fill="none" stroke="#94a3b8" stroke-width="8" stroke-linecap="round" />
                        <path d="M 380 260 C 350 260, 340 240, 290 240" fill="none" stroke="#64748b" stroke-width="8" stroke-linecap="round" />
                        
                        <!-- Ikon Tanda Seru di tengah -->
                        <circle cx="250" cy="200" r="40" fill="#fef2f2" />
                        <path d="M 245 175 L 255 175 L 253 205 L 247 205 Z" fill="#ef4444" />
                        <circle cx="250" cy="218" r="4" fill="#ef4444" />
                        
                        <!-- Sparkles/Korslet kiri -->
                        <path d="M 100 160 L 110 170 M 110 160 L 100 170" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round" />
                        <!-- Sparkles/Korslet kanan -->
                        <path d="M 390 270 L 400 280 M 400 270 L 390 280" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>
