@extends('portal.layout')

@section('title', 'Perizinan')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Pengajuan Izin & Sakit</h1>
    <p class="page-subtitle">Ajukan permohonan izin tidak masuk atau sakit di sini.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('portal.izin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Izin/Sakit <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Perizinan <span class="text-danger">*</span></label>
                        <select class="form-select" name="type" required>
                            <option value="">Pilih Jenis...</option>
                            <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin (Kepentingan Keluarga/Kampus)</option>
                        </select>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alasan Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Tuliskan alasan lengkap Anda di sini..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Bukti Surat Keterangan <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="attachment" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="form-text">Upload surat dokter (jika sakit) atau surat dari kampus/keluarga (jika izin). Format: JPG, PNG, PDF. Maks: 2MB.</div>
                        @error('attachment')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Ajukan Perizinan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 bg-light-primary rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i> Informasi Perizinan</h5>
                <ul class="text-secondary small mb-0 ps-3">
                    <li class="mb-2">Pengajuan izin atau sakit wajib melampirkan surat keterangan yang sah.</li>
                    <li class="mb-2">Sakit: Wajib melampirkan surat keterangan dari dokter/klinik/puskesmas.</li>
                    <li class="mb-2">Izin: Wajib melampirkan surat dari kampus atau pihak keluarga.</li>
                    <li>Status kehadiran Anda akan diperbarui menjadi "Izin" atau "Sakit" setelah pengajuan disetujui oleh Admin.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-body p-4">
        <h5 class="fw-bold border-bottom pb-3 mb-3">Riwayat Pengajuan Anda</h5>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Alasan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $leave)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($leave->type) }}</span></td>
                        <td>{{ \Illuminate\Support\Str::limit($leave->reason, 30) }}</td>
                        <td>
                            @if($leave->attachment_path)
                                <a href="{{ Storage::url($leave->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i> Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($leave->status == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($leave->status == 'approved')
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $leave->admin_notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-3">Belum ada riwayat pengajuan perizinan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
