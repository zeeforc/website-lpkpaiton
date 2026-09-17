@extends('layout')
@section('title', 'Home')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/index.css') }}?v={{ time() }}">
@endpush
@section('content')

<!-- Hero -->
<section id="hero" class="hero-section">
    <div class="container p-2">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <p class="hero-badge text-dark mt-4">
                    Lembaga Pelatihan Kerja Paiton Selaras
                </p>
                <h1 class="hero-title hero-gradient-text mb-4" style="font-size: 3rem;">
                    <span>Selamat Datang di</span>
                    <strong>Lembaga Pelatihan Kerja</strong> <br>
                    <span>Paiton Selaras</span>
                </h1>
                <p class="fs-6 text-dark mb-4">
                    Kami hadir untuk menyiapkan tenaga kerja yang terampil dan siap
                    pakai melalui program pelatihan terstruktur di bidang mekanik dan
                    listrik, sesuai kebutuhan industri di wilayah Paiton dan
                    sekitarnya.
                </p>

                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="#kontak" class="btn btn-gradient"> Hubungi Kami </a>
                </div>
                
                <div class="mt-4 pt-3 overflow-hidden">
                    <div class="d-inline-flex align-items-center px-4 py-2 visitor-badge-anim" style="background: rgba(253, 122, 42, 0.1); border-left: 4px solid #fd7a2a; border-radius: 4px;">
                        <i data-feather="users" class="me-2" style="color: #fd7a2a; width: 18px; height: 18px;"></i>
                        <span class="text-dark small fw-medium">
                            Dipercaya & dikunjungi oleh <strong style="color: #fd7a2a;">{{ $visitorCount }}</strong> orang
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="{{ $home && $home->bg_image ? asset('storage/' . $home->bg_image) : asset('assets/mekanik-org.webp') }}"
                        alt="Pelatihan kerja Paiton Selaras" class="hero-image" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi dan Foto Kantor -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="glass-card h-100 visi-misi-card">
                    <div class="visi-tabs">
                        <span class="active" data-tab="visi">Visi</span>
                        <span data-tab="misi">Misi</span>
                    </div>
                    <h2 class="h4 mb-3 visi-title" data-visi-title="{{ $vimi->visi_title ?? 'Visi Kami' }}"
                        data-misi-title="{{ $vimi->misi_title ?? 'Misi Kami' }}">
                        {{ $vimi->visi_title ?? 'Visi Kami' }}
                    </h2>
                    <p class="small text-dark visi-text">
                        {!! $vimi->visi_text ?? '' !!}
                    </p>
                    <p class="small text-dark misi-text d-none">
                        {{ $vimi->misi_text ?? '' }}
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card h-100 p-0 overflow-hidden">
                    <!-- Ganti src sesuai foto kantor -->
                    <img src="{{asset('assets/map.webp')}}" alt="Kantor LPK Paiton Selaras" class="hero-image" />
                    <div class="p-3 text-center small text-dark">
                        Kantor Lembaga Pelatihan Kerja Paiton Selaras
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang -->
<section id="tentang" class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <div class="mb-4">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Profil Lembaga</p>
                    <h2 class="display-6 fw-bold mb-0 text-dark" style="letter-spacing: -1px;">Tentang <span style="color: #fd7a2a;">LPK</span> Kami</h2>
                </div>
                <p class="small text-dark lh-lg mb-4">
                    LPK Paiton Selaras didirikan untuk memenuhi kebutuhan tenaga kerja
                    kompeten di kawasan Paiton dan sekitarnya, serta memberikan
                    kesempatan pelatihan bagi masyarakat yang ingin meningkatkan
                    keterampilan.
                </p>
            </div>
            <div class="col-lg-8">
                <div class="glass-card">
                    <p class="small text-dark mb-0">
                        LPK Paiton Selaras menyelenggarakan pelatihan kerja di bidang
                        teknik mekanik dan listrik, dengan fokus pada praktik lapangan
                        dan pemahaman standar keselamatan kerja. Program pelatihan
                        dirancang bersama mitra industri sehingga materi selalu relevan
                        dengan kebutuhan nyata di lapangan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Struktur Team -->
