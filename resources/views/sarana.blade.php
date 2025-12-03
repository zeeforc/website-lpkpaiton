@extends('layout')
@section('title', 'Sarana')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/sarana.css') }}" />
@endpush
@section('content')

<main>
    <section class="hero-sarana p-3">
        <div class="hero-overlay-top"></div>
        <div class="container hero-inner">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title text-light">
                        Sarana &<br />
                        Prasarana
                    </h1>
                    <p class="text-light fs-6 mb-0" style="max-width: 420px">
                        Rangkaian kurikulum praktik dan teori yang dirancang bersama
                        mitra industri agar peserta siap terjun ke dunia kerja di bidang
                        mekanik dan listrik.
                    </p>
                    <a href="#sarana">
                        <button class="btn-cta my-4">Selengkapnya</button>
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="hero-illustration-wrap">
                        <div class="hero-illustration-card">
                            <img src="assets/icon/sarana-icon.webp" alt="Ilustrasi mekanik" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-detail section-sarana" id="sarana">
        <div class="container">
            <h6 class="section-subtitle text-secondary">Detail</h6>
            <h2 class="section-title text-dark">Sarana</h2>

            <p class="section-body text-dark">
                {{ $saranaDesk }}
            </p>
        </div>
    </section>

    <section class="section-detail section-prasarana">
        <div class="container">
            <div class="row align-items-center gy-4">
                @php
                $firstSlide = $prasaranaData->first();
                @endphp
                <div class="col-lg-5">
                    <h6 class="section-subtitle text-dark">Detail</h6>
                    <h2 class="section-title text-dark" id="prasaranaHeading">
                        Prasarana
                    </h2>

                    <p class="section-body text-dark" id="prasaranaDescription">
                        {{ $firstSlide['description'] ?? 'Belum ada data prasarana' }}
                    </p>

                    <div class="location-block">
                        <div class="location-title text-dark">LPK Paiton Selaras</div>
                        <div class="location-text text-dark">
                            Jl Brigaan, Dusun Pesisir, Sumberanyar,<br />
                            Kec Paiton, Kabupaten Probolinggo
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-slider">
                        <div class="glass-card">
                            <img id="prasaranaImage" src="{{ $firstSlide['image'] ?? asset('assets/placeholder.jpg') }}"
                                alt="{{ $firstSlide['title'] ?? 'Prasarana' }}">
                            <div class="glass-label" id="prasaranaTitle">
                                {{ $firstSlide['title'] ?? 'Prasarana' }}
                            </div>
                        </div>

                        <button class="slider-btn prev" id="btnPrev" aria-label="Sebelumnya">
                            ‹
                        </button>
                        <button class="slider-btn next" id="btnNext" aria-label="Berikutnya">
                            ›
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const prasaranaData = @json($prasaranaData);

    let currentIndex = 0;

    const imgEl  = document.getElementById("prasaranaImage");
    const titleEl = document.getElementById("prasaranaTitle");
    const descEl  = document.getElementById("prasaranaDescription");
    const btnPrev = document.getElementById("btnPrev");
    const btnNext = document.getElementById("btnNext");

    function renderPrasarana(index) {
        if (!prasaranaData.length) {
            return;
        }

        const item = prasaranaData[index];

        imgEl.classList.add("fade-out");
        titleEl.classList.add("fade-out");
        descEl.classList.add("fade-out");

        setTimeout(() => {
            imgEl.src = item.image;
            imgEl.alt = item.title;
            titleEl.textContent = item.title;
            descEl.textContent = item.description;

            imgEl.classList.remove("fade-out");
            titleEl.classList.remove("fade-out");
            descEl.classList.remove("fade-out");
        }, 150);
    }

    btnNext.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % prasaranaData.length;
        renderPrasarana(currentIndex);
    });

    btnPrev.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + prasaranaData.length) % prasaranaData.length;
        renderPrasarana(currentIndex);
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowRight") {
            btnNext.click();
        } else if (e.key === "ArrowLeft") {
            btnPrev.click();
        }
    });

    const style = document.createElement("style");
    style.innerHTML = `
      .fade-out {
        opacity: 0;
        transition: opacity 0.15s ease;
      }
      #prasaranaImage,
      #prasaranaTitle,
      #prasaranaDescription {
        transition: opacity 0.15s ease;
      }
    `;
    document.head.appendChild(style);

    if (prasaranaData.length) {
        renderPrasarana(currentIndex);
    }
</script>
@endsection
