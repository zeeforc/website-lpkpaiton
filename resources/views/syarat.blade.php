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
                    Berikut adalah tahapan tahapan pendaftaran magang di Lembaga Pelatihan Kerja Paiton Selaras.
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
                    Berikut adalah tahapan tahapan pendaftaran magang di Lembaga Pelatihan Kerja Paiton Selaras.
                </p>

                <div class="step-list">
                    <div class="step-item">
                        <div class="step-badge">1</div>
                        <div>
                            <p class="step-text-title">Registrasi</p>
                            <p class="step-text-body">
                                Registrasi untuk mengajukan permohonan magang PKL ke pihak pengurus LPK Paiton Selaras.
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-badge">2</div>
                        <div>
                            <p class="step-text-title">Melengkapi Data</p>
                            <p class="step-text-body">
                                Isi dan lengkapi data profil siswa/mahasiswa, asal instansi, dan periode magang.
                            </p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-badge">3</div>
                        <div>
                            <p class="step-text-title">Mengirimkan Berkas</p>
                            <p class="step-text-body">
                                Kirimkan berkas pengantar dan proposal sesuai ketentuan LPK Paiton Selaras.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- right illustration -->
            </div>
            <div class="col-lg-6">
                <div class="steps-illustration-wrap d-flex flex-column align-items-center">
                    <img src="{{ asset('assets/icon/folder.webp') }}" alt="Folder ilustrasi" class="steps-folder-img" />

                    <div class="row-steps d-flex flex-column align-items-center mt-4">
                        @if($dokumen)
                        <p class="steps-note mb-2 text-center">
                            Tekan tombol view untuk melihat panduan lengkap.
                        </p>
                        <a href="{{ $dokumen->file_url }}" class="steps-view-btn mb-3"
                            download="{{ $dokumen->dokumen_syarat_pkl_title }}.pdf">
                            View / Download Dokumen
                        </a>
                        @else
                        <p class="steps-note mb-2 text-center">
                            Dokumen syarat PKL belum tersedia.
                        </p>
                        @endif
                        
                        <a href="{{ route('application.create') }}" class="steps-view-btn" style="background-color: #0b5ed7;">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