<section id="struktur" class="section-padding">
    @php
    use Illuminate\Support\Facades\Storage;
    @endphp
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Pengelola & Instruktur</p>
            <h2 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -1px;">Struktur <span style="color: #fd7a2a;">Team</span></h2>
            <p class="small mx-auto text-dark lh-lg" style="max-width: 520px">
                Tim pengelola dan instruktur yang memiliki pengalaman di bidang
                industri dan pendidikan vokasi, siap mendampingi peserta selama
                proses pelatihan.
            </p>
        </div>

        @php
            // Isolate Koordinator
            $koordinator = $teams->firstWhere('position', 'Koordinator') ?? $teams->firstWhere('position', 'koordinator');
            $others = $teams->reject(function($t) use ($koordinator) {
                return $koordinator && $t->id === $koordinator->id;
            });
        @endphp

        @if($koordinator)
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="team-glass-card">
                    <img src="{{ $koordinator->photo ? asset('storage/' . $koordinator->photo) : asset('assets/team-image/default.jpg') }}" alt="{{ $koordinator->name }}" class="team-img-full">
                    <div class="team-info-overlay text-center">
                        <div class="team-name">{{ $koordinator->name }}</div>
                        <div class="team-role">{{ $koordinator->position }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4 justify-content-center">
            @forelse ($others as $team)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="team-glass-card">
                    <img src="{{ $team->photo ? asset('storage/' . $team->photo) : asset('assets/team-image/default.jpg') }}" alt="{{ $team->name }}" class="team-img-full">
                    <div class="team-info-overlay text-center">
                        <div class="team-name">{{ $team->name }}</div>
                        <div class="team-role">{{ $team->position }}</div>
                    </div>
                </div>
            </div>
            @empty
                @if(!$koordinator)
                    <p class="text-center text-muted">Belum ada data team.</p>
                @endif
            @endforelse
        </div>
    </div>
</section>

<!-- Program Pelatihan -->
<section id="program" class="section-padding" style="background: rgba(255,255,255,0.3);">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Jurusan & Keahlian</p>
            <h2 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -1px;">Program <span style="color: #fd7a2a;">Pelatihan</span></h2>
            <p class="small mx-auto text-dark lh-lg" style="max-width: 520px">
                Kami menawarkan program pelatihan vokasi yang dirancang khusus untuk memenuhi standar industri saat ini.
            </p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Welding -->
            <div class="col-md-4">
                <div class="glass-card h-100 text-center py-4">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mx-auto mb-3" style="width: 64px; height: 64px; color: #fd7a2a;">
                        <i data-feather="tool"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Welding (Pengelasan)</h3>
                    <p class="small text-dark mb-0">
                        Pelatihan teknik pengelasan standar industri dengan praktik langsung menggunakan peralatan modern untuk menghasilkan welder yang handal dan bersertifikasi.
                    </p>
                </div>
            </div>
            <!-- Mesin -->
            <div class="col-md-4">
                <div class="glass-card h-100 text-center py-4">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mx-auto mb-3" style="width: 64px; height: 64px; color: #fd7a2a;">
                        <i data-feather="settings"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Mesin</h3>
                    <p class="small text-dark mb-0">
                        Kerja bangku, bubut dasar, dan perawatan mesin industri untuk mempersiapkan peserta menjadi teknisi mekanik yang sigap dan kompeten.
                    </p>
                </div>
            </div>
            <!-- Listrik -->
            <div class="col-md-4">
                <div class="glass-card h-100 text-center py-4">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mx-auto mb-3" style="width: 64px; height: 64px; color: #fd7a2a;">
                        <i data-feather="zap"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Listrik</h3>
                    <p class="small text-dark mb-0">
                        Dasar instalasi listrik, relay, panel, dan sistem kontrol sederhana hingga penerapan di lingkungan kerja nyata dengan pendampingan instruktur profesional.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita Terbaru -->
<section id="berita" class="section-padding">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div class="mb-4 mb-md-0">
                <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Informasi Terkini</p>
                <h2 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -1px;">Berita <span style="color: #fd7a2a;">Terbaru</span></h2>
                <p class="small text-dark mt-2 mb-0 lh-lg">
                    Kabar terkini dan informasi seputar kegiatan LPK Paiton Selaras.
                </p>
            </div>
            <a href="{{ route('berita.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-4 d-none d-md-inline-flex">Lihat Semua</a>
        </div>

        <div class="row g-4">
            @forelse($latestBerita as $berita)
            <div class="col-md-4">
                <div class="news-card h-100 d-flex flex-column">
                    <div class="news-img-wrapper">
                        <img src="{{ $berita->berita_utama_image ? asset('storage/' . $berita->berita_utama_image) : asset('assets/placeholder.jpg') }}" alt="{{ $berita->berita_utama_title }}">
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="text-muted small mb-2 d-flex align-items-center fw-medium">
                            <i data-feather="calendar" class="me-2" style="width: 14px; height: 14px; color: #fd7a2a;"></i> 
                            {{ \Carbon\Carbon::parse($berita->tgl_berita ?? $berita->created_at)->format('d M Y') }}
                        </div>
                        <h5 class="h6 fw-bold mb-3 text-dark lh-base">{{ Str::limit($berita->berita_utama_title, 55) }}</h5>
                        <p class="small text-secondary mb-4 flex-grow-1" style="line-height: 1.6;">{{ Str::limit(strip_tags($berita->berita_utama_desk), 100) }}</p>
                        <a href="{{ route('berita.show', $berita->slug) }}" class="btn-news-link mt-auto d-inline-flex align-items-center">
                            Baca Selengkapnya <i data-feather="arrow-right" class="ms-1" style="width: 16px; height: 16px;"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted small">Belum ada berita terbaru saat ini.</p>
            </div>
            @endforelse
        </div>
        <div class="text-center mt-4 d-md-none">
            <a href="{{ route('berita.index') }}" class="btn btn-outline-dark btn-sm rounded-pill w-100">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<!-- Testimoni Alumni -->
<section id="testimoni" class="section-padding">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5">
            <div class="mb-4 mb-md-0">
                <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Testimoni Alumni</p>
                <h2 class="display-6 fw-bold mb-0 text-dark" style="letter-spacing: -1px;">Apa Kata <span style="color: #fd7a2a;">Alumni</span> Kami</h2>
            </div>
            <div class="d-none d-md-flex gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle border border-dark hover-primary" style="width: 48px; height: 48px; cursor: pointer; transition: all 0.3s;">
                    <i data-feather="arrow-left" style="width: 20px; height: 20px;"></i>
                </div>
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-white" style="width: 48px; height: 48px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i data-feather="arrow-right" style="width: 20px; height: 20px;"></i>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @forelse($testimonis as $testimoni)
            <div class="col-md-4">
                <div class="glass-card h-100 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            @if($testimoni->photo)
                                <img src="{{ asset('storage/' . $testimoni->photo) }}?v={{ time() }}" alt="{{ $testimoni->name }}" class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i data-feather="user" class="text-primary"></i>
                                </div>
                            @endif
                            <div class="small fw-semibold text-primary px-3 py-1 rounded-pill" style="background: rgba(253, 122, 42, 0.1); border: 1px solid rgba(253, 122, 42, 0.2); color: #fd7a2a !important;">
                                <i data-feather="briefcase" style="width: 12px; height: 12px; margin-right: 4px;"></i> 
                                {{ $testimoni->company }}
                            </div>
                        </div>
                    </div>
                    
                    <i data-feather="message-square" class="text-primary opacity-25 mb-3" style="width: 32px; height: 32px; color: #fd7a2a !important;"></i>
                    <div class="testimoni-quote-container mb-4">
                        <p class="small text-dark lh-lg testimoni-quote" style="font-weight: 500; margin-bottom: 0;">
                            "{{ $testimoni->quote }}"
                        </p>
                        <button class="btn btn-link p-0 text-decoration-none small fw-bold text-primary btn-read-more" style="display: none; color: #fd7a2a !important;" onclick="toggleQuote(this)">Baca selengkapnya</button>
                    </div>
                    
                    <div class="mt-auto border-start border-2 ps-3" style="border-color: #dee2e6 !important;">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $testimoni->name }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $testimoni->role }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $testimoni->company }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted small">Belum ada testimoni saat ini.</p>
            </div>
            @endforelse
        </div>
        
        <div class="d-flex d-md-none gap-2 justify-content-center mt-5">
            <div class="d-flex align-items-center justify-content-center rounded-circle border border-dark" style="width: 48px; height: 48px; cursor: pointer;">
                <i data-feather="arrow-left" style="width: 20px; height: 20px;"></i>
            </div>
            <div class="d-flex align-items-center justify-content-center rounded-circle bg-dark text-white" style="width: 48px; height: 48px; cursor: pointer;">
                <i data-feather="arrow-right" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
    </div>
