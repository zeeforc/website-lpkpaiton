@extends('layout')

@section('title', 'Upload Dokumen Prasyarat')

@push('styles')
<style>
    .form-container {
        max-width: 600px;
        margin: 60px auto;
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .btn-submit {
        background-color: #2563eb;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 500;
        width: 100%;
    }
    .btn-submit:hover {
        background-color: #1d4ed8;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="form-container">
        <div class="form-header">
            <h2 style="color: #0b5ed7; font-weight: 700;">UPLOAD BERKAS KELENGKAPAN</h2>
            <p class="text-muted">Halo, {{ $application->nama_lengkap }}. Permohonan awal Anda telah disetujui (Lolos Tahap Pengajuan). Sesuai alur pendaftaran, silakan lengkapi berkas persyaratan pendaftaran berikut.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $contohPortofolioSetting = \App\Models\Setting::where('key', 'contoh_portofolio')->first();
            $contohUrl = $contohPortofolioSetting && $contohPortofolioSetting->value ? Storage::disk('public')->url($contohPortofolioSetting->value) : asset('contoh_portofolio.pdf');
        @endphp

        <form action="{{ URL::signedRoute('application.upload.store', ['application' => $application->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4 p-4 border rounded bg-light">
                <h5 class="mb-4 text-primary">Berkas Kelengkapan</h5>
                
                <div class="mb-4">
                    <label for="dokumen_ktp" class="form-label fw-bold">1. Fotokopi KTP / Kartu Pelajar <span class="text-danger">*</span></label>
                    <div class="form-text text-muted mb-2">Upload scan/foto KTP atau Kartu Pelajar yang masih berlaku (PDF/JPG/PNG, Maks 5 MB).</div>
                    <input class="form-control" type="file" id="dokumen_ktp" name="dokumen_ktp" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="mb-4">
                    <label for="dokumen_foto" class="form-label fw-bold">2. Pas Foto 4x6 Latar Belakang Merah <span class="text-danger">*</span></label>
                    <div class="form-text text-muted mb-2">Upload pas foto resmi terbaru ukuran 4x6 dengan latar belakang merah (JPG/PNG, Maks 5 MB).</div>
                    <input class="form-control" type="file" id="dokumen_foto" name="dokumen_foto" accept=".jpg,.jpeg,.png" required>
                </div>

                <div class="mb-4">
                    <label for="dokumen_skck" class="form-label fw-bold">3. Surat Kelakuan Baik (Dari Sekolah/Kampus) <span class="text-danger">*</span></label>
                    <div class="form-text text-muted mb-2">Upload surat kelakuan baik asli dari instansi pendidikan Anda (PDF/JPG/PNG, Maks 5 MB).</div>
                    <input class="form-control" type="file" id="dokumen_skck" name="dokumen_skck" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="mb-4">
                    <label for="dokumen_sehat" class="form-label fw-bold">4. Surat Keterangan Sehat <span class="text-danger">*</span></label>
                    <div class="form-text text-muted mb-2">Upload surat keterangan sehat dari dokter/puskesmas/klinik (PDF/JPG/PNG, Maks 5 MB).</div>
                    <input class="form-control" type="file" id="dokumen_sehat" name="dokumen_sehat" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>

                <div class="mb-4">
                    <label for="dokumen_portofolio" class="form-label fw-bold">5. Portofolio <span class="text-danger">*</span></label>
                    <div class="form-text text-muted mb-2">Upload portofolio atau hasil karya/project yang pernah Anda buat (PDF/JPG/PNG, Maks 10 MB).
                        <br>
                        <a href="{{ $contohUrl }}" target="_blank" class="text-decoration-none" style="color: #2563eb; font-weight: 500;">
                            Download Contoh Format Portofolio
                        </a>
                    </div>
                    <input class="form-control" type="file" id="dokumen_portofolio" name="dokumen_portofolio" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit">Upload Dokumen</button>
        </form>
    </div>
</div>
@endsection
