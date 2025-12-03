@extends('layout')
@section('title', 'Pelatihan')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/pelatihan.css') }}" />
@endpush
@section('content')

<section class="hero-section">
    <div class="container hero-inner">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <h1 class="hero-title mb-4 text-light">
                    <span>Pelatihan</span>
                    <span>Berbasis</span>
                    <span>kompetensi</span>
                </h1>
                <p class="text-light text-secondary fs-6 mb-0" style="max-width: 420px">
                    Rangkaian kurikulum praktik dan teori yang dirancang bersama mitra
                    industri agar peserta siap terjun ke dunia kerja di bidang mekanik
                    dan listrik.
                </p>
                <a href="#pelatihan">
                    <button class="btn-orange text-light">Selengkapnya</button>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="hero-illustration-wrap">
                    <div class="hero-illustration-card">
                        <!-- ganti dengan ilustrasi sesuai desain -->
                        <img src="assets/icon/pelatihan-maskot.webp" alt="Ilustrasi mekanik" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-wrapper curriculum-bg-top" id="pelatihan">
    <div class="container curriculum-section">

        @foreach ($pelatihans as $pelatihan)
        <div class="row align-items-center gy-4 mb-5">
            <div class="col-lg-6">
                <div class="curriculum-card bg-transparent border-0">
                    <div class="curriculum-subtitle text-dark">Pelatihan</div>

                    <div class="curriculum-title text-dark">
                        {{ $pelatihan->nama_pelatihan }}
                    </div>

                    <p class="curriculum-desc mb-4 text-dark">
                        {{ $pelatihan->deskripsi_pelatihan }}
                    </p>

                    <div class="curriculum-meta">
                        <div class="meta-icon text-warning">⚙</div>
                        <div>
                            <div class="meta-label text-dark">
                                {{ $pelatihan->nama_pelatihan }}
                            </div>

                            <div class="meta-text text-dark">
                                {{ $pelatihan->desk_singkat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="curriculum-image-card overlay-orange text-light">
                    <img src="{{ asset('storage/' . $pelatihan->image_pelatihan) }}"
                        alt="{{ $pelatihan->nama_pelatihan }}" />
                    <div class="curriculum-image-label">
                        {{ $pelatihan->nama_pelatihan }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>

@endsection