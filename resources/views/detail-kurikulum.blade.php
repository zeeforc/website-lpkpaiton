@extends('layout')
@section('title', $kurikulum->kurikulum_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('style/detail-kurikulum.css') }}" />
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('matrix-container');
    const loader    = document.getElementById('matrix-loader');

    if (!container || !container.dataset.url) {
        return;
    }

    fetch(container.dataset.url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(response => response.text())
        .then(html => {
            if (loader) {
                loader.style.display = 'none';
            }
            container.innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            if (loader) {
                loader.textContent = 'Gagal memuat matrik kurikulum.';
            }
        });
});
</script>
@endpush

@section('content')
<main class="curriculum-detail-wrapper">
    <div class="container mt-5 p-5">

        <a href="{{ route('kurikulum.index') }}" class="back-link">
            <span>&lt;</span>
            <span>Kembali</span>
        </a>

        <div class="row news-hero-row">
            <div class="col-lg-6">
                <div class="news-media-wrap">
                    <div class="news-media-bg">
                        <figure class="news-media-card">
                            <img src="{{ asset('storage/' . $kurikulum->image_kurikulum) }}"
                                alt="{{ $kurikulum->kurikulum_title }}">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="news-hero-text">
                    <div class="news-tagline">Kurikulum LPK Paiton Selaras</div>
                    <h1 class="news-title-main">
                        {{ $kurikulum->kurikulum_title }}
                    </h1>
                    <p class="mt-3">
                        {!! nl2br(e($kurikulum->desk_kurikulum)) !!}
                    </p>
                </div>
            </div>
        </div>

        <section class="news-media-card-2 mt-5">
            <h4 class="pt-2">
                Matrik Kurikulum {{ $kurikulum->kurikulum_title }}
            </h4>

            <div class="news-body">
                @if(trim($kurikulum->matrix_html ?? '') !== '')
                <div class="matrix-scroll-wrapper mt-3">
                    {!! $kurikulum->matrix_html !!}
                </div>
                @else
                {!! nl2br(e($kurikulum->desk_kurikulum)) !!}
                @endif
            </div>
        </section>

        {{-- navigasi kurikulum sebelumnya / berikutnya --}}
        <div class="news-pagination">
            <div class="news-pagination-label">Kurikulum lain</div>
            <div class="pagination-dots">
                @if($prevKurikulum)
                <a href="{{ route('kurikulum.show', $prevKurikulum->slug) }}" class="page-dot-link">
                    <button class="page-dot" type="button" aria-label="Kurikulum sebelumnya">
                        ‹
                    </button>
                </a>
                @else
                <button class="page-dot disabled" type="button" aria-disabled="true">
                    ‹
                </button>
                @endif

                <a href="{{ route('kurikulum.index') }}" class="page-dot-link">
                    <button class="page-dot active" type="button" aria-label="Daftar kurikulum">
                        ⌂
                    </button>
                </a>

                @if($nextKurikulum)
                <a href="{{ route('kurikulum.show', $nextKurikulum->slug) }}" class="page-dot-link">
                    <button class="page-dot" type="button" aria-label="Kurikulum berikutnya">
                        ›
                    </button>
                </a>
                @else
                <button class="page-dot disabled" type="button" aria-disabled="true">
                    ›
                </button>
                @endif

            </div>
        </div>
    </div>
</main>
@endsection