</section>


<!-- Alamat Kantor -->
<section id="alamat" class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="mb-5">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Lokasi Kami</p>
                    <h2 class="display-6 fw-bold mb-0 text-dark" style="letter-spacing: -1px;">Alamat <span style="color: #fd7a2a;">Kantor</span></h2>
                </div>
                <div class="map-card">
                    <p class="small text-dark mb-3">
                        LPK Paiton Selaras berlokasi di:
                    </p>
                    <p class="small mb-3">
                        Jl. Brigaan. Dusun Pesisir, Sumberanyar,<br />
                        Kec. Paiton, Kabupaten Probolinggo,<br />
                        Jawa Timur 67291, Indonesia.
                    </p>
                    <p class="small text-dark mb-0">
                        Untuk petunjuk arah yang lebih rinci, silakan buka map
                        di bawah ini dan ikuti petunjuk arah menuju lokasi Lpk Paiton Selaras.
                    </p>
                </div>
            </div>

            <div class="col-lg-7 align-self-center">
                <div class="proper-map-container">
                    <!-- Google Maps Iframe -->
                    <iframe
                        src="https://maps.google.com/maps?q=LPK%20Paiton%20Selaras,%20Probolinggo&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                    <!-- Overlay Button (Floating) -->
                    <div class="map-overlay-action">
                        <a href="https://www.google.com/maps/search/?api=1&query=LPK+Paiton+Selaras" target="_blank"
                            rel="noopener noreferrer" class="btn btn-gradient d-inline-flex align-items-center gap-2">
                            Buka di Google Maps ➜
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kontak -->
<section id="kontak" class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mb-4">
                    <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Hubungi Kami</p>
                    <h2 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -1px;">Kontak <span style="color: #fd7a2a;">Kami</span></h2>
                </div>
                <p class="small text-dark mb-4 lh-lg">
                    Hubungi kami untuk informasi jadwal pelatihan, persyaratan
                    pendaftaran, kerja sama industri, atau kunjungan studi ke
                    fasilitas pelatihan.
                </p>

                <div class="glass-card mb-4" style="padding: 2.5rem;">

                    @if ($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success small">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                style="background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); border-radius: 12px; padding: 12px 16px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"
                                placeholder="Masukkan nama Anda" value="{{ old('name') }}" required />
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email</label>
                            <input type="email" name="email" class="form-control"
                                style="background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); border-radius: 12px; padding: 12px 16px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"
                                placeholder="nama@email.com" value="{{ old('email') }}" required />
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">Pesan</label>
                            <textarea name="message" class="form-control" rows="4"
                                style="background: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.8); border-radius: 12px; padding: 12px 16px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);"
                                placeholder="Tuliskan pertanyaan atau kebutuhan pelatihan Anda..."
                                required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-gradient w-100" style="border-radius: 12px; padding: 12px; font-weight: 600;">
                            <i data-feather="send" class="me-2" style="width: 18px; height: 18px;"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card h-100" style="padding: 2.5rem;">
                    <h3 class="h5 mb-4 fw-bold">Informasi Kontak</h3>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 48px; height: 48px; color: #fd7a2a;">
                            <i data-feather="phone"></i>
                        </div>
                        <div>
                            <div class="small text-uppercase text-secondary fw-semibold" style="letter-spacing: 0.5px;">
                                Phone
                            </div>
                            <div class="fw-medium text-dark">+62 811-3059-8801</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 48px; height: 48px; color: #fd7a2a;">
                            <i data-feather="mail"></i>
                        </div>
                        <div>
                            <div class="small text-uppercase text-secondary fw-semibold" style="letter-spacing: 0.5px;">
                                Email
                            </div>
                            <div class="fw-medium text-dark">doclpkselaras@gmail.com</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 48px; height: 48px; color: #fd7a2a;">
                            <i data-feather="share-2"></i>
                        </div>
                        <div>
                            <div class="small text-uppercase text-secondary fw-semibold" style="letter-spacing: 0.5px;">
                                Social
                            </div>
                            <div class="d-flex gap-3 mt-1">
                                <a href="#" class="text-dark text-decoration-none fw-medium hover-primary d-flex align-items-center"><i data-feather="facebook" class="me-1" style="width: 16px; height: 16px;"></i> Facebook</a>
                                <a href="#" class="text-dark text-decoration-none fw-medium hover-primary d-flex align-items-center"><i data-feather="instagram" class="me-1" style="width: 16px; height: 16px;"></i> Instagram</a>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4" style="border-color: rgba(0,0,0,0.1);">

                    <p class="small text-secondary m-0 d-flex" style="line-height: 1.6;">
                        <i data-feather="info" class="me-2 flex-shrink-0" style="width: 18px; height: 18px; margin-top: 2px;"></i> 
                        <span>Staf administrasi kami siap membantu pada hari kerja dengan jam layanan pukul 08.00 sampai 16.00 waktu setempat.</span>
                    </p>
                </div>
            </div>            
        </div>
    </div>
</section>

<script>
    // Testimoni Read More Logic
    window.addEventListener('load', function() {
        const quotes = document.querySelectorAll('.testimoni-quote');
        quotes.forEach(quote => {
            if (quote.scrollHeight > quote.clientHeight + 2) {
                const btn = quote.nextElementSibling;
                if (btn && btn.classList.contains('btn-read-more')) {
                    btn.style.display = 'inline-block';
                }
            }
        });
    });

    function toggleQuote(btn) {
        const quoteText = btn.previousElementSibling;
        if (quoteText.classList.contains('expanded')) {
            quoteText.classList.remove('expanded');
            btn.textContent = 'Baca selengkapnya';
        } else {
            quoteText.classList.add('expanded');
            btn.textContent = 'Tutup';
        }
    }
</script>

@endsection