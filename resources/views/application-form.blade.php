@extends('layout')

@section('title', 'Form Pendaftaran Praktik Kerja Lapangan')

@push('styles')
<style>
    .form-container {
        max-width: 860px;
        margin: 40px auto;
        background: #ffffff;
        padding: clamp(24px, 5vw, 48px);
        border: 1px solid #dfe5ef;
        border-radius: 20px;
        box-shadow: 0 18px 50px -30px rgba(15, 23, 42, 0.35);
    }
    .form-header {
        margin-bottom: 36px;
    }
    .form-eyebrow {
        display: inline-block;
        margin-bottom: 10px;
        color: #b84f0d;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .form-header h1 {
        margin-bottom: 8px;
        color: #0f172a;
        font-size: clamp(1.7rem, 4vw, 2.15rem);
        font-weight: 700;
        letter-spacing: -0.03em;
    }
    .form-header p {
        margin-bottom: 0;
        color: #475569;
    }
    .application-form .form-section {
        padding: 28px 0;
        border-top: 1px solid #e2e8f0;
    }
    .application-form .form-section:first-child {
        padding-top: 0;
        border-top: 0;
    }
    .section-heading {
        margin-bottom: 22px;
    }
    .section-heading h2 {
        margin-bottom: 5px;
        color: #0f172a;
        font-size: 1.15rem;
        font-weight: 700;
    }
    .section-heading p {
        margin-bottom: 0;
        color: #475569;
        font-size: 0.93rem;
    }
    .application-form .form-label {
        color: #334155;
        font-weight: 600;
    }
    .application-form .form-control,
    .application-form .form-select {
        min-height: 48px;
        padding: 11px 14px;
        border-color: #64748b;
        border-radius: 10px;
        color: #0f172a;
    }
    .application-form .form-check-input {
        border-color: #64748b;
    }
    .application-form textarea.form-control {
        min-height: auto;
    }
    .application-form .form-control:focus,
    .application-form .form-select:focus {
        border-color: #0b5ed7;
        box-shadow: 0 0 0 3px rgba(11, 94, 215, 0.2);
    }
    .application-form .form-check-input:focus,
    .application-form .btn:focus-visible,
    .application-form a:focus-visible {
        outline: 3px solid #0b5ed7;
        outline-offset: 2px;
        box-shadow: none;
    }
    .application-form .form-text {
        color: #475569;
    }
    .application-form .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 44px;
        padding-left: 0;
    }
    .application-form .form-check-input {
        flex: 0 0 auto;
        margin: 0;
    }
    .application-form .form-check-label {
        display: flex;
        flex: 1;
        align-items: center;
        min-height: 44px;
    }
    .document-upload {
        padding: 20px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
    }
    .btn-submit {
        min-height: 48px;
        padding: 11px 28px;
        border: 0;
        border-radius: 10px;
        background: #0b5ed7;
        color: #ffffff;
        font-weight: 600;
    }
    .btn-submit:hover {
        background: #084db3;
        color: #ffffff;
    }
    .btn-submit:focus-visible,
    .form-container a:focus-visible {
        outline: 3px solid #0b5ed7;
        outline-offset: 3px;
    }
    @media (max-width: 575.98px) {
        .form-container {
            margin: 8px auto;
            border-radius: 16px;
        }
        .application-form .form-section {
            padding: 24px 0;
        }
        .form-actions {
            align-items: stretch !important;
            flex-direction: column-reverse;
            gap: 12px;
        }
        .form-actions .btn-submit,
        .form-actions a {
            width: 100%;
            text-align: center;
        }
        .form-actions a {
            min-height: 44px;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <div class="form-container">
        <div class="form-header">
            <span class="form-eyebrow">LPK Paiton Selaras</span>
            <h1>Form Pendaftaran PKL</h1>
            <p>Lengkapi data diri, rencana kegiatan, dan dokumen pengantar dari sekolah atau kampus.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
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
        <form class="application-form" action="{{ route('application.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <section class="form-section" aria-labelledby="personal-info-heading">
                <div class="section-heading">
                    <h2 id="personal-info-heading">Informasi pribadi dan pendidikan</h2>
                    <p>Gunakan data yang sesuai dengan identitas sekolah atau kampus.</p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="nama_lengkap" class="form-label">Nama lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama sesuai identitas" required value="{{ old('nama_lengkap') }}">
                    </div>
                    <div class="col-12">
                        <label for="instansi" class="form-label">Sekolah atau perguruan tinggi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="instansi" name="instansi" placeholder="Nama sekolah atau perguruan tinggi" required value="{{ old('instansi') }}">
                    </div>
                    <div class="col-12">
                        <fieldset class="border-0 p-0 m-0">
                            <legend class="form-label">Tingkat pendidikan <span class="text-danger">*</span></legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tingkat_pendidikan" id="tingkat_pendidikan1" value="Mahasiswa" required {{ old('tingkat_pendidikan') == 'Mahasiswa' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tingkat_pendidikan1">Mahasiswa (perguruan tinggi)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tingkat_pendidikan" id="tingkat_pendidikan2" value="Siswa SMK/SMA" {{ old('tingkat_pendidikan') == 'Siswa SMK/SMA' ? 'checked' : '' }}>
                            <label class="form-check-label" for="tingkat_pendidikan2">Siswa SMK/SMA</label>
                        </div>
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <label for="nomor_induk" class="form-label"><span id="nomor_induk_label">NISN / NIM</span> <span class="text-danger">*</span></label>
                        <input type="text" inputmode="numeric" pattern="[0-9]+" maxlength="50" class="form-control" id="nomor_induk" name="nomor_induk" placeholder="NISN atau NIM" required value="{{ old('nomor_induk') }}" aria-describedby="nomor_induk_help">
                        <div id="nomor_induk_help" class="form-text">Masukkan NISN untuk siswa atau NIM untuk mahasiswa. Hanya angka.</div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="jurusan" class="form-label">Jurusan atau bidang studi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Jurusan atau bidang studi" required value="{{ old('jurusan') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="no_hp" class="form-label">Nomor WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor yang bisa dihubungi" required value="{{ old('no_hp') }}">
                    </div>
                    <div class="col-12">
                        <label for="email_balasan" class="form-label">Email untuk surat balasan <span class="text-danger">*</span></label>
                        <div class="form-text mb-2">Gunakan alamat email aktif. Email ini juga menjadi akun untuk masuk ke portal siswa.</div>
                        <input type="email" class="form-control" id="email_balasan" name="email_balasan" placeholder="nama@contoh.com" required value="{{ old('email_balasan') }}">
                    </div>
                </div>
            </section>

            <section class="form-section" aria-labelledby="application-info-heading">
                <div class="section-heading">
                    <h2 id="application-info-heading">Rencana kegiatan</h2>
                    <p>Pilih jenis pengajuan dan jelaskan rencana belajar selama kegiatan.</p>
                </div>

                <div class="mb-4">
                    <fieldset class="border-0 p-0 m-0">
                        <legend class="form-label">Jenis pengajuan <span class="text-danger">*</span></legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan1" value="Praktek Kerja Lapangan" required {{ old('pengajuan') == 'Praktek Kerja Lapangan' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pengajuan1">Praktik kerja lapangan</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan2" value="Penelitian/Tugas Akhir" {{ old('pengajuan') == 'Penelitian/Tugas Akhir' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pengajuan2">Penelitian atau tugas akhir</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pengajuan" id="pengajuan3" value="Industrial Visit" {{ old('pengajuan') == 'Industrial Visit' ? 'checked' : '' }}>
                        <label class="form-check-label" for="pengajuan3">Kunjungan industri</label>
                    </div>
                    </fieldset>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="periode_gelombang" class="form-label">Bulan mulai <span class="text-danger">*</span></label>
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
                    <div class="col-12 col-md-6">
                        <label for="lama_durasi_bulan" class="form-label">Lama kegiatan (bulan) <span class="text-danger">*</span></label>
                        <div class="form-text mb-2">Minimal 2 bulan, maksimal 6 bulan.</div>
                        <input type="number" class="form-control" id="lama_durasi_bulan" name="lama_durasi_bulan" placeholder="Contoh: 3" required min="2" max="6" value="{{ old('lama_durasi_bulan') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="fokus_studi" class="form-label">Fokus studi atau topik pembelajaran <span class="text-danger">*</span></label>
                    <div class="form-text mb-2">Tuliskan secara singkat fokus studi yang akan dilakukan selama kegiatan.</div>
                    <textarea class="form-control" id="fokus_studi" name="fokus_studi" rows="4" placeholder="Jelaskan fokus studi Anda" required>{{ old('fokus_studi') }}</textarea>
                </div>
            </section>

            <section class="form-section" aria-labelledby="requirements-heading">
                <div class="section-heading">
                    <h2 id="requirements-heading">Kesiapan dan dokumen</h2>
                    <p>Pastikan persyaratan keselamatan dan surat pengantar sudah disiapkan.</p>
                </div>

                <div class="mb-4">
                    <fieldset class="border-0 p-0 m-0">
                        <legend class="form-label">Apakah Anda sudah memiliki sepatu safety? <span class="text-danger">*</span></legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sepatu_safety_a" id="sepatu_a_ya" value="Ya" required>
                        <label class="form-check-label" for="sepatu_a_ya">Ya, saya sudah memiliki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sepatu_safety_a" id="sepatu_a_tidak" value="Tidak">
                        <label class="form-check-label" for="sepatu_a_tidak">Belum memiliki</label>
                    </div>
                    </fieldset>
                </div>

                <div class="mb-4" id="sepatu_safety_b_container" style="display: none;">
                    <fieldset class="border-0 p-0 m-0">
                        <legend class="form-label">Jika belum, apakah Anda bersedia membeli sepatu safety? <span class="text-danger">*</span></legend>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sepatu_safety_b" id="sepatu_b_ya" value="Ya">
                        <label class="form-check-label" for="sepatu_b_ya">Ya, saya bersedia</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sepatu_safety_b" id="sepatu_b_tidak" value="Tidak">
                        <label class="form-check-label" for="sepatu_b_tidak">Tidak bersedia</label>
                    </div>
                    </fieldset>
                </div>

                <div class="document-upload">
                    <label for="documents" class="form-label">Surat pengantar sekolah atau kampus <span class="text-danger">*</span></label>
                    <div class="form-text mb-3">Unggah file PDF, JPG, atau PNG. Ukuran maksimal 5 MB per file.</div>
                    <input class="form-control" type="file" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
            </section>

            <div class="form-actions d-flex justify-content-between align-items-center mt-4">
                <a href="/" class="text-decoration-none fw-semibold" style="color: #334155;">Kembali ke beranda</a>
                <button type="submit" class="btn btn-submit">Kirim pendaftaran</button>
            </div>
        </form>
        @endif
    </div>
</div>

@include('components.simulation-guide')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('duplicate_registration'))
<script>
Swal.fire({
    icon: 'warning',
    title: 'Data pendaftaran sudah ada',
    text: 'Anda sudah pernah mendaftar. Hubungi admin untuk pendaftaran ulang. Admin perlu menghapus data lama terlebih dahulu.',
    confirmButtonText: 'Mengerti',
    confirmButtonColor: '#0b5ed7'
});
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioA = document.querySelectorAll('input[name="sepatu_safety_a"]');
    const radioB = document.querySelectorAll('input[name="sepatu_safety_b"]');
    const containerB = document.getElementById('sepatu_safety_b_container');
    const form = document.querySelector('form');
    const identityLabel = document.getElementById('nomor_induk_label');
    const identityInput = document.getElementById('nomor_induk');
    const educationOptions = document.querySelectorAll('input[name="tingkat_pendidikan"]');

    function updateIdentityLabel() {
        if (!identityLabel || !identityInput) {
            return;
        }

        const selectedEducation = document.querySelector('input[name="tingkat_pendidikan"]:checked')?.value;
        const label = selectedEducation === 'Mahasiswa' ? 'NIM' : 'NISN';
        identityLabel.textContent = label;
        identityInput.placeholder = label;
    }

    educationOptions.forEach(option => option.addEventListener('change', updateIdentityLabel));
    updateIdentityLabel();

    if (form) {
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
                    text: 'Untuk mengikuti Praktik Kerja Lapangan, Anda perlu memiliki atau bersedia membeli sepatu safety.',
                    confirmButtonColor: '#0b5ed7'
                });
                return;
            }

            const fileInput = document.getElementById('documents');
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 5 * 1024 * 1024;

            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    const file = fileInput.files[i];
                    if (!allowedTypes.includes(file.type)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Format file tidak sesuai',
                            text: `File "${file.name}" harus berformat PDF, JPG, atau PNG.`,
                            confirmButtonColor: '#0b5ed7'
                        });
                        return;
                    }
                    if (file.size > maxSize) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Ukuran file terlalu besar',
                            text: `Ukuran file "${file.name}" melebihi batas maksimal 5 MB.`,
                            confirmButtonColor: '#0b5ed7'
                        });
                        return;
                    }
                }
            }
        });
    }
});
</script>
@endsection
