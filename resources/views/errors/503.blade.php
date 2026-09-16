<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Sedang Dalam Pemeliharaan</title>
    <!-- Framework & Fonts sesuai dengan antislop-ui (konsisten dengan brand) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fafafc; /* Solid neutral background, menghindari glow berlebih */
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        /* Badge status (informatif, bukan dekoratif) */
        .maintenance-badge {
            background-color: #fff4ed; /* Warna turunan dari orange brand */
            color: #fd7a2a;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            border: 1px solid #ffd8c4;
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
        
        /* Tombol yang fungsional (bukan sekadar hiasan) */
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
            transform: translateY(-2px); /* Motion minimalis */
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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row align-items-center justify-content-between g-5">
            <!-- Konten Kiri (Hierarchy sesuai antislop-ui) -->
            <div class="col-lg-5 order-2 order-lg-1">
                <div class="maintenance-badge">
                    <i data-feather="tool" width="16" height="16"></i>
                    Maintenance Mode
                </div>
                
                <h1 class="title">
                    Website Sedang Dalam<br><span>Pemeliharaan</span>
                </h1>
                
                <p class="description">
                    Kami sedang melakukan pemeliharaan dan peningkatan sistem untuk memberikan pengalaman layanan yang lebih baik. Website akan kembali tersedia setelah proses pembaruan selesai.
                </p>
                
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <!-- Fungsional: Refresh bukan semata "Back to Home" palsu -->
                    <button onclick="window.location.reload()" class="btn-primary-custom">
                        <i data-feather="refresh-cw" width="18" height="18"></i>
                        Cek Status Kembali
                    </button>
                    <!-- Fungsional: Bantuan langsung -->
                    <a href="https://wa.me/" class="btn-outline-custom">
                        <i data-feather="message-circle" width="18" height="18"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
            
            <!-- Vektor Kanan -->
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="vector-wrapper text-center">
                    <!-- Inline SVG merepresentasikan sistem sedang dibangun/diperbaiki.
                         Sengaja dibuat bersih, solid, tanpa shadow blur dan gradient berlebih. -->
                    <svg viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Latar belakang lingkaran aksen (solid color, bukan gradient) -->
                        <circle cx="250" cy="200" r="160" fill="#fff4ed" />
                        <circle cx="360" cy="90" r="30" fill="#fd7a2a" opacity="0.1" />
                        <circle cx="100" cy="320" r="45" fill="#fd7a2a" opacity="0.1" />
                        
                        <!-- Panel Website Utama -->
                        <rect x="60" y="110" width="380" height="230" rx="12" fill="#ffffff" stroke="#e2e8f0" stroke-width="2" />
                        
                        <!-- Header Panel (Menyerupai browser) -->
                        <path d="M 60 122 Q 60 110 72 110 L 428 110 Q 440 110 440 122 L 440 140 L 60 140 Z" fill="#f8fafc" />
                        <circle cx="85" cy="125" r="5" fill="#cbd5e1" />
                        <circle cx="105" cy="125" r="5" fill="#cbd5e1" />
                        <circle cx="125" cy="125" r="5" fill="#cbd5e1" />
                        
                        <!-- Struktur Skeleton Dalam Web (Clean, flat) -->
                        <rect x="90" y="165" width="140" height="14" rx="4" fill="#e2e8f0" />
                        <rect x="90" y="195" width="220" height="8" rx="4" fill="#f1f5f9" />
                        <rect x="90" y="215" width="200" height="8" rx="4" fill="#f1f5f9" />
                        <rect x="90" y="235" width="240" height="8" rx="4" fill="#f1f5f9" />
                        
                        <!-- Block Image Placeholder -->
                        <rect x="90" y="260" width="100" height="50" rx="6" fill="#f1f5f9" />
                        <rect x="200" y="260" width="100" height="50" rx="6" fill="#f1f5f9" />
                        
                        <!-- Ikon Gear Utama (Fokus) -->
                        <g transform="translate(320, 220)">
                            <circle cx="0" cy="0" r="45" fill="#fd7a2a" opacity="0.1" />
                            <path d="M -8 -30 L 8 -30 L 10 -20 L -10 -20 Z" fill="#fd7a2a" />
                            <path d="M -8 30 L 8 30 L 10 20 L -10 20 Z" fill="#fd7a2a" />
                            <path d="M -30 -8 L -30 8 L -20 10 L -20 -10 Z" fill="#fd7a2a" />
                            <path d="M 30 -8 L 30 8 L 20 10 L 20 -10 Z" fill="#fd7a2a" />
                            
                            <g transform="rotate(45)">
                                <path d="M -8 -30 L 8 -30 L 10 -20 L -10 -20 Z" fill="#fd7a2a" />
                                <path d="M -8 30 L 8 30 L 10 20 L -10 20 Z" fill="#fd7a2a" />
                            </g>
                            <g transform="rotate(-45)">
                                <path d="M -8 -30 L 8 -30 L 10 -20 L -10 -20 Z" fill="#fd7a2a" />
                                <path d="M -8 30 L 8 30 L 10 20 L -10 20 Z" fill="#fd7a2a" />
                            </g>
                            
                            <circle cx="0" cy="0" r="22" fill="none" stroke="#fd7a2a" stroke-width="8" />
                            <circle cx="0" cy="0" r="8" fill="#fd7a2a" />
                        </g>
                        
                        <!-- Kunci Inggris (Wrench) - Ilustrasi sedang diperbaiki -->
                        <g transform="translate(250, 250) rotate(-45)">
                            <rect x="-10" y="-15" width="70" height="16" rx="8" fill="#94a3b8" />
                            <circle cx="-10" cy="-7" r="22" fill="#64748b" />
                            <circle cx="-10" cy="-7" r="10" fill="#f8fafc" />
                            <polygon points="-5,8 12,-7 -5,-22" fill="#f8fafc" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inisialisasi icon dari feather
        feather.replace();
    </script>
</body>
</html>
