@extends('layouts.auth')

@section('title', 'Reset Password - HAMORA')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.webp') }}" alt="HAMORA" style="width: 70px; height: 70px; border-radius: 18px; object-fit: cover; margin: 0 auto 20px; display: block; box-shadow: 0 12px 40px rgba(5, 150, 105, 0.3);">
            <h1 class="login-title">Reset Password</h1>
            <p class="login-subtitle">Buat password baru</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="Masukkan email">
                @error('email')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password Baru</label>
                <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Masukkan password baru">
                @error('password')
                    <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>
@endsection
