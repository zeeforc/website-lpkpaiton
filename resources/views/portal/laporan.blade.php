@extends('portal.layout')

@section('title', 'Pengajuan Laporan PKL')

@push('styles')
<style>
    .timeline-container {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 25px;
    }
    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: 20px;
        padding: 0 40px;
    }
    .timeline-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 80px;
        right: 80px;
        height: 2px;
        background: #e2e8f0;
        z-index: 1;
    }
    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        width: 120px;
    }
    .timeline-icon {
        width: 40px;
        height: 40px;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 600;
        color: #94a3b8;
    }
    .timeline-step.active .timeline-icon {
        background: #eab308;
        border-color: #eab308;
        color: #fff;
    }
    .timeline-step.completed .timeline-icon {
        background: #22c55e;
        border-color: #22c55e;
        color: #fff;
    }
    .timeline-step.completed ~ .timeline-step::before {
        background: #22c55e;
    }
    .timeline-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }
    .timeline-date {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 5px;
    }
    .card-custom {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    .card-header-custom {
        padding: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .card-body-custom {
        padding: 20px;
    }
    .form-control-lock {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }
    .lock-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .timeline-vertical {
        position: relative;
        padding-left: 25px;
    }
    .timeline-vertical::before {
        content: '';
        position: absolute;
        top: 5px;
        bottom: 0;
        left: 5px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-v-item {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-v-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #cbd5e1;
    }
    .timeline-v-item.active::before {
        background: #eab308;
        box-shadow: 0 0 0 1px #eab308;
    }
    .timeline-v-item.completed::before {
        background: #22c55e;
        box-shadow: 0 0 0 1px #22c55e;
    }
    .timeline-v-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
    }
    .timeline-v-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Pengajuan Laporan PKL</h1>
    <p class="page-subtitle">Ajukan permohonan laporan PKL dan pantau status pengajuan Anda.</p>
</div>

<!-- Timeline Header -->
<div class="timeline-container">
    <div class="d-flex align-items-center gap-3">
        @if(!$laporan)
            <i class="fa-regular fa-clock fs-1 text-secondary"></i>
            <div>
                <h5 class="fw-bold text-dark m-0">Belum Ada Pengajuan</h5>
                <div class="text-secondary mt-1" style="font-size: 0.85rem">Silakan isi form di bawah untuk mengajukan laporan.</div>
            </div>
        @elseif($laporan->status == 'pending')
            <i class="fa-regular fa-clock fs-1 text-warning"></i>
            <div>
                <h5 class="fw-bold text-dark m-0 text-warning">Menunggu Persetujuan Admin</h5>
                <div class="text-secondary mt-1" style="font-size: 0.85rem">
                    <i class="fa-regular fa-calendar me-1"></i> Diajukan pada: {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
        @elseif($laporan->status == 'approved')
            <i class="fa-regular fa-circle-check fs-1 text-success"></i>
            <div>
                <h5 class="fw-bold text-dark m-0 text-success">Pengajuan Disetujui</h5>
                <div class="text-secondary mt-1" style="font-size: 0.85rem">
                    <i class="fa-regular fa-calendar-check me-1"></i> Disetujui pada: {{ $laporan->updated_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
        @elseif($laporan->status == 'rejected')
            <i class="fa-regular fa-circle-xmark fs-1 text-danger"></i>
            <div>
                <h5 class="fw-bold text-dark m-0 text-danger">Pengajuan Ditolak</h5>
                <div class="text-secondary mt-1" style="font-size: 0.85rem">
                    Silakan perbaiki dan ajukan ulang laporan Anda.
                </div>
            </div>
        @endif
    </div>

    <div class="timeline-steps mt-4">
        <div class="timeline-step {{ $laporan ? 'completed' : 'active' }}">
            <div class="timeline-icon">1</div>
            <div class="timeline-label">Pengajuan Dibuat</div>
            <div class="timeline-date">{{ $laporan ? $laporan->created_at->format('d M Y, H:i') : '-' }}</div>
        </div>
        <div class="timeline-step {{ $laporan && $laporan->status == 'approved' ? 'completed' : ($laporan && $laporan->status == 'pending' ? 'active' : '') }}">
            <div class="timeline-icon">2</div>
            <div class="timeline-label">Verifikasi Admin</div>
            <div class="timeline-date">-</div>
        </div>
        <div class="timeline-step {{ $laporan && $laporan->status == 'approved' ? 'completed' : '' }}">
            <div class="timeline-icon">3</div>
            <div class="timeline-label">Pengajuan Disetujui</div>
            <div class="timeline-date">{{ $laporan && $laporan->status == 'approved' ? $laporan->updated_at->format('d M Y, H:i') : '-' }}</div>
        </div>
        <div class="timeline-step {{ $laporan && $laporan->status == 'approved' ? 'active' : '' }}">
            <div class="timeline-icon">4</div>
            <div class="timeline-label">Sertifikat Tersedia</div>
            <div class="timeline-date">-</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Form Pengajuan -->
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header-custom d-flex align-items-center gap-2">
                <i class="fa-regular fa-file-lines text-primary fs-5"></i>
                <h6 class="fw-bold text-dark m-0">Form Pengajuan Laporan</h6>
            </div>
            <div class="card-body-custom">
                @if($laporan && $laporan->status == 'pending')
                    <div class="alert alert-warning">
                        Pengajuan Anda sedang diproses. Anda tidak dapat membuat pengajuan baru saat ini.
                    </div>
                @elseif($laporan && $laporan->status == 'approved')
                    <div class="alert alert-success">
                        Pengajuan laporan Anda telah disetujui.
                    </div>
                @else
                    <form action="{{ route('portal.laporan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-primary fw-semibold" style="font-size: 0.85rem">Data Pengajuan <span class="text-secondary fw-normal">(diambil dari biodata)</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Nama Lengkap</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="{{ optional($application)->nama_lengkap ?? Auth::user()->name }}" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Jurusan</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="{{ optional($application)->jurusan ?? '-' }}" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">NIS / NISN</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="{{ optional($profile)->nis ?? '-' }} / {{ optional($profile)->nisn ?? '-' }}" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Tempat PKL</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="LPK Paiton Selaras" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Asal Sekolah</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="{{ optional($application)->instansi ?? '-' }}" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Periode PKL</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-sm form-control-lock" value="{{ optional($application)->periode_gelombang ?? '-' }}" readonly>
                                        <i class="fa-solid fa-lock lock-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="text-secondary" style="opacity: 0.1; margin: 25px 0;">

                        <div class="mb-3">
                            <label class="form-label text-primary fw-semibold" style="font-size: 0.85rem">Informasi Pengajuan</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Judul / Tema Laporan <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="Contoh: Analisis Sistem Informasi..." required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Upload Laporan (PDF) <span class="text-danger">*</span></label>
                                    <input type="file" name="file_path" class="form-control form-control-sm" accept=".pdf" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-secondary" style="font-size: 0.85rem">Catatan untuk Admin (opsional)</label>
                                    <textarea name="notes" class="form-control form-control-sm" rows="3" placeholder="Tuliskan catatan atau informasi tambahan..."></textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-medium mt-3">
                            <i class="fa-regular fa-paper-plane me-2"></i> Ajukan Laporan
                        </button>
                    </form>
                    
                    <div class="mt-3 p-3 bg-light rounded text-secondary d-flex gap-2" style="font-size: 0.8rem">
                        <i class="fa-solid fa-circle-info mt-1"></i>
                        <span>Data identitas otomatis diambil dari biodata siswa dan tidak dapat diubah melalui halaman ini.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Info & Catatan -->
    <div class="col-lg-5">
        <!-- Template Laporan -->
        <div class="card-custom mb-3">
            <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                <i class="fa-solid fa-lock text-primary"></i>
                <h6 class="fw-bold text-dark m-0">Sertifikat PKL</h6>
            </div>
            <div class="card-body-custom py-4">
                @if($certificates->count() > 0)
                    @php $cert = $certificates->sortByDesc('created_at')->first(); @endphp
                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 border rounded text-start d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fa-solid fa-certificate fs-2 text-warning"></i>
                                <div>
                                    <h6 class="m-0 fw-bold text-dark">{{ $cert->title }}</h6>
                                    <small class="text-secondary">Diterbitkan: {{ $cert->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                <i class="fa-solid fa-download me-1"></i> Unduh
                            </a>
                        </div>
                    </div>
                @elseif($laporan && $laporan->status == 'approved')
                    <div class="text-center py-4">
                        <i class="fa-solid fa-award fs-1 text-success mb-3"></i>
                        <p class="text-secondary" style="font-size: 0.85rem">Laporan disetujui. Sertifikat Anda sedang disiapkan oleh admin.</p>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4" disabled>Menunggu Sertifikat</button>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa-solid fa-lock fs-1 text-secondary mb-3 opacity-50"></i>
                        <p class="text-secondary" style="font-size: 0.85rem">Sertifikat PKL akan tersedia setelah laporan Anda disetujui oleh admin.</p>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4" disabled>Belum Tersedia</button>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card-custom h-100">
                    <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                        <i class="fa-regular fa-clock text-primary"></i>
                        <h6 class="fw-bold text-dark m-0">Timeline</h6>
                    </div>
                    <div class="card-body-custom">
                        <div class="timeline-vertical">
                            @if(!$laporan)
                                <div class="timeline-v-item">
                                    <div class="timeline-v-title text-secondary">Belum ada pengajuan</div>
                                </div>
                            @else
                                <div class="timeline-v-item completed">
                                    <div class="timeline-v-title">Pengajuan Dibuat</div>
                                    <div class="timeline-v-desc">{{ $laporan->created_at->format('d M Y, H:i') }} WIB</div>
                                </div>
                                <div class="timeline-v-item {{ $laporan->status == 'pending' ? 'active' : 'completed' }}">
                                    <div class="timeline-v-title">Menunggu Verifikasi Admin</div>
                                    <div class="timeline-v-desc">Sedang diperiksa...</div>
                                </div>
                                @if($laporan->status == 'approved')
                                    <div class="timeline-v-item completed">
                                        <div class="timeline-v-title">Pengajuan Disetujui</div>
                                        <div class="timeline-v-desc">Selesai</div>
                                    </div>
                                @endif
                                @if($laporan->status == 'rejected')
                                    <div class="timeline-v-item active">
                                        <div class="timeline-v-title text-danger">Pengajuan Ditolak</div>
                                        <div class="timeline-v-desc text-danger">Revisi diperlukan</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card-custom h-100">
                    <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                        <i class="fa-regular fa-comment text-primary"></i>
                        <h6 class="fw-bold text-dark m-0">Catatan Admin</h6>
                    </div>
                    <div class="card-body-custom">
                        @if($laporan && $laporan->admin_note)
                            <div class="alert {{ $laporan->status == 'rejected' ? 'alert-danger' : 'alert-info' }} m-0 p-3" style="font-size: 0.85rem">
                                <strong>Pesan dari Admin:</strong><br>
                                {{ $laporan->admin_note }}
                                @if($laporan->status == 'rejected')
                                    <hr style="opacity: 0.15; margin: 10px 0;">
                                    <span class="fw-medium"><i class="fa-solid fa-circle-exclamation me-1"></i> Harap segera menghubungi staf administrasi LPK Paiton Selaras untuk tindak lanjut penyelesaian tanggungan Anda.</span>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-warning m-0 p-3" style="font-size: 0.85rem">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                <strong>Belum ada catatan</strong><br>
                                Catatan dari admin akan muncul di sini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
