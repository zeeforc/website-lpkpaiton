@extends('layout')

@section('title', 'Cek Status Pendaftaran')

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
    .status-card {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="form-container">
        <div class="form-header">
            <h2 style="color: #0b5ed7; font-weight: 700;">CEK STATUS PENDAFTARAN</h2>
            <p class="text-muted">Masukkan email yang Anda gunakan saat mendaftar.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('application.check') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn btn-submit">Cek Status</button>
        </form>

        @if(isset($application))
            <div class="status-card mt-5">
                <h5 class="mb-3">Hasil Pencarian untuk: <strong>{{ $application->email_balasan }}</strong></h5>
                
                <div class="mb-2">
                    <strong>Nama Lengkap:</strong> {{ $application->nama_lengkap }}
                </div>
                <div class="mb-2">
                    <strong>Instansi:</strong> {{ $application->instansi }}
                </div>
                <div class="mb-2">
                    <strong>Tanggal Mendaftar:</strong> {{ $application->created_at->format('d M Y') }}
                </div>
                <div class="mb-3">
                    <strong>Status Saat Ini:</strong> 
                    @if($application->status === 'pending')
                        <span class="badge bg-secondary">Menunggu</span>
                    @elseif($application->status === 'permohonan_diterima')
                        <span class="badge bg-primary">Lolos Tahap Pengajuan</span>
                    @elseif($application->status === 'document_review')
                        <span class="badge bg-warning text-dark">Review Dokumen Kelengkapan</span>
                    @elseif($application->status === 'accepted')
                        <span class="badge bg-success">Lolos Sepenuhnya</span>
                    @elseif($application->status === 'rejected')
                        <span class="badge bg-danger">Ditolak / Revisi</span>
                    @endif
                </div>

                @if($application->status === 'permohonan_diterima')
                    <div class="alert alert-info mt-3">
                        <strong>Langkah Selanjutnya:</strong><br>
                        Selamat! Anda dinyatakan Lolos Tahap Pengajuan. Sesuai prosedur, silakan melengkapi berkas persyaratan pendaftaran (Fotokopi KTP, Pas Foto, SKCK, Surat Sehat).
                        <div class="mt-3">
                            <a href="{{ URL::signedRoute('application.upload', ['application' => $application->id]) }}" class="btn btn-primary btn-sm">
                                Upload Berkas Kelengkapan Sekarang
                            </a>
                        </div>
                    </div>
                @elseif($application->status === 'accepted')
                    <div class="alert alert-success mt-3">
                        <strong>Langkah Selanjutnya:</strong><br>
                        @if($application->tingkat_pendidikan === 'Mahasiswa')
                            Selamat! Anda dinyatakan Lolos Sepenuhnya. Silakan hubungi Admin atau pihak terkait melalui WhatsApp untuk arahan proses Interview.
                        @else
                            Selamat! Anda dinyatakan Lolos Sepenuhnya. Silakan tunggu email konfirmasi dari Admin mengenai jadwal/kapan Anda bisa mulai masuk.
                        @endif
                    </div>
                @endif

                @if($application->status === 'rejected' && $application->notes->count() > 0)
                    <div class="alert alert-warning mt-3">
                        <strong>Catatan Panitia:</strong><br>
                        {{ $application->notes->latest()->first()->note }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
