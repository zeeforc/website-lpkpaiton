@extends('layout')
@section('title', $berita->berita_utama_title)
@push('styles')
<link rel="stylesheet" href="{{ asset('style/detail.css') }}" />
@endpush
@section('content')

<main class="news-wrapper">
    <div class="container mt-5">
        {{-- Tombol Kembali --}}
        <a href="{{ route('berita.index') }}" class="back-link">
            <i data-feather="arrow-left" class="back-icon"></i>
            <span>Kembali ke Berita</span>
        </a>

        {{-- Hero Section --}}
        <div class="row news-hero-row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="news-media-wrap">
                    <div class="news-media-bg">
                        <div class="news-media-card">
                            <img src="{{ $berita->image_url }}" alt="{{ $berita->berita_utama_title }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="news-hero-text">
                    <div class="news-meta d-flex align-items-center gap-3 mb-3">
                        <span class="news-tagline">LPKPS News</span>
                        <span class="meta-dot">•</span>
                        <span class="news-date">
                            <i data-feather="calendar" class="meta-icon"></i>
                            {{ \Carbon\Carbon::parse($berita->tgl_berita)->format('d F Y') }}
                        </span>
                    </div>
                    <h1 class="news-title-main">{{ $berita->berita_utama_title }}</h1>
                    <div class="title-divider"></div>
                </div>
            </div>
        </div>

        {{-- Konten Berita (Dibatasi lebarnya agar enak dibaca) --}}
        <div class="news-content-container">

            <div class="news-body">
                {!! $berita->berita_utama_desk !!}
            </div>

            {{-- Navigasi Berita (Pagination) --}}
            <div class="news-pagination mt-5 pt-4 border-top">
                <div class="news-pagination-label">Navigasi Berita</div>
                <div class="pagination-dots">

                    {{-- Sebelumnya --}}
                    @if($prevBerita)
                    <a href="{{ route('berita.show', ['beritaUtama' => $prevBerita->slug]) }}" class="page-nav-btn"
                        title="Berita Sebelumnya">
                        <i data-feather="chevron-left"></i>
                    </a>
                    @else
                    <button class="page-nav-btn disabled" type="button" aria-disabled="true">
                        <i data-feather="chevron-left"></i>
                    </button>
                    @endif

                    {{-- Daftar Berita --}}
                    <a href="{{ route('berita.index') }}" class="page-nav-btn active" title="Daftar Berita">
                        <i data-feather="grid"></i>
                    </a>

                    {{-- Berikutnya --}}
                    @if($nextBerita)
                    <a href="{{ route('berita.show', ['beritaUtama' => $nextBerita->slug]) }}" class="page-nav-btn"
                        title="Berita Selanjutnya">
                        <i data-feather="chevron-right"></i>
                    </a>
                    @else
                    <button class="page-nav-btn disabled" type="button" aria-disabled="true">
                        <i data-feather="chevron-right"></i>
                    </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</main>

@endsection