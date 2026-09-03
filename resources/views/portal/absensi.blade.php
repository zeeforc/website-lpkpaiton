@extends('portal.layout')

@section('title', 'Absensi PKL')

@push('styles')
<style>
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 25px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-icon.bg-light-blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.bg-light-green { background: #f0fdf4; color: #22c55e; }
    .stat-icon.bg-light-red { background: #fef2f2; color: #ef4444; }
    .stat-icon.bg-light-purple { background: #f5f3ff; color: #8b5cf6; }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    .stat-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    
    .table-custom {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .table-custom th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-custom td {
        padding: 15px 20px;
        color: #334155;
        font-size: 0.9rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .status-badge.hadir { background: #f0fdf4; color: #16a34a; }
    .status-badge.hadir::before { background: #16a34a; }
    
    .status-badge.telat { background: #fef9c3; color: #eab308; }
    .status-badge.telat::before { background: #eab308; }
    
    .status-badge.tidak-hadir { background: #fef2f2; color: #dc2626; }
    .status-badge.tidak-hadir::before { background: #dc2626; }
</style>
@endpush

@section('content')
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div>
        <h1 class="page-title">Absensi PKL</h1>
        <p class="page-subtitle">Pantau dan lihat riwayat kehadiran Anda selama pelaksanaan PKL.</p>
    </div>
    <div>
        <a href="{{ route('portal.absensi.check-in') }}" class="btn btn-primary px-4 py-2" style="border-radius: 30px;">
            <i class="fa-solid fa-camera me-2"></i> {{ empty(Auth::user()->face_descriptor) ? 'Pindai Wajah' : 'Absen' }}
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-light-blue"><i class="fa-regular fa-calendar"></i></div>
            <div>
                <div class="stat-label">Total Hari</div>
                <div class="stat-value">{{ $totalDays }} <span class="fs-6 text-secondary fw-normal">Hari</span></div>
                <div class="stat-sub">Total hari kerja tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-light-green"><i class="fa-regular fa-circle-check"></i></div>
            <div>
                <div class="stat-label">Hadir</div>
                <div class="stat-value text-success">{{ $present }} <span class="fs-6 text-secondary fw-normal">Hari</span></div>
                <div class="stat-sub">Jumlah hari hadir</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-light-red"><i class="fa-solid fa-xmark"></i></div>
            <div>
                <div class="stat-label">Tidak Hadir</div>
                <div class="stat-value text-danger">{{ $absent }} <span class="fs-6 text-secondary fw-normal">Hari</span></div>
                <div class="stat-sub">Jumlah hari tidak hadir</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-light-purple"><i class="fa-solid fa-chart-pie"></i></div>
            <div>
                <div class="stat-label">Persentase Kehadiran</div>
                <div class="stat-value">{{ $percentage }}%</div>
                <div class="stat-sub">Dari total hari kerja</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Toolbar -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
    <form class="d-flex align-items-center gap-2" method="GET" action="{{ route('portal.absensi') }}">
        <span class="text-secondary fw-medium" style="font-size: 0.9rem">Periode</span>
        <select name="month" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            @for($i = 1; $i <= 12; $i++)
                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                </option>
            @endfor
        </select>
        <select name="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
            <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
            <option value="2027" {{ $year == '2027' ? 'selected' : '' }}>2027</option>
        </select>
    </form>
    
    <div class="text-secondary" style="font-size: 0.85rem">
        <i class="fa-regular fa-calendar-check me-1"></i> Data diperbarui: {{ now()->format('d M Y, H:i') }}
    </div>
</div>

<!-- Table -->
<div class="table-custom mb-4">
    <div class="p-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="fw-bold text-dark m-0">Riwayat Absensi</h6>
        <div class="d-flex gap-2">
            <a href="{{ route('portal.absensi.export', ['month' => request('month', date('m')), 'year' => request('year', date('Y'))]) }}" class="btn btn-outline-primary-custom"><i class="fa-solid fa-download me-1"></i> Unduh Excel</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($att->date)->isoFormat('dddd') }}</td>
                    <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}</td>
                    <td>{{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}</td>
                    <td>
                        @if($att->status == 'Hadir')
                            <span class="status-badge hadir">Hadir</span>
                        @elseif($att->status == 'Telat')
                            <span class="status-badge telat">Telat</span>
                        @else
                            <span class="status-badge tidak-hadir">{{ $att->status }}</span>
                        @endif
                    </td>
                    <td class="text-secondary">{{ $att->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">Tidak ada data absensi untuk periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 d-flex justify-content-between align-items-center border-top bg-light">
        <div class="text-secondary" style="font-size: 0.85rem">
            Menampilkan data absensi bulan ini.
        </div>
        <div>
            {{ $attendances->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="d-flex flex-column flex-md-row gap-3">
    <div class="flex-grow-1 p-3 bg-white border rounded-3 d-flex align-items-center gap-3">
        <i class="fa-solid fa-circle-info text-primary fs-4"></i>
        <div>
            <div class="fw-semibold text-dark" style="font-size: 0.85rem">Catatan:</div>
            <div class="text-secondary" style="font-size: 0.8rem">Data absensi ditampilkan berdasarkan data kehadiran yang tercatat pada sistem. Apabila terdapat kesalahan, silakan menghubungi staf LPK.</div>
        </div>
    </div>
    <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3" style="min-width: 300px;">
        <i class="fa-solid fa-headset text-primary fs-3"></i>
        <div>
            <div class="fw-semibold text-dark" style="font-size: 0.85rem">Butuh bantuan?</div>
            <a href="https://wa.me/6281130598801" target="_blank" class="btn btn-outline-primary btn-sm mt-1 rounded-pill">Hubungi Staf</a>
        </div>
    </div>
</div>
@endsection
