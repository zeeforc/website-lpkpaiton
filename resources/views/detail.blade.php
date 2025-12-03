@extends('layout')
@section('title', $berita->berita_utama_title)
@push('styles')
<link rel="stylesheet" href="{{ asset('style/detail.css') }}" />
@endpush
@section('content')

<main class="news-wrapper">
    <div class="container mt-5">
        <a href="{{ route('berita.index') }}" class="back-link">
            <span>&lt;</span>
            <span>Kembali</span>
        </a>

        <div class="row news-hero-row">
            <div class="col-lg-6">
                <div class="news-media-wrap align-items-center rounded">
                    <div class="news-media-bg">
                        <div class="news-media-card">
                            <img src="{{ $berita->image_url }}" alt="{{ $berita->berita_utama_title }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="news-hero-text">
                    <div class="news-tagline">LPKPS News</div>
                    <h1 class="news-title-main">{{ $berita->berita_utama_title }}</h1>
                    <p class="mt-3">
                        {{ \Carbon\Carbon::parse($berita->tgl_berita)->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="news-body">
            {!! nl2br(e($berita->berita_utama_desk)) !!}
        </div>

        {{-- paginate berita --}}
        <div class="news-pagination">
            <div class="news-pagination-label">Halaman</div>
            <div class="pagination-dots">

                {{-- to berita sebelumnya --}}
                @if($prevBerita)
                <a href="{{ route('berita.show', ['beritaUtama' => $prevBerita->slug]) }}" class="page-dot-link">
                    <button class="page-dot" type="button" aria-label="Berita sebelumnya">
                        1
                    </button>
                </a>
                @else
                {{-- tidak ada sebelumnya, disabled --}}
                <button class="page-dot disabled" type="button" aria-disabled="true">
                    <i data-feather="arrow-left"></i>
                </button>
                @endif

                {{-- Dot 2: kembali ke daftar berita --}}
                <a href="{{ route('berita.index') }}" class="page-dot-link">
                    <button class="page-dot active" type="button" aria-label="Daftar berita">
                        <i data-feather="arrow-up-circle"></i>
                    </button>
                </a>

                {{-- Dot 3: berita berikutnya --}}
                @if($nextBerita)
                <a href="{{ route('berita.show', ['beritaUtama' => $nextBerita->slug]) }}" class="page-dot-link">
                    <button class="page-dot" type="button" aria-label="Berita berikutnya">
                        <i data-feather="arrow-right"></i>
                    </button>
                </a>
                @else
                {{-- tidak ada berikutnya, disabled --}}
                <button class="page-dot disabled" type="button" aria-disabled="true">
                    3
                </button>
                @endif

            </div>
        </div>
    </div>
</main>

@endsection
