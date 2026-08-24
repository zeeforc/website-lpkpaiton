@extends('layout')
@section('title', 'Berita')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/berita.css') }}" />
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
                            <div class="hero-slide">
                                <img src="{{ asset('assets/slider/slide2.webp') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="marquee">
                <div class="marquee-content">
                    <span id="runningDate"></span> •
                    Berita LPK Paiton Selaras •
                    Pendaftaran Gelombang Baru Dibuka •
                    Program Pelatihan Terbaru •
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
    });
</script>

@endsection
