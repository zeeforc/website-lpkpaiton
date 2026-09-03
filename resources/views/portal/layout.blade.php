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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f6fb;
            color: #334155;
        }
        .portal-navbar {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            min-height: 70px;
        }
        .portal-brand {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .portal-brand i {
            color: #2563eb;
            font-size: 1.3rem;
        }
        .portal-nav-links {
            display: flex;
            gap: 20px;
        }
        .portal-nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 23px 5px;
            position: relative;
            transition: all 0.2s;
        }
        .portal-nav-link:hover {
            color: #2563eb;
        }
        .portal-nav-link.active {
            color: #2563eb;
            font-weight: 600;
        }
        .portal-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #2563eb;
            border-radius: 3px 3px 0 0;
        }
        .portal-user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .portal-user-menu img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        .portal-content {
            padding: 30px 40px;
            max-width: 1400px;
            margin: 0 auto;
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
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            margin-bottom: 20px;
        }
        .card-header-custom {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
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
            border: 1px solid #cbd5e1;
            color: #475569;
            background: #f8fafc;
            font-size: 0.85rem;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-outline-custom:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .btn-outline-primary-custom {
            border: 1px solid #3b82f6;
            color: #3b82f6;
            background: transparent;
            font-size: 0.85rem;
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-outline-primary-custom:hover {
            background: #eff6ff;
        }
        
        @media (max-width: 768px) {
            .portal-nav-links {
                flex-direction: column;
                gap: 5px;
                padding-bottom: 10px;
                padding-top: 10px;
                border-top: 1px solid #f1f5f9;
                margin-top: 10px;
            }
            .portal-nav-link {
                padding: 10px 15px;
                width: 100%;
                border-radius: 6px;
            }
            .portal-nav-link.active {
                background-color: #eff6ff;
            }
            .portal-nav-link.active::after {
                display: none;
            }
            .portal-user-menu {
                padding: 10px 15px;
                margin-bottom: 10px;
                border-top: 1px solid #f1f5f9;
            }
            .portal-content {
                padding: 20px 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @if(Auth::check())
    <nav class="navbar navbar-expand-md portal-navbar py-0">
        <div class="container-fluid px-3 px-md-4">
            <a href="{{ Auth::user()->role === 'guru_pondok' ? route('portal.guru.absensi-rombongan') : route('portal.biodata') }}" class="portal-brand">
                <i class="fa-solid fa-users"></i>
                Portal Siswa PKL
            </a>
            
            <button class="navbar-toggler border-0 shadow-none px-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#portalOffcanvas" aria-controls="portalOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="offcanvas offcanvas-end" tabindex="-1" id="portalOffcanvas" aria-labelledby="portalOffcanvasLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title" id="portalOffcanvasLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body d-flex flex-column flex-md-row align-items-md-center justify-content-md-end">
                    <div class="portal-nav-links ms-md-auto me-md-4 mt-0 pt-0 border-0">
                        @if(Auth::user()->role === 'guru_pondok')
                            <a href="{{ route('portal.guru.absensi-rombongan') }}" class="portal-nav-link {{ request()->routeIs('portal.guru.absensi-rombongan') ? 'active' : '' }}"><i class="fa-solid fa-users me-2"></i> Absensi Rombongan</a>
                        @else
                            <a href="{{ route('portal.biodata') }}" class="portal-nav-link {{ request()->routeIs('portal.biodata') ? 'active' : '' }}">Biodata</a>
                            <a href="{{ route('portal.informasi') }}" class="portal-nav-link {{ request()->routeIs('portal.informasi') ? 'active' : '' }}">Informasi PKL</a>
                            <a href="{{ route('portal.absensi') }}" class="portal-nav-link {{ request()->routeIs('portal.absensi') ? 'active' : '' }}">Absensi</a>
                            <a href="{{ route('portal.laporan') }}" class="portal-nav-link {{ request()->routeIs('portal.laporan') ? 'active' : '' }}">Pengajuan Laporan</a>
                        @endif
                    </div>
                    
                    <div class="dropdown mt-3 mt-md-0 border-top pt-3 border-md-0 pt-md-0 portal-user-menu-wrapper">
                        <div class="portal-user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user-circle" style="font-size: 1.5rem; color: #64748b;"></i>
                            <span style="font-weight: 500; font-size: 0.95rem; color: #475569;">
                                {{ Auth::user()->name }} <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                            </span>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 position-absolute">
                            <li>
                                <form action="{{ route('portal.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endif

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
    </script>
    
    @stack('scripts')
</body>
</html>
