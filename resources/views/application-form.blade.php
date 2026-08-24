@extends('layout')

@section('title', 'Form Pendaftaran Praktik Kerja Lapangan')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .form-header h2 {
        font-weight: 600;
        color: #2c3e50;
    }
    .form-label {
        font-weight: 500;
        color: #34495e;
    }
    .btn-submit {
        background-color: #2563eb;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 500;
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
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #dee2e6;">
                <h2 style="color: #0b5ed7; margin:0;">INTERNSHIP PROGRAM</h2>
                <p class="mb-0 text-muted">Lembaga Pelatihan Kerja Paiton Selaras</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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

        <form action="{{ route('application.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nama_lengkap" class="form-label">NAMA LENGKAP <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required value="{{ old('nama_lengkap') }}">
            </div>

            <div class="mb-4">
                <label for="instansi" class="form-label">INSTANSI / PERGURUAN TINGGI <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="instansi" name="instansi" required value="{{ old('instansi') }}">
            </div>

            <div class="mb-4">
                <label class="form-label">TINGKAT PENDIDIKAN <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tingkat_pendidikan" id="tingkat_pendidikan1" value="Mahasiswa" required {{ old('tingkat_pendidikan') == 'Mahasiswa' ? 'checked' : '' }}>
                    <label class="form-check-label" for="tingkat_pendidikan1">MAHASISWA (PERGURUAN TINGGI)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="tingkat_pendidikan" id="tingkat_pendidikan2" value="Siswa SMK/SMA" {{ old('tingkat_pendidikan') == 'Siswa SMK/SMA' ? 'checked' : '' }}>
                    <label class="form-check-label" for="tingkat_pendidikan2">SISWA SMK/SMA</label>
                </div>
            </div>

            <div class="mb-4">
                <label for="jurusan" class="form-label">JURUSAN/BIDANG STUDI <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jurusan" name="jurusan" required value="{{ old('jurusan') }}">
            </div>

            <div class="mb-4">
                <label for="no_hp" class="form-label">NO HANDPHONE <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required value="{{ old('no_hp') }}">
            </div>

            <div class="mb-4">
                <label class="form-label">PENGAJUAN <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan1" value="Praktek Kerja Lapangan" required {{ old('pengajuan') == 'Praktek Kerja Lapangan' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pengajuan1">PRAKTEK KERJA LAPANGAN</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan2" value="Penelitian/Tugas Akhir" {{ old('pengajuan') == 'Penelitian/Tugas Akhir' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pengajuan2">PENELITIAN/TUGAS AKHIR</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan3" value="Industrial Visit" {{ old('pengajuan') == 'Industrial Visit' ? 'checked' : '' }}>
                    <label class="form-check-label" for="pengajuan3">INDUSTRIAL VISIT</label>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">PERIODE GELOMBANG <span class="text-danger">*</span></label>
                @php 
                    $currentMonth = (int) date('n');
                    $currentYear = (int) date('Y');
                    
                    $yearG1 = $currentMonth >= 1 ? $currentYear + 1 : $currentYear;
                    $yearG2 = $currentMonth >= 4 ? $currentYear + 1 : $currentYear;
                    $yearG3 = $currentMonth >= 7 ? $currentYear + 1 : $currentYear;
                    $yearG4 = $currentMonth >= 10 ? $currentYear + 1 : $currentYear;
                @endphp
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="periode_gelombang" id="gelombang1" value="GELOMBANG 1 : 1 JANUARI {{ $yearG1 }}" required {{ old('periode_gelombang') == 'GELOMBANG 1 : 1 JANUARI '.$yearG1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="gelombang1">GELOMBANG 1 : 1 JANUARI {{ $yearG1 }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="periode_gelombang" id="gelombang2" value="GELOMBANG 2 : 1 APRIL {{ $yearG2 }}" {{ old('periode_gelombang') == 'GELOMBANG 2 : 1 APRIL '.$yearG2 ? 'checked' : '' }}>
                    <label class="form-check-label" for="gelombang2">GELOMBANG 2 : 1 APRIL {{ $yearG2 }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="periode_gelombang" id="gelombang3" value="GELOMBANG 3 : 1 JULI {{ $yearG3 }}" {{ old('periode_gelombang') == 'GELOMBANG 3 : 1 JULI '.$yearG3 ? 'checked' : '' }}>
                    <label class="form-check-label" for="gelombang3">GELOMBANG 3 : 1 JULI {{ $yearG3 }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="periode_gelombang" id="gelombang4" value="GELOMBANG 4 : 1 OKTOBER {{ $yearG4 }}" {{ old('periode_gelombang') == 'GELOMBANG 4 : 1 OKTOBER '.$yearG4 ? 'checked' : '' }}>
                    <label class="form-check-label" for="gelombang4">GELOMBANG 4 : 1 OKTOBER {{ $yearG4 }}</label>
                </div>
            </div>

            <div class="mb-4">
                <label for="jumlah_peserta" class="form-label">JUMLAH PESERTA <span class="text-danger">*</span></label>
                <select class="form-select" id="jumlah_peserta" name="jumlah_peserta" required>
                    <option value="">Pilih Jumlah Peserta</option>
                    @for($i=1; $i<=10; $i++)
                        <option value="{{ $i }} ORANG" {{ old('jumlah_peserta') == $i.' ORANG' ? 'selected' : '' }}>{{ $i }} ORANG</option>
                    @endfor
                </select>
            </div>

            <div class="mb-4">
                <label for="lama_durasi_bulan" class="form-label">LAMA DURASI (BULAN) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="lama_durasi_bulan" name="lama_durasi_bulan" placeholder="Jawaban Anda" required value="{{ old('lama_durasi_bulan') }}">
            </div>

            <div class="mb-4">
                <label for="fokus_studi" class="form-label">RINGKASAN FOKUS STUDI YANG AKAN DILAKUKAN <span class="text-danger">*</span></label>
                <div class="form-text text-muted mb-2">Tuliskan secara singkat dan jelas fokus studi atau topik pembelajaran yang akan dilakukan selama pelaksanaan Praktik Kerja Lapangan di Lembaga Pelatihan Kerja Paiton Selaras.</div>
                <textarea class="form-control" id="fokus_studi" name="fokus_studi" rows="4" placeholder="Jawaban Anda" required>{{ old('fokus_studi') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="email_balasan" class="form-label">EMAIL SURAT BALASAN <span class="text-danger">*</span></label>
                <div class="form-text text-muted mb-2">Seluruh pendaftar akan melalui proses seleksi berdasarkan kesesuaian jurusan dengan bidang bisnis perusahaan... Tuliskan alamat email aktif yang akan digunakan untuk pengiriman surat balasan...</div>
                <input type="email" class="form-control" id="email_balasan" name="email_balasan" placeholder="Jawaban Anda" required value="{{ old('email_balasan') }}">
            </div>

            <div class="mb-4 p-4 border rounded bg-light">
                <label for="documents" class="form-label fw-bold">UNGGAH SURAT PENGANTAR RESMI & PROPOSAL PRAKTIK KERJA LAPANGAN ANDA <span class="text-danger">*</span></label>
                <div class="form-text text-muted mb-3">Sesuai Alur Pendaftaran, Anda wajib melampirkan Surat Pengantar dan Proposal. Maks 10 MB per file (PDF).</div>
                <input class="form-control" type="file" id="documents" name="documents[]" multiple accept=".pdf" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5">
                <a href="/" class="text-decoration-none" style="color: #5f6368; font-weight: 500;">Kembali</a>
                <button type="submit" class="btn btn-submit">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection
