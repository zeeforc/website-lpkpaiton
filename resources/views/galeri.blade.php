@extends('layout')
@section('title', 'Galeri')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/galeri.css') }}" />
@endpush
@section('content')
<main>
    <section class="hero-galeri">
        <div class="hero-overlay-top"></div>
        <div class="container hero-inner">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <h1 class="hero-title text-light">
                        Galeri LPK<br />
                        Paiton<br />
                        Selaras
                    </h1>
                    <a href="#gallery">
                        <button class="btn-cta my-4">Selengkapnya</button>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration-wrap">
                        <div class="hero-illustration-card">
                            <img src="assets/icon/icon-galeri.webp" alt="Ikon Galeri" class="hero-illustration-img" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gallery" id="gallery">
        <div class="container gallery-inner">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5">
                    <h6 class="section-subtitle text-dark">Dokumentasi</h6>
                    <h2 class="section-title text-dark">Kegiatan</h2>
                    <p class="section-body text-dark">
                        Seluruh kegiatan yang dilaksanakan kita dokumentasikan dan
                        ditampilkan di halaman ini karena kenangan yang disimpan dalam
                        sebuah foto akan selalu abadi.
                    </p>
                </div>

                @php
                $images = collect($galeries)
                ->flatMap(function ($galery) {
                return $galery->galery_image ?? []; // sudah array of string
                })
                ->values();
                @endphp

                <div class="col-lg-7">
                    <div class="glass-card">
                        <div class="gallery-stack" id="galleryStack">
                            @forelse ($images as $index => $path)
                            <img src="{{ asset('storage/' . $path) }}" alt="Kegiatan"
                                class="gallery-item" data-index="{{ $index }}" />
                            @empty
                            <p class="text-center">Belum ada data galeri.</p>
                            @endforelse
                        </div>

                        <button type="button" class="gallery-nav gallery-prev" id="galleryPrev" aria-label="Sebelumnya">
                            ‹
                        </button>

                        <button type="button" class="gallery-nav gallery-next" id="galleryNext" aria-label="Berikutnya">
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery-preview-overlay" id="previewOverlay">
            <img id="previewImage" alt="Preview kegiatan" />
        </div>
    </section>
</main>
@endsection
