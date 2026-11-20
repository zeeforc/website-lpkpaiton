@extends('layout')
@section('title', 'Kurikulum')
@php
use Illuminate\Support\Str;
@endphp
@push('styles')
<link rel="stylesheet" href="{{ asset('style/kurikulum.css') }}" />
@endpush
@section('content')


<section class="hero-section">
    <div class="container hero-inner">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title mb-4 text-light">
                    <span>Kurikulum</span>
                    <span>Yang Kita</span>
                    <strong>Implementasikan</strong>
                </h1>
                <p class="text-light fs-6 mb-0" style="max-width: 420px">
                    Rangkaian kurikulum praktik dan teori yang dirancang bersama mitra
                    industri agar peserta siap terjun ke dunia kerja di bidang mekanik
                    dan listrik.
                </p>
                <a href="#curiculum">
                    <button class="btn btn-orange mt-3 text-light">Selengkapnya</button>
                </a>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-illustration-wrap">
                    <div class="hero-illustration-card">
                        <!-- ganti dengan ilustrasi sesuai desain -->
                        <img src="{{ asset('assets/icon/maskot.webp') }}?v={{ time() }}" alt="Ilustrasi mekanik" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@foreach ($kurikulums as $kurikulum)
<section class="section-wrapper {{ $loop->first ? 'curriculum-bg-top' : '' }}" id="curiculum">
    <div class="container curriculum-section">
        <div class="row align-items-center gy-4 {{ $loop->iteration % 2 == 0 ? 'flex-lg-row-reverse' : '' }}">

            <div class="col-lg-6" data-aos="{{ $loop->iteration % 2 == 0 ? 'fade-left' : 'fade-right' }}">
                <div class="curriculum-card bg-transparent border-0">
                    <div class="curriculum-subtitle">Kurikulum {{ $loop->iteration }}</div>
                    <div class="curriculum-title text-white">
                        {{ $kurikulum->kurikulum_title }}
                    </div>

                    <p class="curriculum-desc mb-4">
                        {{ Str::words($kurikulum->desk_kurikulum, 39, '...') }}
                    </p>

                    <a href="{{ route('kurikulum.show', $kurikulum) }}" class="btn btn-orange text-light mb-3">
                        Selengkapnya
                    </a>

                    <div class="curriculum-meta">
                        <div class="meta-icon {{ $loop->iteration % 2 == 0 ? 'text-primary' : 'text-warning' }}">
                            {{ $loop->iteration % 2 == 0 ? '⚡' : '⚙' }}
                        </div>
                        <div>
                            <div class="meta-label">
                                {{ $kurikulum->kurikulum_title }}
                            </div>
                            <div class="meta-text">
                                {{ $kurikulum->desk_singkat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="{{ $loop->iteration % 2 == 0 ? 'fade-right' : 'fade-left' }}">
                <div
                    class="curriculum-image-card-2 {{ $loop->iteration % 2 == 0 ? 'overlay-blue' : 'overlay-orange' }}">
                    <img src="{{ $kurikulum->image_url }}" alt="{{ $kurikulum->kurikulum_title }}">
                    <div class="curriculum-image-label">
                        {{ $kurikulum->kurikulum_title }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endforeach


@endsection
