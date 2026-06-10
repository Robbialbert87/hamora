@extends('layouts.auth')

@section('title', 'Register - HAMORA')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" style="width: 70px; height: 70px; border-radius: 18px; object-fit: cover; margin: 0 auto 20px; display: block; box-shadow: 0 12px 40px rgba(5, 150, 105, 0.3);">
            <h1 class="login-title">Daftar Akun</h1>
            <p class="login-subtitle">Buat akun baru HAMORA</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Nama</label>
                <input id="name" type="text" name="name" class="form-input" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama">
                @error('name')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan email">
                @error('email')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Masukkan password">
                @error('password')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Ulangi password">
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>

            <div class="login-footer" style="margin-top: 20px;">
                Sudah punya akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
