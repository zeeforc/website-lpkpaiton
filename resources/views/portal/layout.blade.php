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
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f6fb;
            color: #334155;
        }
        .portal-navbar {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0 20px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            gap: 30px;
            height: 100%;
        }
        .portal-nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            height: 100%;
            position: relative;
            padding: 0 5px;
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
                display: none;
            }
            .portal-content {
                padding: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @if(Auth::check())
    <nav class="portal-navbar">
        <a href="{{ route('portal.biodata') }}" class="portal-brand">
            <i class="fa-solid fa-users"></i>
            Portal Siswa PKL
        </a>
        
        <div class="portal-nav-links">
            <a href="{{ route('portal.biodata') }}" class="portal-nav-link {{ request()->routeIs('portal.biodata') ? 'active' : '' }}">Biodata</a>
            <a href="{{ route('portal.informasi') }}" class="portal-nav-link {{ request()->routeIs('portal.informasi') ? 'active' : '' }}">Informasi PKL</a>
            <a href="{{ route('portal.absensi') }}" class="portal-nav-link {{ request()->routeIs('portal.absensi') ? 'active' : '' }}">Absensi</a>
            <a href="{{ route('portal.laporan') }}" class="portal-nav-link {{ request()->routeIs('portal.laporan') ? 'active' : '' }}">Pengajuan Laporan</a>
        </div>
        
        <div class="dropdown">
            <div class="portal-user-menu" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-user-circle" style="font-size: 1.5rem; color: #64748b;"></i>
                <span style="font-weight: 500; font-size: 0.95rem; color: #475569;">
                    {{ Auth::user()->name }} <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                </span>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li>
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
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
    @stack('scripts')
</body>
</html>
