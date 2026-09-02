@extends('portal.layout')

@section('title', 'Informasi PKL')

@push('styles')
<style>
    .info-header-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 30px;
        margin-bottom: 25px;
        align-items: center;
    }
    .info-badge {
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #3b82f6;
    }
    .info-divider {
        width: 1px;
        height: 50px;
        background: #e2e8f0;
    }
    .doc-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .doc-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }
    .status-tanggungan-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
    }
    .tanggungan-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .tanggungan-item:last-child {
        border-bottom: none;
    }
    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 15px;
    }
    .status-dot.bg-success { background-color: #22c55e !important; }
    .status-dot.bg-primary { background-color: #3b82f6 !important; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Informasi PKL</h1>
    <p class="page-subtitle">Panduan, ketentuan, dan informasi penting selama pelaksanaan PKL.</p>
</div>

<!-- Header Info -->
<div class="info-header-card">
    <div class="info-badge">
        <i class="fa-regular fa-clipboard"></i>
    </div>
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span style="width: 10px; height: 10px; background: #3b82f6; border-radius: 50%; display: inline-block;"></span>
            <span class="fw-bold text-dark">PKL Sedang Berlangsung</span>
        </div>
        <div class="text-secondary" style="font-size: 0.85rem">Periode PKL</div>
        <div class="fw-medium text-dark" style="font-size: 0.9rem">{{ $application->periode_gelombang }}</div>
    </div>
    <div class="info-divider"></div>
    <div>
        <div class="text-secondary mb-1" style="font-size: 0.85rem">Tempat PKL</div>
        <div class="fw-medium text-dark" style="font-size: 0.9rem">LPK Paiton Selaras</div>
    </div>
    <div class="info-divider"></div>
    <div>
        <div class="text-secondary mb-1" style="font-size: 0.85rem">Pembimbing Industri</div>
        <div class="fw-medium text-dark" style="font-size: 0.9rem">Admin, LPK</div>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri -->
    <div class="col-lg-6">
        <!-- Tata Tertib -->
        <div class="doc-card">
            <div class="doc-header">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-regular fa-file-lines text-primary me-2"></i> TATA TERTIB</h6>
                    <div class="fw-semibold text-dark">LPK Paiton Selaras</div>
                    <div class="text-secondary" style="font-size: 0.85rem">Ketentuan yang wajib dipatuhi selama mengikuti kegiatan PKL.</div>
                </div>
                <div class="text-end">
                    <i class="fa-regular fa-file-pdf fs-3 text-secondary"></i>
                    <div class="text-secondary mt-1" style="font-size: 0.75rem">PDF<br>1.2 MB</div>
                </div>
            </div>
            
            <div class="row mb-3" style="font-size: 0.85rem">
                <div class="col-6">
                    <ul class="text-secondary ps-3 mb-0">
                        <li>Ketentuan Umum</li>
                        <li>Kehadiran & Kedisiplinan</li>
                        <li>Sikap & Etika</li>
                    </ul>
                </div>
                <div class="col-6">
                    <ul class="text-secondary ps-3 mb-0">
                        <li>Penggunaan Fasilitas</li>
                        <li>Larangan</li>
                        <li>Sanksi Pelanggaran</li>
                    </ul>
                </div>
            </div>
            
            <button class="btn btn-outline-primary btn-sm rounded-pill fw-medium px-4">
                <i class="fa-solid fa-download me-2"></i> Lihat / Unduh Tata Tertib
            </button>
        </div>

        <!-- Status Tanggungan -->
        <div class="status-tanggungan-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-file-invoice text-primary me-2"></i> STATUS TANGGUNGAN ANDA</h6>
                @php
                    $belumCount = 0;
                    if(!$adminComplete) $belumCount++;
                    if(!$docComplete) $belumCount++;
                    if(!$laporan || $laporan->status != 'approved') $belumCount++;
                @endphp
                @if($belumCount > 0)
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">{{ $belumCount }} Belum Selesai</span>
                @else
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">Semua Selesai</span>
                @endif
            </div>
            <p class="text-secondary mb-4" style="font-size: 0.85rem">Berikut adalah tanggungan yang perlu Anda selesaikan selama PKL.</p>

            <!-- Administrasi -->
            <div class="tanggungan-item">
                <div class="d-flex align-items-start">
                    <div class="status-dot {{ $adminComplete ? 'bg-success' : 'bg-primary opacity-50' }} mt-1"></div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold text-dark">Administrasi PKL</span>
                            @if($adminComplete)
                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.7rem">Selesai</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.7rem">Belum Selesai</span>
                            @endif
                        </div>
                        <div class="text-secondary" style="font-size: 0.85rem">
                            Silakan lengkapi biodata Anda di halaman Biodata.
                        </div>
                    </div>
                </div>
                <a href="{{ route('portal.biodata') }}" class="btn btn-outline-custom">Lihat Detail</a>
            </div>

            <!-- Kelengkapan Dokumen -->
            <div class="tanggungan-item">
                <div class="d-flex align-items-start">
                    <div class="status-dot {{ $docComplete ? 'bg-success' : 'bg-primary opacity-50' }} mt-1"></div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold text-dark">Kelengkapan Dokumen</span>
                            @if($docComplete)
                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.7rem">Selesai</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.7rem">Belum Selesai</span>
                            @endif
                        </div>
                        <div class="text-secondary" style="font-size: 0.85rem">
                            Surat pengantar & proposal.
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-custom">Lihat Detail</button>
            </div>

            <!-- Laporan -->
            <div class="tanggungan-item">
                <div class="d-flex align-items-start">
                    <div class="status-dot {{ ($laporan && $laporan->status == 'approved') ? 'bg-success' : 'bg-primary opacity-50' }} mt-1"></div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-semibold text-dark">Laporan PKL</span>
                            @if($laporan && $laporan->status == 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.7rem">Selesai</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 0.7rem">Perlu Ditindaklanjuti</span>
                            @endif
                        </div>
                        <div class="text-secondary" style="font-size: 0.85rem">
                            Laporan akhir perlu dikumpulkan dan diverifikasi.
                        </div>
                    </div>
                </div>
                <a href="{{ route('portal.laporan') }}" class="btn btn-outline-custom">Lihat Detail</a>
            </div>
            
            <div class="mt-3 p-3 bg-light rounded text-secondary d-flex gap-2" style="font-size: 0.85rem">
                <i class="fa-solid fa-circle-info mt-1"></i>
                <span>Jika ada kendala, silakan hubungi staf LPK untuk mendapatkan bantuan.</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="col-lg-6">
        <!-- SOP PKL -->
        <div class="doc-card">
            <div class="doc-header">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-check text-primary me-2"></i> SOP PKL</h6>
                    <div class="fw-semibold text-dark">LPK Paiton Selaras</div>
                    <div class="text-secondary" style="font-size: 0.85rem">Prosedur dan alur pelaksanaan PKL di LPK Paiton Selaras.</div>
                </div>
                <div class="text-end">
                    <i class="fa-regular fa-file-pdf fs-3 text-secondary"></i>
                    <div class="text-secondary mt-1" style="font-size: 0.75rem">PDF<br>1.5 MB</div>
                </div>
            </div>
            
            <div class="mb-3 text-secondary fw-medium" style="font-size: 0.85rem">Tahapan SOP</div>
            <div class="d-flex justify-content-between text-center mb-4" style="font-size: 0.75rem; color: #64748b;">
                <div>
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 24px; height: 24px;">1</div><br>Persiapan
                </div>
                <div>
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 24px; height: 24px;">2</div><br>Pelaksanaan
                </div>
                <div>
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 24px; height: 24px;">3</div><br>Evaluasi
                </div>
                <div>
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-1" style="width: 24px; height: 24px;">4</div><br>Laporan
                </div>
            </div>
            
            <button class="btn btn-outline-primary btn-sm rounded-pill fw-medium px-4">
                <i class="fa-solid fa-download me-2"></i> Lihat / Unduh SOP
            </button>
        </div>

        <!-- Informasi Penting -->
        <div class="card-custom mb-3">
            <div class="card-body-custom">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-bullhorn text-primary me-2"></i> INFORMASI PENTING</h6>
                <ul class="text-secondary mb-0 ps-3" style="font-size: 0.85rem; line-height: 1.8;">
                    <li>Patuhi tata tertib LPK dan tempat PKL.</li>
                    <li>Ikuti alur dan prosedur sesuai SOP yang berlaku.</li>
                    <li>Laporkan kendala kepada pembimbing / staf terkait.</li>
                    <li>Selesaikan seluruh tanggungan sebelum batas waktu.</li>
                </ul>
            </div>
        </div>

        <!-- Butuh Bantuan -->
        <div class="card-custom">
            <div class="card-body-custom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-regular fa-user text-primary me-2"></i> BUTUH BANTUAN?</h6>
                    <div class="text-secondary mb-2" style="font-size: 0.85rem">Hubungi staf LPK Paiton Selaras.</div>
                    
                    <div class="fw-medium text-dark mt-3" style="font-size: 0.9rem">Staf Administrasi PKL</div>
                    <div class="text-secondary mt-1" style="font-size: 0.85rem"><i class="fa-solid fa-phone me-2"></i> +62 811-3059-8801</div>
                    <div class="text-secondary mt-1" style="font-size: 0.85rem"><i class="fa-regular fa-clock me-2"></i> Senin - Jumat | 08.00 - 16.00 WIB</div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <a href="https://wa.me/6281130598801" target="_blank" class="btn btn-outline-success rounded-pill fw-medium btn-sm px-3">
                        <i class="fa-brands fa-whatsapp me-1"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
