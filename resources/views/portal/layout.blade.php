<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Siswa PKL') - LPK Paiton Selaras</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 40%), 
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
            background-attachment: fixed;
            color: #334155;
            min-height: 100vh;
        }
        .portal-layout-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .portal-main-content {
            flex-grow: 1;
            width: 100%;
            transition: padding 0.3s ease;
        }
        .portal-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 30px;
        }
        
        /* Sidebar (Desktop) */
        .portal-sidebar {
            position: fixed;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            width: 75px;
            height: calc(100vh - 40px);
            max-height: 600px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 40px;
            padding: 30px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            z-index: 1000;
        }
        .sidebar-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: #fff;
            color: #3b82f6;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
            transform: translateY(-2px);
        }
        .sidebar-logout {
            color: #ef4444;
        }
        .sidebar-logout:hover {
            background: #fef2f2;
            color: #dc2626;
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.2);
        }

        /* Bottom Bar (Mobile) */
        .portal-bottombar {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 30px;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .bottombar-link {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .bottombar-link.active {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
        }
        .page-header {
            margin-bottom: 25px;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        .page-subtitle {
            font-size: 0.9rem;
            color: #64748b;
        }
        .card-custom {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px -10px rgba(0,0,0,0.08);
        }
        .card-header-custom {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title-custom {
            font-size: 0.95rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .card-title-custom i {
            color: #3b82f6;
        }
        .card-body-custom {
            padding: 20px;
        }
        .data-row {
            display: flex;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        .data-label {
            width: 160px;
            color: #64748b;
            flex-shrink: 0;
        }
        .data-separator {
            width: 20px;
            color: #64748b;
        }
        .data-value {
            color: #1e293b;
            font-weight: 500;
        }
        .btn-outline-custom {
            border: 1px solid rgba(203, 213, 225, 0.8);
            color: #475569;
            background: rgba(248, 250, 252, 0.6);
            font-size: 0.85rem;
            padding: 6px 16px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-outline-custom:hover {
            background: rgba(241, 245, 249, 0.9);
            color: #0F172A;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .btn-outline-primary-custom {
            border: 1px solid #3b82f6;
            color: #3b82f6;
            background: transparent;
            font-size: 0.85rem;
            padding: 6px 16px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-outline-primary-custom:hover {
            background: #3b82f6;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }
        
        /* Glassmorphism Inputs */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            color: #334155;
        }
        .form-control:focus, .form-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15), inset 0 2px 4px rgba(0,0,0,0.02);
            background-color: rgba(255, 255, 255, 0.8);
            transform: translateY(-1px);
        }
        .form-control:disabled, .form-control[readonly], .form-select:disabled {
            background-color: rgba(241, 245, 249, 0.5);
            color: #64748b;
        }
        
        /* SweetAlert Glassmorphism */
        .swal-glass {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px !important;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
            color: #1e293b !important;
        }
        
        @media (min-width: 768px) {
            .portal-main-content {
                padding-left: 110px; /* Space for sidebar */
            }
        }
        @media (max-width: 767px) {
            .portal-main-content {
                padding-bottom: 90px; /* Space for bottom bar */
            }
            .portal-content {
                padding: 20px 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="portal-layout-wrapper">
        @if(Auth::check())
            <!-- Sidebar (Desktop/Tablet) -->
            <aside class="portal-sidebar d-none d-md-flex">
                <a href="{{ Auth::user()->role === 'guru_pondok' ? route('portal.guru.absensi-rombongan') : route('portal.biodata') }}" class="sidebar-brand mb-3" style="text-decoration: none;">
                    <i class="fa-solid fa-users" style="color: #3b82f6; font-size: 1.8rem; filter: drop-shadow(0 4px 6px rgba(59, 130, 246, 0.4));"></i>
                </a>
                
                @if(Auth::user()->role === 'guru_pondok')
                    <a href="{{ route('portal.guru.absensi-rombongan') }}" class="sidebar-link {{ request()->routeIs('portal.guru.absensi-rombongan') ? 'active' : '' }}" title="Absensi Rombongan"><i class="fa-solid fa-users"></i></a>
                @else
                    <a href="{{ route('portal.biodata') }}" class="sidebar-link {{ request()->routeIs('portal.biodata') ? 'active' : '' }}" title="Biodata"><i class="fa-regular fa-user"></i></a>
                    <a href="{{ route('portal.informasi') }}" class="sidebar-link {{ request()->routeIs('portal.informasi') ? 'active' : '' }}" title="Informasi PKL"><i class="fa-regular fa-compass"></i></a>
                    <a href="{{ route('portal.absensi') }}" class="sidebar-link {{ request()->routeIs('portal.absensi') ? 'active' : '' }}" title="Absensi"><i class="fa-regular fa-calendar-check"></i></a>
                    <a href="{{ route('portal.laporan') }}" class="sidebar-link {{ request()->routeIs('portal.laporan') ? 'active' : '' }}" title="Pengajuan Laporan"><i class="fa-regular fa-folder-open"></i></a>
                @endif
                
                <form action="{{ route('portal.logout') }}" method="POST" class="mt-auto m-0 p-0" onsubmit="event.preventDefault(); confirmLogout(this);">
                    @csrf
                    <button type="submit" class="sidebar-link sidebar-logout border-0 bg-transparent" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </aside>
            
            <!-- Bottom Bar (Mobile) -->
            <nav class="portal-bottombar d-flex d-md-none">
                @if(Auth::user()->role === 'guru_pondok')
                    <a href="{{ route('portal.guru.absensi-rombongan') }}" class="bottombar-link {{ request()->routeIs('portal.guru.absensi-rombongan') ? 'active' : '' }}"><i class="fa-solid fa-users"></i></a>
                @else
                    <a href="{{ route('portal.biodata') }}" class="bottombar-link {{ request()->routeIs('portal.biodata') ? 'active' : '' }}"><i class="fa-solid fa-house"></i></a>
                    <a href="{{ route('portal.informasi') }}" class="bottombar-link {{ request()->routeIs('portal.informasi') ? 'active' : '' }}"><i class="fa-regular fa-compass"></i></a>
                    <a href="{{ route('portal.absensi') }}" class="bottombar-link {{ request()->routeIs('portal.absensi') ? 'active' : '' }}"><i class="fa-regular fa-calendar-check"></i></a>
                    <a href="{{ route('portal.laporan') }}" class="bottombar-link {{ request()->routeIs('portal.laporan') ? 'active' : '' }}"><i class="fa-regular fa-folder-open"></i></a>
                @endif
                
                <form action="{{ route('portal.logout') }}" method="POST" class="m-0 p-0" onsubmit="event.preventDefault(); confirmLogout(this);">
                    @csrf
                    <button type="submit" class="bottombar-link border-0 bg-transparent text-danger"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </nav>
        @endif

        <main class="portal-main-content">
            <div class="portal-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
        
        function confirmLogout(form) {
            Swal.fire({
                title: 'Keluar Portal?',
                text: "Apakah Anda yakin ingin keluar dari portal siswa?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                background: 'rgba(255, 255, 255, 0.65)',
                backdrop: 'rgba(15, 23, 42, 0.4)',
                customClass: {
                    popup: 'swal-glass'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
