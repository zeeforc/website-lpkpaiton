@extends('layout')
@section('title', 'Berita')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/berita.css') }}?v={{ time() }}" />
@endpush
@section('content')

<main>
    <section class="hero-news">
        <div class="hero-overlay-top"></div>
        <div class="container hero-inner">
            <div class="row align-items-center gy-4">
                <div class="col-lg-4">
                    <h1 class="hero-title text-light">
                        Berita LPK<br />
                        Paiton<br />
                        Selaras
                    </h1>
                    <a href="#news">
                        <button class="btn-cta my-4 text-light">Selengkapnya</button>
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="hero-illustration-wrap">
                        <div class="hero-slider">
                            <div class="hero-slide active">
                                <img src="{{ asset('assets/slider/slide1.webp') }}" alt="">
                            </div>
                            <!-- <div class="hero-slide">
                                <img src="{{ asset('assets/slider/slide2.webp') }}" alt="">
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="marquee-wrapper">
                <div class="marquee-content">
                    {{-- Set 1 --}}
                    <div class="marquee-text">
                        <span class="runningDate"></span>
                        <span class="marquee-dot">•</span>
                        <span>Berita LPK Paiton Selaras</span>
                        <span class="marquee-dot">•</span>
                        <span>Pendaftaran Gelombang Baru Dibuka</span>
                        <span class="marquee-dot">•</span>
                        <span>Program Pelatihan Terbaru</span>
                        <span class="marquee-dot">•</span>
                    </div>
                    {{-- Set 2 (for seamless loop) --}}
                    <div class="marquee-text">
                        <span class="runningDate"></span>
                        <span class="marquee-dot">•</span>
                        <span>Berita LPK Paiton Selaras</span>
                        <span class="marquee-dot">•</span>
                        <span>Pendaftaran Gelombang Baru Dibuka</span>
                        <span class="marquee-dot">•</span>
                        <span>Program Pelatihan Terbaru</span>
                        <span class="marquee-dot">•</span>
                    </div>
                </div>
            </div>

            {{-- HIGHLIGHT POPUP (CAROUSEL DINAMIS) --}}
            @php
            // Gabungkan $latest dan $others untuk mendapatkan koleksi berita (maksimal 5 untuk dirotasi)
            $popupList = collect([$latest])->merge($others->items())->filter(function($item) {
            return !empty($item) && !empty($item->slug);
            })->take(5)->map(function($item) {
            return [
            'title' => \Illuminate\Support\Str::limit($item->berita_utama_title, 45),
            'image' => $item->image_url,
            'url' => route('berita.show', ['beritaUtama' => $item->slug])
            ];
            })->values()->toJson();
            @endphp

            <div class="news-popup-highlight" id="newsPopupHighlight">
                <button class="popup-close-btn" id="closePopupBtn" aria-label="Tutup Popup">
                    &times;
                </button>
                <a href="#" class="popup-link" id="popupLink">
                    <div class="popup-content">
                        <div class="popup-img-wrap">
                            <img src="" alt="Highlight" class="popup-img" id="popupImage">
                        </div>
                        <div class="popup-text">
                            <span class="popup-badge">Sorotan Utama</span>
                            <h4 class="popup-title" id="popupTitle">Memuat...</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- RINGKASAN DATA PESERTA PKL --}}
    <section class="section-pkl-stats">
        <div class="container">
            <div class="row align-items-center gy-5">

                {{-- LEFT: Text --}}
                <div class="col-lg-5">
                    <p class="pkl-stats-label">DATA PESERTA</p>
                    <h2 class="pkl-stats-title">Peserta PKL</h2>
                    <p class="pkl-stats-desc">
                        Berikut adalah ringkasan data peserta Praktik Kerja Lapangan yang telah tercatat di LPK Paiton Selaras.
                    </p>
                    <a href="{{ route('berita.index') }}#news" class="pkl-stats-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        Lihat Selengkapnya
                    </a>
                </div>

                {{-- RIGHT: Stats Card --}}
                <div class="col-lg-7">
                    <div class="pkl-stats-card">
                        <h6 class="pkl-stats-card-title">Ringkasan Data Peserta PKL</h6>

                        {{-- Row 1: 2 big cards --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <div class="pkl-stat-item pkl-stat-orange">
                                    <div class="pkl-stat-icon-wrap pkl-icon-orange">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                    </div>
                                    <div class="pkl-stat-info">
                                        <span class="pkl-stat-label">Total Peserta PKL</span>
                                        <span class="pkl-stat-number counter" data-target="{{ $pklStat?->total_peserta ?? 0 }}">0</span>
                                        <span class="pkl-stat-sublabel">Peserta pernah PKL</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pkl-stat-item pkl-stat-blue">
                                    <div class="pkl-stat-icon-wrap pkl-icon-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <div class="pkl-stat-info">
                                        <span class="pkl-stat-label">Peserta PKL Saat Ini</span>
                                        <span class="pkl-stat-number counter" data-target="{{ $pklStat?->peserta_aktif ?? 0 }}">0</span>
                                        <span class="pkl-stat-sublabel">Peserta sedang PKL</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Row 2: 3 small cards --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-4">
                                <div class="pkl-stat-item pkl-stat-sm pkl-stat-green">
                                    <div class="pkl-stat-icon-wrap pkl-icon-green pkl-icon-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                    </div>
                                    <div class="pkl-stat-info">
                                        <span class="pkl-stat-label">Jurusan</span>
                                        <span class="pkl-stat-number pkl-stat-number-sm counter" data-target="{{ $pklStat?->jumlah_jurusan ?? 0 }}">0</span>
                                        <span class="pkl-stat-sublabel">Jurusan peserta PKL</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="pkl-stat-item pkl-stat-sm pkl-stat-purple">
                                    <div class="pkl-stat-icon-wrap pkl-icon-purple pkl-icon-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                    </div>
                                    <div class="pkl-stat-info">
                                        <span class="pkl-stat-label">Sekolah / Kampus</span>
                                        <span class="pkl-stat-number pkl-stat-number-sm counter" data-target="{{ $pklStat?->jumlah_sekolah ?? 0 }}">0</span>
                                        <span class="pkl-stat-sublabel">Asal sekolah / kampus</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="pkl-stat-item pkl-stat-sm pkl-stat-teal">
                                    <div class="pkl-stat-icon-wrap pkl-icon-teal pkl-icon-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                                    </div>
                                    <div class="pkl-stat-info">
                                        <span class="pkl-stat-label">Program</span>
                                        <span class="pkl-stat-number pkl-stat-number-sm counter" data-target="{{ $pklStat?->jumlah_program ?? 0 }}">0</span>
                                        <span class="pkl-stat-sublabel">Program PKL tersedia</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="pkl-stats-info-box">
                            <div class="pkl-stats-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <p class="pkl-stats-info-text">
                                Data peserta PKL selalu diperbarui secara berkala untuk memastikan informasi yang akurat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CHART SECTION --}}
            <style>
                .pkl-chart-outer { background: #f0f4ff; border-radius: 20px; padding: 0; }
                .pkl-chart-outer-header { padding: 0 4px 16px 4px; }
                .pkl-chart-outer-title { font-family: "Outfit", sans-serif; font-weight: 800; font-size: 1.4rem; color: #111827; margin: 0 0 4px 0; }
                .pkl-chart-outer-subtitle { font-family: "Outfit", sans-serif; font-size: 0.85rem; color: #6b7280; margin: 0; }
                .pkl-chart-card { background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; padding: 20px 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
                .pkl-chart-title { font-family: "Outfit", sans-serif; font-weight: 700; color: #111827 !important; font-size: 0.95rem; }
                .pkl-chart-legend { display: flex; align-items: center; gap: 6px; }
                .pkl-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
                .pkl-legend-orange { background-color: #fd7a2a; }
                .pkl-legend-dark { background-color: #1a1a2e; }
                .pkl-legend-text { font-family: "Outfit", sans-serif; font-size: 0.78rem; color: #6b7280; font-weight: 500; }
                .pkl-chart-select { font-family: "Outfit", sans-serif; font-size: 0.78rem; color: #374151; font-weight: 500; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 999px; padding: 4px 12px; cursor: pointer; outline: none; appearance: auto; }
                .pkl-chart-select:focus { border-color: #fd7a2a; box-shadow: 0 0 0 2px rgba(253,122,42,0.15); }
            </style>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="pkl-chart-outer">
                        <div class="pkl-chart-outer-header">
                            <h5 class="pkl-chart-outer-title">Statistik Peserta PKL</h5>
                            <p class="pkl-chart-outer-subtitle">Lihat pertumbuhan peserta di setiap tahunnya</p>
                        </div>
                        <div class="pkl-chart-card">
                            <div class="pkl-chart-header d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="pkl-chart-title m-0">Pertumbuhan Peserta PKL</h6>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="pkl-chart-legend">
                                        <span class="pkl-legend-dot pkl-legend-orange"></span>
                                        <span class="pkl-legend-text">Total Peserta</span>
                                    </div>
                                    <select id="yearRangeFilter" class="pkl-chart-select">
                                        <option value="all" selected>Semua Tahun</option>
                                        <option value="5">5 Tahun Terakhir</option>
                                        <option value="3">3 Tahun Terakhir</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pkl-chart-body" style="position: relative; height: 280px; width: 100%;">
                                <canvas id="pklYearlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- BERITA TERBARU --}}

    <section class="section-news-latest" id="news">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-4 d-flex flex-column align-item-start justify-content-start">
                    <div class="latest-text-block d-flex flex-column justify-content-start">
                        <h6 class="section-subtitle text-dark text-start">Berita</h6>
                        <h2 class="section-title text-dark text-start">Terbaru</h2>
                        <p class="section-body text-dark text-start ms-auto">
                            Seluruh kegiatan yang dilaksanakan kita dokumentasikan dan
                            ditampilkan di halaman ini karena kenangan yang disimpan dalam
                            sebuah foto akan selalu abadi.
                        </p>

                        @if($latest && !empty($latest->slug))
                        <div class="text-light text-start">
                            <a href="{{ route('berita.show', ['beritaUtama' => $latest->slug]) }}">
                                <button class="btn-cta">Selengkapnya</button>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-8">
                    @if($latest && !empty($latest->slug))
                    <a href="{{ route('berita.show', ['beritaUtama' => $latest->slug]) }}" class="latest-card-link">
                        <div class="glass-card-latest">
                            <div class="latest-image-wrap">
                                <img src="{{ $latest->image_url }}" alt="{{ $latest->berita_utama_title }}">
                            </div>
                            <div class="latest-caption">
                                <div class="latest-caption-main">
                                    <div class="latest-title">
                                        {{ $latest->berita_utama_title }}
                                    </div>
                                </div>
                                <div class="latest-date">
                                    {{ \Carbon\Carbon::parse($latest->tgl_berita)->format('d/m/y') }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @else
                    <p class="text-center">Belum ada berita.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- BERITA LAINNYA --}}
    <section class="section-news-list">
        <div class="container position-relative">
            <div class="row align-items-center gy-4">
                <div class="col-lg-4">
                    <div class="other-news-text">
                        <h6 class="section-subtitle text-start text-dark">Berita</h6>
                        <h2 class="section-title text-start text-dark">Lainnya</h2>
                        <p class="section-body text-start text-dark ms-auto">
                            Seluruh kegiatan yang dilaksanakan kita dokumentasikan dan
                            ditampilkan di halaman ini karena kenangan yang disimpan dalam
                            sebuah foto akan selalu abadi.
                        </p>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="glass-panel">
                        <div class="news-list-scroll" id="newsListScroll">
                            <div class="row g-3 mb-3">
                                @forelse($others as $berita)
                                @continue(empty($berita->slug))

                                <div class="col-sm-6">
                                    <a href="{{ route('berita.show', ['beritaUtama' => $berita->slug]) }}"
                                        class="news-preview">
                                        <div class="news-thumb">
                                            <img src="{{ $berita->image_url }}" alt="{{ $berita->berita_utama_title }}">
                                        </div>
                                        <div class="news-caption">
                                            {{ $berita->berita_utama_title }}
                                        </div>
                                    </a>
                                </div>
                                @empty
                                <p class="text-center">Belum ada berita lainnya.</p>
                                @endforelse
                            </div>
                        </div>

                        <button class="scroll-down-btn" id="newsScrollDown" aria-label="Scroll Berita Lainnya">
                            <span>➜</span>
                        </button>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $others->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('newsPopupHighlight');
        const closeBtn = document.getElementById('closePopupBtn');
        const popupLink = document.getElementById('popupLink');
        const popupImage = document.getElementById('popupImage');
        const popupTitle = document.getElementById('popupTitle');

        // Mengambil array 5 berita dari PHP
        const popupNews = {!! $popupList !!};

        let currentIndex = 0;
        let popupInterval;
        let hideTimeout;

        if (popup && closeBtn && popupNews.length > 0) {

            // Fungsi untuk mengganti data berita di dalam popup secara dinamis
            const updatePopupContent = () => {
                const currentNews = popupNews[currentIndex];
                popupLink.href = currentNews.url;
                popupImage.src = currentNews.image;
                popupTitle.textContent = currentNews.title;

                // Pindah ke indeks berita berikutnya (looping ke 0 jika sudah mencapai akhir array)
                currentIndex = (currentIndex + 1) % popupNews.length;
            };

            // Fungsi menjalankan siklus popup
            const runPopupCycle = () => {
                // Update konten dulu saat popup sedang bersembunyi (di bawah)
                updatePopupContent();

                // Munculkan popup ke atas
                popup.classList.add('show');

                // Sembunyikan setelah 20 detik
                hideTimeout = setTimeout(() => {
                    popup.classList.remove('show');
                }, 20000);
            };

            // Memulai siklus pertama setelah 2 detik halaman diload
            setTimeout(() => {
                runPopupCycle();

                // Jalankan siklus berulang setiap 24 detik (20 detik tampil + 4 detik hilang)
                popupInterval = setInterval(runPopupCycle, 22000);
            }, 2000);

            // Matikan popup sepenuhnya jika tombol X diklik user
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                popup.classList.remove('show');
                clearInterval(popupInterval);
                clearTimeout(hideTimeout);
            });
        }

        // --- Marquee Date Logic ---
        const dateElements = document.querySelectorAll('.runningDate');
        if (dateElements.length > 0) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date().toLocaleDateString('id-ID', options);
            dateElements.forEach(el => {
                el.textContent = today;
            });
        }

        // --- Stats Counter Animation ---
        const counters = document.querySelectorAll('.counter');
        const speed = 100; // Semakin kecil semakin cepat

        const animateCounters = () => {
            counters.forEach(counter => {
                const animate = () => {
                    const target = +counter.getAttribute('data-target');
                    // Hapus semua karakter non-digit (titik, koma) biar nggak error pas di-parse
                    const count = +counter.innerText.replace(/\D/g, '');
                    const inc = target / speed;

                    if (count < target) {
                        // Math.ceil agar nambahnya proporsional
                        counter.innerText = Math.ceil(count + inc).toLocaleString('id-ID');
                        setTimeout(animate, 20);
                    } else {
                        counter.innerText = target.toLocaleString('id-ID');
                    }
                }
                animate();
            });
        };

        // Buat observer biar animasinya jalan pas di-scroll ke view
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    // Stop observing once animated
                    observer.disconnect();
                }
            });
        }, {
            threshold: 0.5 // Jalan pas setengah card kelihatan
        });

        // Trigger observer untuk container stats
        const statsSection = document.querySelector('.section-pkl-stats');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // --- Chart.js Implementation ---
        const rawYearlyData = @json($pklStat?->yearly_data ?? []);
        // rawYearlyData is an array of objects: { year: "2019", total: "150" }
        
        // Sort data by year ascending just in case it's not sorted in DB
        const sortedData = [...rawYearlyData].sort((a, b) => parseInt(a.year) - parseInt(b.year));
        
        const ctx = document.getElementById('pklYearlyChart');
        if (ctx && sortedData.length > 0) {
            let pklChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sortedData.map(item => item.year),
                    datasets: [
                        {
                            label: 'Dasar',
                            data: sortedData.map(item => Math.floor(Number(item.total) * 0.4)),
                            backgroundColor: '#1a1a2e',
                            hoverBackgroundColor: '#111122',
                            borderRadius: { topLeft: 0, topRight: 0, bottomLeft: 6, bottomRight: 6 },
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        },
                        {
                            label: 'Total Peserta',
                            data: sortedData.map(item => Number(item.total) - Math.floor(Number(item.total) * 0.4)),
                            backgroundColor: '#fd7a2a',
                            hoverBackgroundColor: '#e86a20',
                            borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                            borderSkipped: false,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f9fafb',
                            bodyColor: '#fd7a2a',
                            borderColor: 'rgba(253,122,42,0.3)',
                            borderWidth: 1,
                            titleFont: { size: 12, family: "'Outfit', sans-serif", weight: '600' },
                            bodyFont: { size: 14, family: "'Outfit', sans-serif", weight: 'bold' },
                            padding: 12,
                            displayColors: false,
                            filter: function(item) { return item.datasetIndex === 1; },
                            callbacks: {
                                title: function(context) { return 'Tahun ' + context[0].label; },
                                label: function(context) {
                                    const idx = context.dataIndex;
                                    const total = pklChart.data.datasets[0].data[idx] + pklChart.data.datasets[1].data[idx];
                                    return total.toLocaleString('id-ID') + ' Peserta';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.06)', drawBorder: false },
                            border: { display: false },
                            ticks: {
                                font: { family: "'Outfit', sans-serif", size: 11 },
                                color: '#9ca3af',
                                callback: function(value) { return value.toLocaleString('id-ID'); }
                            }
                        },
                        x: {
                            stacked: true,
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                font: { family: "'Outfit', sans-serif", size: 11 },
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });

            // Filter logic
            const yearFilter = document.getElementById('yearRangeFilter');
            
            const updateChartData = (range) => {
                let filteredData = [...sortedData];
                if (range !== 'all') {
                    const limit = parseInt(range);
                    filteredData = filteredData.slice(Math.max(filteredData.length - limit, 0));
                }
                
                pklChart.data.labels = filteredData.map(item => item.year);
                pklChart.data.datasets[0].data = filteredData.map(item => Math.floor(Number(item.total) * 0.4));
                pklChart.data.datasets[1].data = filteredData.map(item => Number(item.total) - Math.floor(Number(item.total) * 0.4));
                pklChart.update();
            };

            yearFilter.addEventListener('change', (e) => {
                updateChartData(e.target.value);
            });

            // Trigger initial filter based on default selected option
            updateChartData(yearFilter.value);
        } else if (ctx) {
            // No data placeholder
            ctx.parentNode.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted" style="font-family: Outfit, sans-serif;">Data tahunan belum tersedia</div>';
        }
    });
</script>

@endsection
