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
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="hero-title text-light">
                        Galeri LPK<br />
                        Paiton<br />
                        Selaras
                    </h1>
                    <a href="#gallery">
                        <button class="btn-cta my-4">Selengkapnya</button>
                    </a>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="hero-illustration-wrap">
                        <div class="hero-illustration-card">
                            <img src="{{ asset('assets/icon/icon-galeri.webp') }}" alt="Ikon Galeri"
                                class="hero-illustration-img" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gallery" id="gallery">
        <div class="container gallery-inner">
            <div class="row align-items-center gy-4">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="mb-4">
                        <p class="text-uppercase fw-bold text-muted mb-3" style="letter-spacing: 2px; font-size: 0.75rem;">Dokumentasi</p>
                        <h2 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -1px;">Kegiatan <span style="color: #fd7a2a;">LPK</span></h2>
                    </div>
                    <p class="section-body text-dark">
                        Seluruh kegiatan yang dilaksanakan kita dokumentasikan dan
                        ditampilkan di halaman ini karena kenangan yang disimpan dalam
                        sebuah foto akan selalu abadi.
                    </p>
                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
                    <div class="glass-card">
                        <div class="gallery-stack" id="galleryStack">
                            {{-- View menjadi sangat bersih, tidak ada lagi proses ekstraksi (flatMap) PHP di sini --}}
                            @forelse ($images as $index => $path)
                            {{-- Memastikan variable $path bukan null/string kosong sebelum merender img --}}
                            @if(!empty($path))
                            <img src="{{ asset('storage/' . $path) }}" alt="Kegiatan LPK Paiton" class="gallery-item"
                                data-index="{{ $index }}" loading="lazy" />
                            @endif
                            @empty
                            <div class="text-center w-100 py-5">
                                <p class="text-muted">Belum ada dokumentasi galeri yang diunggah.</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Tampilkan tombol navigasi hanya jika gambar lebih dari 1 --}}
                        @if(count($images) > 1)
                        <button type="button" class="gallery-nav gallery-prev" id="galleryPrev" aria-label="Sebelumnya">
                            ‹
                        </button>

                        <button type="button" class="gallery-nav gallery-next" id="galleryNext" aria-label="Berikutnya">
                            ›
                        </button>
                        @endif
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