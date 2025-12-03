@extends('layout')
@section('title', 'Syarat')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/syarat.css') }}" />
@endpush
@section('content')

<section class="hero-gallery">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-8">
                <h1 class="hero-title mb-4 text-light">
                    Panduan
                    <br />
                    Mendaftar PKL <br />
                    di LPK Paiton Selaras
                </h1>
                <p class="hero-desc text-light">
                    Berikut adalah tahapan tahapan pendaftaran magang di Kementerian
                    Keuangan. Berikut adalah tahapan tahapan pendaftaran magang di
                    Kementerian Keuangan.
                </p>
                <a href="#steps">
                    <button class="btn-orange">Selengkapnya</button>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="steps-section" id="steps">
    <div class="container">
        <div class="row align-items-start gy-5 d-flex flex-row">
            <!-- left text -->
            <div class="col-lg-6">
                <p class="steps-subtitle">Tahapan</p>
                <h2 class="steps-title">Pendaftaran</h2>
                <p class="steps-intro">
                    Berikut adalah tahapan tahapan pendaftaran magang di Kementerian
                    Keuangan.
                </p>

                <div class="step-list">
                    <div class="step-item">
                        <div class="step-badge">1</div>
                        <div>
                            <p class="step-text-title">Registrasi</p>
                            <p class="step-text-body">
                                Registrasi untuk mengetahui kuota pendaftar apakah masih
                                ada.
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-badge">3</div>
                        <div>
                            <p class="step-text-title">Melengkapi Data</p>
                            <p class="step-text-body">
                                Isi dan lengkapi data profil mahasiswa dan periode magang.
                            </p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-badge">2</div>
                        <div>
                            <p class="step-text-title">Mengirimkan Berkas</p>
                            <p class="step-text-body">
                                Kirimkan berkas yang sudah di lenkapi ke pihak pengurus LPK Paiton Selaras
                            </p>
                        </div>
                    </div>
                </div>

                <!-- right illustration -->
            </div>
            <div class="col-lg-6">
                <div class="steps-illustration-wrap d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/icon/folder.webp') }}" alt="Folder ilustrasi" class="steps-folder-img" />

                    <div class="row-steps">
                        @if($dokumen)
                        <p class="steps-note">
                            Tekan tombol view untuk melihat panduan lengkap.
                        </p>
                        <a href="{{ $dokumen->file_url }}" class="steps-view-btn"
                            download="{{ $dokumen->dokumen_syarat_pkl_title }}.pdf">
                            View / Download Dokumen
                        </a>
                        @else
                        <p class="steps-note">
                            Dokumen syarat PKL belum tersedia.
                        </p>
                        <button class="steps-view-btn" disabled>
                            View / Download Dokumen
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
