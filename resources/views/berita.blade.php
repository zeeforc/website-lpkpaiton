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
                <div class="col-lg-6">
                    <h1 class="hero-title text-light">
                        Berita LPK<br />
                        Paiton<br />
                        Selaras
                    </h1>
                    <a href="#news">
                        <button class="btn-cta my-4 text-light">Selengkapnya</button>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration-wrap">
                        <div class="hero-illustration-card">
                            <img src="assets/icon/icon-berita.webp" alt="Megaphone" class="hero-illustration-img" />
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

@endsection
