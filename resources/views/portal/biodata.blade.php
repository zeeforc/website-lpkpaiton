@extends('portal.layout')

@section('title', 'Biodata Siswa')

@push('styles')
<style>
    .profile-card {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        background-color: #e2e8f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #94a3b8;
    }
    .info-legend {
        display: flex;
        gap: 20px;
        align-items: center;
        background: #f8fafc;
        padding: 15px 20px;
        border-radius: 8px;
        margin-top: 20px;
        font-size: 0.85rem;
        color: #64748b;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .legend-color-gray {
        width: 16px;
        height: 16px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
    }
    .legend-color-blue {
        width: 16px;
        height: 16px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 4px;
    }
    .input-readonly {
        background-color: #f8fafc !important;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Biodata Siswa</h1>
    <p class="page-subtitle">Kelola informasi pribadi dan data PKL Anda.</p>
</div>

<form action="{{ route('portal.biodata.update') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-lg-7">
            <!-- Data Pribadi -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom"><i class="fa-regular fa-id-card"></i> DATA PRIBADI</h5>
                    <button type="submit" class="btn-outline-primary-custom">Simpan Perubahan</button>
                </div>
                <div class="card-body-custom">
                    <div class="profile-card mb-4">
                        <div class="profile-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div style="flex:1;">
                            <div class="row mb-2">
                                <div class="col-sm-4 text-secondary">Nama Lengkap</div>
                                <div class="col-sm-8 fw-medium">{{ $application->nama_lengkap }}</div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-sm-4 text-secondary">Nama Panggilan</div>
                                <div class="col-sm-8">
                                    <input type="text" name="nama_panggilan" class="form-control form-control-sm" value="{{ $profile->nama_panggilan }}">
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-sm-4 text-secondary">NIS</div>
                                <div class="col-sm-8">
                                    <input type="text" name="nis" class="form-control form-control-sm" value="{{ $profile->nis }}">
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-sm-4 text-secondary">NISN</div>
                                <div class="col-sm-8">
                                    <input type="text" name="nisn" class="form-control form-control-sm" value="{{ $profile->nisn }}">
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-sm-4 text-secondary">Jenis Kelamin</div>
                                <div class="col-sm-8">
                                    <select name="jenis_kelamin" class="form-select form-select-sm">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ $profile->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $profile->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2 align-items-center">
                                <div class="col-sm-4 text-secondary">Tempat, Tgl Lahir</div>
                                <div class="col-sm-4">
                                    <input type="text" name="tempat_lahir" class="form-control form-control-sm" placeholder="Tempat" value="{{ $profile->tempat_lahir }}">
                                </div>
                                <div class="col-sm-4">
                                    <input type="date" name="tanggal_lahir" class="form-control form-control-sm" value="{{ $profile->tanggal_lahir }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="text-secondary" style="opacity: 0.1">
                    
                    <div class="row mb-2">
                        <div class="col-sm-3 text-secondary">No. HP</div>
                        <div class="col-sm-3 fw-medium">{{ $application->no_hp }}</div>
                        <div class="col-sm-2 text-secondary">Email</div>
                        <div class="col-sm-4 fw-medium">{{ $application->email_balasan }}</div>
                    </div>
                    <div class="row mt-3 align-items-start">
                        <div class="col-sm-3 text-secondary pt-1">Alamat</div>
                        <div class="col-sm-9">
                            <textarea name="alamat_lengkap" class="form-control form-control-sm" rows="2">{{ $profile->alamat_lengkap }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Penempatan PKL -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom"><i class="fa-solid fa-location-dot text-warning"></i> DATA PENEMPATAN PKL</h5>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle text-success me-1" style="font-size: 0.5rem"></i> Aktif</span>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="data-row">
                                <div class="data-label">Status PKL</div>
                                <div class="data-separator">:</div>
                                <div class="data-value text-primary fw-bold">Sedang Berlangsung</div>
                            </div>
                            <div class="data-row">
                                <div class="data-label">Periode PKL</div>
                                <div class="data-separator">:</div>
                                <div class="data-value">{{ $application->periode_gelombang }} ({{ $application->lama_durasi_bulan }} Bulan)</div>
                            </div>
                            <div class="data-row">
                                <div class="data-label">Nama Instansi</div>
                                <div class="data-separator">:</div>
                                <div class="data-value">LPK Paiton Selaras</div>
                            </div>
                            <div class="data-row">
                                <div class="data-label">Posisi / Bidang</div>
                                <div class="data-separator">:</div>
                                <div class="data-value">{{ $application->jurusan }}</div>
                            </div>
                            
                            <hr class="text-secondary my-4" style="opacity: 0.1">
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-secondary fw-semibold mb-2" style="font-size: 0.85rem">Pembimbing Industri</div>
                                    <div class="mb-1"><i class="fa-regular fa-user text-secondary me-2"></i> Admin LPK</div>
                                    <div><i class="fa-solid fa-phone text-secondary me-2"></i> +62 811-3059-8801</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-secondary fw-semibold mb-2" style="font-size: 0.85rem">Guru Pembimbing</div>
                                    <div class="mb-1">
                                        <input type="text" name="guru_pembimbing_nama" class="form-control form-control-sm" placeholder="Nama Guru" value="{{ $profile->guru_pembimbing_nama }}">
                                    </div>
                                    <div>
                                        <input type="text" name="guru_pembimbing_hp" class="form-control form-control-sm" placeholder="No HP Guru" value="{{ $profile->guru_pembimbing_hp }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="info-legend">
                <div><i class="fa-solid fa-circle-info text-primary me-2"></i> Data yang diubah harus sesuai dengan data asli dari pihak sekolah/kampus.</div>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-lg-5">
            <!-- Data Sekolah -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom"><i class="fa-solid fa-school text-primary"></i> DATA SEKOLAH/KAMPUS</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">Nama Sekolah</div>
                        <div class="col-sm-7 fw-medium">{{ $application->instansi }}</div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">NPSN</div>
                        <div class="col-sm-7">
                            <input type="text" name="npsn" class="form-control form-control-sm" value="{{ $profile->npsn }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">Jurusan</div>
                        <div class="col-sm-7 fw-medium">{{ $application->jurusan }}</div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">Kelas / SMT</div>
                        <div class="col-sm-7">
                            <input type="text" name="kelas" class="form-control form-control-sm" value="{{ $profile->kelas }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">Tahun Ajaran</div>
                        <div class="col-sm-7">
                            <input type="text" name="tahun_ajaran" class="form-control form-control-sm" value="{{ $profile->tahun_ajaran }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">Wali Kelas</div>
                        <div class="col-sm-7">
                            <input type="text" name="nama_wali_kelas" class="form-control form-control-sm" value="{{ $profile->nama_wali_kelas }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-5 text-secondary">HP Wali Kelas</div>
                        <div class="col-sm-7">
                            <input type="text" name="no_hp_wali_kelas" class="form-control form-control-sm" value="{{ $profile->no_hp_wali_kelas }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak Darurat -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom"><i class="fa-solid fa-phone-volume text-purple" style="color: #8b5cf6"></i> KONTAK DARURAT</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-4 text-secondary">Nama</div>
                        <div class="col-sm-8">
                            <input type="text" name="nama_kontak_darurat" class="form-control form-control-sm" value="{{ $profile->nama_kontak_darurat }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-4 text-secondary">Hubungan</div>
                        <div class="col-sm-8">
                            <input type="text" name="hubungan_kontak_darurat" class="form-control form-control-sm" placeholder="Contoh: Ayah / Ibu" value="{{ $profile->hubungan_kontak_darurat }}">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center">
                        <div class="col-sm-4 text-secondary">No. HP</div>
                        <div class="col-sm-8">
                            <input type="text" name="no_hp_kontak_darurat" class="form-control form-control-sm" value="{{ $profile->no_hp_kontak_darurat }}">
                        </div>
                    </div>
                    <div class="row mt-2 align-items-start">
                        <div class="col-sm-4 text-secondary pt-1">Alamat</div>
                        <div class="col-sm-8">
                            <textarea name="alamat_kontak_darurat" class="form-control form-control-sm" rows="2">{{ $profile->alamat_kontak_darurat }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Akun -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom"><i class="fa-solid fa-shield-halved text-info"></i> INFORMASI AKUN</h5>
                </div>
                <div class="card-body-custom">
                    <div class="data-row">
                        <div class="data-label" style="width: 120px;">Email Login</div>
                        <div class="data-separator">:</div>
                        <div class="data-value">{{ $user->email }}</div>
                    </div>
                    <div class="data-row mb-0">
                        <div class="data-label" style="width: 120px;">Terakhir Login</div>
                        <div class="data-separator">:</div>
                        <div class="data-value">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d F Y, H:i') . ' WIB' : '-' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="info-legend d-flex justify-content-center mt-3 bg-white border">
                <div class="legend-item"><div class="legend-color-gray"></div> Dikelola Admin</div>
                <div class="legend-item ms-4"><div class="legend-color-blue"></div> Dapat diedit siswa</div>
            </div>
        </div>
    </div>
</form>
@endsection
