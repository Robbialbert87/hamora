@extends('layouts.auth')

@section('title', 'Login - HAMORA')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/logo.png') }}" alt="HAMORA"
                    style="width: 220px; height: auto; border-radius: 24px; object-fit: contain; margin: 0 auto 28px; display: block; filter: drop-shadow(0 12px 40px rgba(5, 150, 105, 0.3));">
            </div>

            @if (session('error'))
                <div class="alert alert-danger"
                    style="background: rgba(220, 38, 38, 0.15); border: 1px solid rgba(220, 38, 38, 0.3); color: #ef4444; border-radius: 12px; padding: 12px 18px; margin-bottom: 24px; font-size: 14px;">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success"
                    style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #22c55e; border-radius: 12px; padding: 12px 18px; margin-bottom: 24px; font-size: 14px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}"
                        required autofocus autocomplete="username" placeholder="Masukkan email">
                    @error('email')
                        <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required
                        autocomplete="current-password" placeholder="Masukkan password">
                    @error('password')
                        <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    {{-- @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                    @endif --}}
                </div>

                <button type="submit" class="btn btn-primary">Login</button>

                {{-- @if (Route::has('register'))
                <div class="login-footer" style="margin-top: 20px;">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                </div>
            @endif --}}
            </form>
        </div>
    </div>
@endsection
