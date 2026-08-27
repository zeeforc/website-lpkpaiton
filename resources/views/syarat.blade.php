@extends('layout')
@section('title', 'Panduan Pendaftaran PKL')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/syarat.css') }}?v={{ time() }}" />
@endpush
@section('content')

<section class="hero-gallery">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <h1 class="hero-title mb-4 text-light">
                    Panduan
                    <br />
                    Mendaftar PKL <br />
                    di LPK Paiton Selaras
                </h1>
                <p class="hero-desc text-light">
                    Pelajari tahapan dan syarat pendaftaran magang (PKL) di Lembaga Pelatihan Kerja Paiton Selaras dengan mudah melalui Portal Pendaftaran kami.
                </p>
                <a href="#steps">
                    <button class="btn-orange">Selengkapnya</button>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="hero-illustration-wrap">
                    <div class="hero-illustration-card">
                        <img src="{{ asset('assets/icon/alur.webp') }}" alt="Ilustrasi mekanik" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="steps-section" id="steps">
    <div class="container">
        <div class="steps-card">
            {{-- Decorative accent bars top-left --}}
            <div class="accent-bars">
                <span class="accent-bar accent-bar-1"></span>
                <span class="accent-bar accent-bar-2"></span>
            </div>

            <div class="row">
                {{-- LEFT: Content --}}
                <div class="col-lg-6 steps-left">
                    {{-- Subtitle pill --}}
                    <span class="steps-pill">Tahapan</span>

                    <h2 class="steps-title">Pendaftaran</h2>
                    <p class="steps-intro">
                        Berikut adalah tahapan pendaftaran magang di<br>Lembaga Pelatihan Kerja Paiton Selaras.
                    </p>

                    {{-- Step List --}}
                    <div class="step-list">

                        {{-- Step 01 --}}
                        <div class="step-item step-active">
                            <div class="step-left-col">
                                <div class="step-badge">01</div>
                                <div class="step-connector"></div>
                            </div>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                            <div class="step-content">
                                <p class="step-text-title">Registrasi</p>
                                <p class="step-text-body">
                                    Registrasi untuk mengajukan permohonan Praktik Kerja Lapangan (PKL) melalui link https://lpkpaiton.site/pendaftaran atau tekan tombol daftar sekarang di samping.
                                </p>
                            </div>
                        </div>

                        {{-- Step 02 --}}
                        <div class="step-item">
                            <div class="step-left-col">
                                <div class="step-badge">02</div>
                                <div class="step-connector"></div>
                            </div>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                            </div>
                            <div class="step-content">
                                <p class="step-text-title">Melengkapi Data</p>
                                <p class="step-text-body">
                                    Isi dan lengkapi data profil siswa/mahasiswa, asal instansi, dan periode magang. Silakan tunggu proses peninjauan selesai. Anda dapat melihat status proses melalui halaman cek status.
                                </p>
                            </div>
                        </div>

                        {{-- Step 03 --}}
                        <div class="step-item">
                            <div class="step-left-col">
                                <div class="step-badge">03</div>
                            </div>
                            <div class="step-icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            </div>
                            <div class="step-content">
                                <p class="step-text-title">Mengirimkan Berkas</p>
                                <p class="step-text-body">
                                    Jika sudah di setujui maka akan mendapatkan email pemberitahuan. Selanjutnya anda bisa kirimkan berkas pengantar dan proposal sesuai ketentuan di halaman pendaftaran LPK Paiton Selaras.
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- Info Box --}}
                    <div class="step-info-box">
                        <div class="step-info-icon">
                            <i data-feather="alert-circle"></i>
                        </div>
                        <p class="step-info-text">
                            Pastikan seluruh data dan berkas yang kamu kirimkan sudah sesuai dengan ketentuan yang berlaku.
                        </p>
                    </div>

                </div>

                {{-- RIGHT: Illustration --}}
                <div class="col-lg-6 steps-right">
                    {{-- Dot pattern --}}
                    <div class="dot-pattern"></div>

                    <div class="steps-illustration-wrap">
                        <img src="{{ asset('assets/icon/daftar.webp') }}" alt="Folder ilustrasi" class="steps-folder-img" />
                        <div class="btn-daftar">
                            <a href="/pendaftaran" class="steps-cta-btn">
                                Daftar Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
