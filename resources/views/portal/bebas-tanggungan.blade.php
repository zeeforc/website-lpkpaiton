@extends('portal.layout')

@section('title', 'Bebas Tanggungan')

@push('styles')
<style>
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
    .timeline-v-item.rejected::before {
        background: #ef4444;
        box-shadow: 0 0 0 1px #ef4444;
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
    .download-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .download-box:hover {
        border-color: #cbd5e1;
        background-color: #f1f5f9;
    }
    .download-icon {
        font-size: 2.5rem;
        color: #fd7a2a;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Surat Bebas Tanggungan</h1>
    <p class="page-subtitle">Unduh format surat pernyataan dan unggah dokumen yang sudah ditandatangani.</p>
</div>

<div class="row">
    <!-- Form Pengajuan -->
    <div class="col-lg-7 mb-4">
        <div class="card-custom h-100">
            <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                <i class="fa-regular fa-file-lines text-primary fs-5"></i>
                <h6 class="fw-bold text-dark m-0">Unggah Surat Pernyataan</h6>
            </div>
            <div class="card-body-custom">
                @if($submission && $submission->status == 'pending')
                    <div class="alert alert-warning">
                        Pengajuan Anda sedang diproses. Anda tidak dapat mengunggah dokumen baru saat ini.
                    </div>
                @elseif($submission && $submission->status == 'disetujui')
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check me-2"></i> Dokumen bebas tanggungan Anda telah disetujui.
                    </div>
                @else
                    @if($template)
                    <div class="download-box mb-4">
                        <i class="fa-regular fa-file-pdf download-icon"></i>
                        <h6 class="fw-bold text-dark mb-1">Unduh Format Surat Pernyataan</h6>
                        <p class="text-secondary small mb-3">Silakan unduh, isi, dan tandatangani surat pernyataan berikut sebelum mengunggahnya kembali ke sistem.</p>
                        <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm px-4 fw-medium">
                            <i class="fa-solid fa-download me-2"></i> Unduh Format
                        </a>
                    </div>
                    @else
                    <div class="alert alert-info small mb-4">
                        <i class="fa-solid fa-circle-info me-1"></i> Admin belum mengunggah format surat pernyataan.
                    </div>
                    @endif

                    <form action="{{ route('portal.bebas-tanggungan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 0.85rem">Pilih Dokumen (PDF, JPG, PNG - Maks. 2MB) <span class="text-danger">*</span></label>
                            <input type="file" name="file_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                            @error('file_path')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="verifyCheck" required>
                                <label class="form-check-label text-secondary" for="verifyCheck" style="font-size: 0.85rem">
                                    Saya menyatakan bahwa dokumen yang diunggah adalah benar, sesuai dengan format, dan telah ditandatangani.
                                </label>
                            </div>
                            @error('is_verified')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-medium">
                            <i class="fa-solid fa-cloud-arrow-up me-2"></i> Unggah Dokumen
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Status & Timeline -->
    <div class="col-lg-5">
        <div class="card-custom mb-4">
            <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                <i class="fa-regular fa-clock text-primary"></i>
                <h6 class="fw-bold text-dark m-0">Status Pengajuan</h6>
            </div>
            <div class="card-body-custom">
                <div class="timeline-vertical">
                    @if(!$submission)
                        <div class="timeline-v-item">
                            <div class="timeline-v-title text-secondary">Belum ada pengajuan</div>
                        </div>
                    @else
                        <div class="timeline-v-item completed">
                            <div class="timeline-v-title">Dokumen Diunggah</div>
                            <div class="timeline-v-desc">{{ $submission->created_at->format('d M Y, H:i') }} WIB</div>
                        </div>
                        <div class="timeline-v-item {{ $submission->status == 'pending' ? 'active' : ($submission->status == 'disetujui' ? 'completed' : 'rejected') }}">
                            <div class="timeline-v-title">Verifikasi Admin</div>
                            @if($submission->status == 'pending')
                                <div class="timeline-v-desc">Sedang diperiksa...</div>
                            @elseif($submission->status == 'disetujui')
                                <div class="timeline-v-desc">Disetujui pada {{ $submission->updated_at->format('d M Y, H:i') }}</div>
                            @else
                                <div class="timeline-v-desc text-danger">Ditolak pada {{ $submission->updated_at->format('d M Y, H:i') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card-custom">
            <div class="card-header-custom d-flex align-items-center gap-2 py-3">
                <i class="fa-regular fa-comment text-primary"></i>
                <h6 class="fw-bold text-dark m-0">Catatan Admin</h6>
            </div>
            <div class="card-body-custom">
                @if($submission && $submission->admin_note)
                    <div class="alert {{ $submission->status == 'ditolak' ? 'alert-danger' : 'alert-info' }} m-0 p-3" style="font-size: 0.85rem">
                        <strong>Pesan dari Admin:</strong><br>
                        {{ $submission->admin_note }}
                    </div>
                @else
                    <div class="alert alert-warning m-0 p-3" style="font-size: 0.85rem">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        <strong>Belum ada catatan</strong><br>
                        Catatan dari admin akan muncul di sini jika ada.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
