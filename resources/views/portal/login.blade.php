@extends('portal.layout')

@section('title', 'Login Portal PKL')

@push('styles')
<style>
    .login-container {
        max-width: 450px;
        margin: 80px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 40px;
        text-align: center;
    }
    .login-header {
        margin-bottom: 30px;
    }
    .login-header i {
        font-size: 3rem;
        color: #2563eb;
        margin-bottom: 15px;
    }
    .login-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    .login-subtitle {
        color: #64748b;
        font-size: 0.95rem;
    }
    .form-control-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 15px;
    }
    .form-control-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: #fff;
    }
    .btn-login {
        background-color: #2563eb;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        font-size: 1rem;
        transition: background-color 0.2s;
    }
    .btn-login:hover {
        background-color: #1d4ed8;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-header">
        <i class="fa-solid fa-users-rectangle"></i>
        <h1 class="login-title">Portal Siswa PKL</h1>
        <p class="login-subtitle">Masuk untuk mengelola kegiatan PKL Anda</p>
    </div>

    <form action="{{ route('portal.login.post') }}" method="POST">
        @csrf
        <div class="mb-4 text-start">
            <label class="form-label text-secondary fw-medium" style="font-size: 0.9rem;">Email Login</label>
            <input type="email" name="email" class="form-control form-control-custom" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
            @error('email')
                <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4 text-start">
            <label class="form-label text-secondary fw-medium" style="font-size: 0.9rem;">Password</label>
            <input type="password" name="password" class="form-control form-control-custom" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn btn-login">Masuk ke Portal</button>
    </form>
    
    <div class="mt-4 text-secondary" style="font-size: 0.85rem;">
        &copy; {{ date('Y') }} LPK Paiton Selaras.
    </div>
</div>
@endsection
