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

        @if($isFull)
            <div class="alert alert-warning p-4 border-warning mt-4 text-center">
                <h4 class="alert-heading fw-bold mb-3"><i data-feather="alert-circle" class="me-2"></i>Pendaftaran Ditutup</h4>
                <p>Mohon maaf, kuota peserta Praktik Kerja Lapangan (PKL) saat ini telah penuh.</p>
                <hr>
                <p class="mb-0 fw-bold">Pendaftaran diperkirakan akan dibuka kembali pada bulan: <span class="text-primary">{{ $predictedOpenMonth }}</span>.</p>
                <p class="mt-3 mb-0 small text-muted">Silakan kunjungi halaman ini lagi pada bulan tersebut.</p>
            </div>
            
            <div class="text-center mt-4">
                <a href="/" class="btn btn-outline-secondary px-4 py-2">Kembali ke Beranda</a>
            </div>
        @else
        <form action="{{ route('application.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nama_lengkap" class="form-label">NAMA LENGKAP (Kapitalisasi)<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required value="{{ old('nama_lengkap') }}">
            </div>

            <div class="mb-4">
                <label for="instansi" class="form-label">INSTANSI / PERGURUAN TINGGI (Kapitalisasi)<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Sekolah / Perguruan Tinggi" required value="{{ old('instansi') }}">
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
                <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Jurusan" required value="{{ old('jurusan') }}">
            </div>

            <div class="mb-4">
                <label for="no_hp" class="form-label">NO HANDPHONE ( Whatsapp ) <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No Handphone" required value="{{ old('no_hp') }}">
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
                <label for="periode_gelombang" class="form-label">PERIODE MULAI PKL (BULAN) <span class="text-danger">*</span></label>
                <select class="form-select" id="periode_gelombang" name="periode_gelombang" required>
                    <option value="" disabled {{ old('periode_gelombang') ? '' : 'selected' }}>-- Pilih Bulan --</option>
                    <option value="Januari" {{ old('periode_gelombang') == 'Januari' ? 'selected' : '' }}>Januari</option>
                    <option value="Februari" {{ old('periode_gelombang') == 'Februari' ? 'selected' : '' }}>Februari</option>
                    <option value="Maret" {{ old('periode_gelombang') == 'Maret' ? 'selected' : '' }}>Maret</option>
                    <option value="April" {{ old('periode_gelombang') == 'April' ? 'selected' : '' }}>April</option>
                    <option value="Mei" {{ old('periode_gelombang') == 'Mei' ? 'selected' : '' }}>Mei</option>
                    <option value="Juni" {{ old('periode_gelombang') == 'Juni' ? 'selected' : '' }}>Juni</option>
                    <option value="Juli" {{ old('periode_gelombang') == 'Juli' ? 'selected' : '' }}>Juli</option>
                    <option value="Agustus" {{ old('periode_gelombang') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                    <option value="September" {{ old('periode_gelombang') == 'September' ? 'selected' : '' }}>September</option>
                    <option value="Oktober" {{ old('periode_gelombang') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                    <option value="November" {{ old('periode_gelombang') == 'November' ? 'selected' : '' }}>November</option>
                    <option value="Desember" {{ old('periode_gelombang') == 'Desember' ? 'selected' : '' }}>Desember</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">APAKAH ANDA SUDAH MEMILIKI SEPATU SAFETY? <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sepatu_safety_a" id="sepatu_a_ya" value="Ya" required>
                    <label class="form-check-label" for="sepatu_a_ya">Ya, saya sudah memiliki</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sepatu_safety_a" id="sepatu_a_tidak" value="Tidak">
                    <label class="form-check-label" for="sepatu_a_tidak">Tidak memiliki</label>
                </div>
            </div>

            <div class="mb-4" id="sepatu_safety_b_container" style="display: none;">
                <label class="form-label">Jika tidak, apakah anda siap membeli sepatu safety demi keamanan diri anda dan mengikuti ketentuan keamanan LPK Paiton Selaras? <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sepatu_safety_b" id="sepatu_b_ya" value="Ya">
                    <label class="form-check-label" for="sepatu_b_ya">Ya, saya siap</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sepatu_safety_b" id="sepatu_b_tidak" value="Tidak">
                    <label class="form-check-label" for="sepatu_b_tidak">Tidak siap</label>
                </div>
            </div>

            <div class="mb-4">
                <label for="lama_durasi_bulan" class="form-label">LAMA DURASI (BULAN) <span class="text-danger">*</span></label>
                <div class="form-text text-muted mb-2">Minimal 2 bulan, maksimal 6 bulan.</div>
                <input type="number" class="form-control" id="lama_durasi_bulan" name="lama_durasi_bulan" placeholder="Contoh: 3" required min="2" max="6" value="{{ old('lama_durasi_bulan') }}">
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
                <label for="documents" class="form-label fw-bold">UNGGAH SURAT PENGANTAR SEKOLAH/KAMPUS <span class="text-danger">*</span></label>
                <div class="form-text text-muted mb-3">Lampirkan Surat Pengantar dari Sekolah/Kampus. Maksimal 5 MB per file (PDF/JPG/PNG).</div>
                <input class="form-control" type="file" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5">
                <a href="/" class="text-decoration-none" style="color: #5f6368; font-weight: 500;">Kembali</a>
                <button type="submit" class="btn btn-submit">Kirim</button>
            </div>
        </form>
        @endif
    </div>
</div>

@include('components.simulation-guide')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sepatu Safety Logic
    const radioA = document.querySelectorAll('input[name="sepatu_safety_a"]');
    const radioB = document.querySelectorAll('input[name="sepatu_safety_b"]');
    const containerB = document.getElementById('sepatu_safety_b_container');
    const form = document.querySelector('form');

    radioA.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Tidak') {
                containerB.style.display = 'block';
                document.getElementById('sepatu_b_ya').required = true;
            } else {
                containerB.style.display = 'none';
                document.getElementById('sepatu_b_ya').required = false;
                radioB.forEach(r => r.checked = false);
            }
        });
    });

    form.addEventListener('submit', function(e) {
        const valA = document.querySelector('input[name="sepatu_safety_a"]:checked')?.value;
        const valB = document.querySelector('input[name="sepatu_safety_b"]:checked')?.value;

        if (valA === 'Tidak' && valB === 'Tidak') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Tidak Memenuhi Syarat',
                text: 'Maaf, untuk mengikuti Praktik Kerja Lapangan di LPK Paiton Selaras, Anda diwajibkan untuk memiliki atau bersedia membeli sepatu safety demi keamanan kerja Anda.',
                confirmButtonColor: '#fd7a2a'
            });
            return;
        }

        // File validation
        const fileInput = document.getElementById('documents');
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                const file = fileInput.files[i];
                if (!allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Sesuai',
                        text: `File "${file.name}" memiliki format yang tidak diizinkan. Harap gunakan format PDF, JPG, atau PNG.`,
                        confirmButtonColor: '#fd7a2a'
                    });
                    return;
                }
                if (file.size > maxSize) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: `Ukuran file "${file.name}" melebihi batas maksimal 5 MB.`,
                        confirmButtonColor: '#fd7a2a'
                    });
                    return;
                }
            }
        }
    });
});
</script>
@endsection
