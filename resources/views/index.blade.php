@extends('layout')
@section('title', 'Home')
@push('styles')
<link rel="stylesheet" href="{{ asset('style/index.css') }}">
@endpush
@section('content')

<!-- Hero -->
<section id="hero" class="hero-section">
    <div class="container p-2">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6">
                <p class="hero-badge text-dark mt-4">
                    Lembaga Pelatihan Kerja Paiton Selaras
                </p>
                <h1 class="hero-title hero-gradient-text mb-4" style="font-size: 3rem;">
                    <span>Selamat Datang di</span>
                    <strong>Lembaga Pelatihan Kerja</strong> <br>
                    <span>Paiton Selaras</span>
                </h1>
                <p class="fs-6 text-dark mb-4">
                    Kami hadir untuk menyiapkan tenaga kerja yang terampil dan siap
                    pakai melalui program pelatihan terstruktur di bidang mekanik dan
                listrik, sesuai kebutuhan industri di wilayah Paiton dan
                    sekitarnya.
                </p>

                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="#kontak" class="btn btn-gradient"> Hubungi Kami </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="{{ $home && $home->bg_image ? asset('storage/' . $home->bg_image) : asset('assets/mekanik-org.webp') }}"
                        alt="Pelatihan kerja Paiton Selaras" class="hero-image" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi dan Foto Kantor -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="glass-card h-100 visi-misi-card">
                    <div class="visi-tabs">
                        <span class="active" data-tab="visi">Visi</span>
                        <span data-tab="misi">Misi</span>
                    </div>
                    <h2 class="h4 mb-3 visi-title" data-visi-title="{{ $vimi->visi_title ?? 'Visi Kami' }}"
                        data-misi-title="{{ $vimi->misi_title ?? 'Misi Kami' }}">
                        {{ $vimi->visi_title ?? 'Visi Kami' }}
                    </h2>
                    <p class="small text-dark visi-text">
                        {!! $vimi->visi_text ?? '' !!}
                    </p>
                    <p class="small text-dark misi-text d-none">
                        {{ $vimi->misi_text ?? '' }}
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card h-100 p-0 overflow-hidden">
                    <!-- Ganti src sesuai foto kantor -->
                    <img src="{{asset('assets/map.webp')}}" alt="Kantor LPK Paiton Selaras" class="hero-image" />
                    <div class="p-3 text-center small text-dark">
                        Kantor Lembaga Pelatihan Kerja Paiton Selaras
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang -->
<section id="tentang" class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <h2 class="section-title">Tentang LPK Paiton Selaras</h2>
                <p class="section-subtitle small text-dark">
                    LPK Paiton Selaras didirikan untuk memenuhi kebutuhan tenaga kerja
                    kompeten di kawasan Paiton dan sekitarnya, serta memberikan
                    kesempatan pelatihan bagi masyarakat yang ingin meningkatkan
                    keterampilan.
                </p>
            </div>
            <div class="col-lg-8">
                <div class="glass-card">
                    <p class="small text-dark mb-4">
                        LPK Paiton Selaras menyelenggarakan pelatihan kerja di bidang
                        teknik mekanik dan listrik, dengan fokus pada praktik lapangan
                        dan pemahaman standar keselamatan kerja. Program pelatihan
                        dirancang bersama mitra industri sehingga materi selalu relevan
                        dengan kebutuhan nyata di lapangan.
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="glass-card">
                                    <div class="fs-3">⚙️</div>
                                    <div>
                                        <h3 class="h6 mb-1">Mekanik</h3>
                                        <p class="small text-dark mb-0">
                                            Kerja bangku, bubut dasar, dan pelatihan dasar
                                            pengelasan untuk mempersiapkan peserta menjadi teknisi
                                            mekanik yang terampil dan sigap menghadapi kebutuhan
                                            industri.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-3">

                                <div class="glass-card">
                                    <div class="fs-3">💡</div>
                                    <div>
                                        <h3 class="h6 mb-1">Listrik</h3>
                                        <p class="small text-dark mb-0">
                                            Dasar instalasi listrik, relay, panel, dan sistem
                                            kontrol sederhana hingga penerapan di lingkungan kerja
                                            nyata dengan pendampingan instruktur bersertifikat.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Struktur Team -->
<section id="struktur" class="section-padding">
    @php
    use Illuminate\Support\Facades\Storage;
    @endphp
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Struktur Team</h2>
            <p class="section-subtitle small mx-auto text-dark" style="max-width: 520px">
                Tim pengelola dan instruktur yang memiliki pengalaman di bidang
                industri dan pendidikan vokasi, siap mendampingi peserta selama
                proses pelatihan.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($teams as $team)
            <div class="col-6 col-md-3 col-lg-3">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="{{ $team->photo ? Storage::url($team->photo) : asset('assets/team-image/default.jpg') }}"
                            alt="{{ $team->name }}">
                    </div>
                    <div class="team-name text-dark">{{ $team->name }}</div>
                    <div class="team-role small text-dark">{{ $team->position }}</div>
                </div>
            </div>
            @empty
            <p class="text-center">Belum ada data team.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Alamat Kantor -->
<section id="alamat" class="section-padding">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <h2 class="section-title">Alamat Kantor</h2>
                <div class="map-card">
                    <p class="small text-dark mb-3">
                        LPK Paiton Selaras berlokasi di:
                    </p>
                    <p class="small mb-3">
                        Jl. Brigjend. Dusan Pesisir, Sumberanyar,<br />
                        Kec. Paiton, Kabupaten Probolinggo,<br />
                        Jawa Timur 67291, Indonesia.
                    </p>
                    <p class="small text-dark mb-0">
                        Untuk petunjuk arah yang lebih rinci, silakan tekan lingkarang
                        si samping maka akan diarahkan ke Google Maps dan ikuti rambu
                        menuju kawasan pelatihan Paiton.
                    </p>
                </div>
            </div>

            <div class="col-lg-7 align-self-center text-center">
                <p></p>
                <div class="map-illustration">
                    <div class="map-pin">
                        <a href="https://www.google.com/maps/search/?api=1&query=LPK+Paiton+Selaras" target="_blank"
                            rel="noopener noreferrer" class="d-block w-100 h-100" style="border-radius: 50%">
                            <div class="map-pin-inner"></div>
                        </a>
                    </div>
                </div>
                <p class="text-dark text-secondary">*Tekan lingkaran di atas untuk melihat titik lokasi di Google Maps
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Kontak -->
<section id="kontak" class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <h2 class="section-title">Kontak Kami</h2>
                <p class="section-subtitle small text-dark">
                    Hubungi kami untuk informasi jadwal pelatihan, persyaratan
                    pendaftaran, kerja sama industri, atau kunjungan studi ke
                    fasilitas pelatihan.
                </p>

                <div class="contact-card mb-4">
                    <form>
                        <div class="mb-3">
                            <label class="form-label small">Email</label>
                            <input type="email" class="form-control form-control-sm" placeholder="nama@email.com" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Pesan</label>
                            <textarea class="form-control form-control-sm" rows="4"
                                placeholder="Tuliskan pertanyaan atau kebutuhan pelatihan Anda"></textarea>
                        </div>
                        <button type="submit" class="btn btn-gradient btn-sm">
                            Kirim
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <h3 class="h6 mb-3">Informasi Kontak</h3>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-4">📞</div>
                        <div>
                            <div class="small text-uppercase text-dark">
                                Phone
                            </div>
                            <div class="small">+62 123 456 7890</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-4">📧</div>
                        <div>
                            <div class="small text-uppercase text-dark">
                                Email
                            </div>
                            <div class="small">lpkpaiton@gmail.com</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="fs-4">🌐</div>
                        <div>
                            <div class="small text-uppercase text-dark">
                                Social
                            </div>
                            <div class="d-flex gap-3 small">
                                <a href="#" class="link-dark text-decoration-none">Facebook</a>
                                <a href="#" class="link-dark text-decoration-none">Instagram</a>
                            </div>
                        </div>
                    </div>

                    <p class="small text-dark mb-0">
                        Staf administrasi kami siap membantu pada hari kerja dengan jam
                        layanan pukul 08.00 sampai 16.00 waktu setempat.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
