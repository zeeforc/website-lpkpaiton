<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container py-2">
        @php
        $isDarkBrand = request()->is('/') || request()->is('berita/*');
        @endphp

        <a class="navbar-brand d-flex align-items-center" href="/index">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo LPK" height="38" class="me-2" />
            <div class="d-flex flex-column lh-sm">
                <span class="fw-bold {{ $isDarkBrand ? 'text-dark' : 'text-white' }}">
                    Lembaga Pelatihan Kerja
                </span>
                <small class="{{ $isDarkBrand ? 'text-dark' : 'text-white' }}">
                    Paiton Selaras
                </small>
            </div>
        </a>

        <!-- Toggler: buka offcanvas di mobile -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav"
            aria-controls="mobileNav">
            <span class="navbar-toggler-icon text"></span>
        </button>

        <!-- Menu desktop (horizontal biasa) -->
        <div class="collapse navbar-collapse justify-content-end d-none d-lg-flex" id="mainNavbar">
            <ul class="navbar-nav gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="/index">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/kurikulum">Kurikulum</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/pelatihan">Pelatihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sarana">Sarana Prasarana</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Acara & Bantuan
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/berita">Berita</a></li>
                        <li><a class="dropdown-item" href="/galeri">Galeri</a></li>
                        <li><a class="dropdown-item" href="/syarat">Panduan Mendaftar</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Menu mobile: offcanvas glass dari kanan -->
        <div class="offcanvas offcanvas-end offcanvas-glass d-lg-none" tabindex="-1" id="mobileNav">
            <div class="offcanvas-header border-0">
                <h6 class="mb-0">Menu</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link offcanvas-nav-link" href="/index">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link offcanvas-nav-link" href="/kurikulum">Kurikulum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link offcanvas-nav-link" href="/pelatihan">Pelatihan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link offcanvas-nav-link" href="/sarana">Sarana Prasarana</a>
                    </li>
                    <li class="nav-item mt-2">
                        <span class="text-uppercase small text-muted d-block mb-1">Acara</span>
                        <a class="nav-link offcanvas-nav-link ps-3" href="/berita">Berita</a>
                        <a class="nav-link offcanvas-nav-link ps-3" href="/galeri">Galeri</a>
                        <a class="nav-link offcanvas-nav-link ps-3" href="/syarat">Panduan Mendaftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>