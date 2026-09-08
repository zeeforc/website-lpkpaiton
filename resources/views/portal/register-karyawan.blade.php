@extends('portal.layout')

@section('title', 'Login Portal PKL')

@push('styles')
<style>
    .login-container {
        max-width: 480px;
        margin: 100px auto;
        background: rgba(255, 255, 255, 0.35); /* Lowered opacity for glass effect */
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        text-align: center;
        position: relative;
        z-index: 10;
    }
    .login-header {
        margin-bottom: 40px;
    }
    .login-header i {
        font-size: 3.5rem;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
    .login-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0F172A;
        letter-spacing: -0.5px;
    }
    .login-subtitle {
        color: #64748b;
        font-size: 1rem;
    }
    .form-control-custom {
        background-color: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .form-control-custom:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15), inset 0 2px 4px rgba(0,0,0,0.02);
        background-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-1px);
    }
    .btn-login {
        background-color: #111827;
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 14px;
        width: 100%;
        font-weight: 600;
        font-size: 1.05rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-top: 10px;
    }
    .btn-login:hover {
        background-color: #3b82f6;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
    }
    .btn-login:active {
        transform: scale(0.97);
    }
</style>
@endpush

@section('content')
<div class="position-relative d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <!-- Decorative Blobs behind the login card to make glassmorphism pop -->
    <div style="position: absolute; top: 30%; left: 35%; width: 350px; height: 350px; background: #3b82f6; filter: blur(90px); opacity: 0.45; border-radius: 50%; z-index: 1; animation: float 6s ease-in-out infinite;"></div>
    <div style="position: absolute; top: 50%; left: 55%; width: 300px; height: 300px; background: #8b5cf6; filter: blur(90px); opacity: 0.45; border-radius: 50%; z-index: 1; animation: float 8s ease-in-out infinite reverse;"></div>

    <style>
        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
            100% { transform: translateY(0px) scale(1); }
        }
    </style>

    <div class="login-container w-100">
    <div class="login-header">
        <i class="fa-solid fa-users-rectangle"></i>
        <h1 class="login-title">Pendaftaran Karyawan Paving</h1>
        <p class="login-subtitle">Daftarkan akun Anda untuk absensi harian</p>
    </div>

    <form action="{{ route('portal.register-karyawan.post') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label text-secondary fw-semibold" style="font-size: 0.9rem;">Nama Lengkap</label>
            <input type="text" name="name" class="form-control form-control-custom" placeholder="Masukkan nama lengkap Anda" required value="{{ old('name') }}">
            @error('name')
                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 text-start">
            <label class="form-label text-secondary fw-semibold" style="font-size: 0.9rem;">Email</label>
            <input type="email" name="email" class="form-control form-control-custom" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
            @error('email')
                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4 text-start">
            <label class="form-label text-secondary fw-semibold" style="font-size: 0.9rem;">Password</label>
            <input type="password" name="password" class="form-control form-control-custom" placeholder="Buat password (min. 6 karakter)" required>
            @error('password')
                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-login">Daftar Sekarang</button>
    </form>
    
    <div class="mt-4 text-center">
        <a href="{{ route('portal.login') }}" class="text-decoration-none text-primary" style="font-size: 0.9rem; font-weight: 500;">Sudah punya akun? Login di sini</a>
    </div>
    
    <div class="mt-4 text-secondary fw-medium" style="font-size: 0.85rem;">
        &copy; {{ date('Y') }} LPK Paiton Selaras.
    </div>
</div>
</div>
@endsection
