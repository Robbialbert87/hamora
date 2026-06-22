@extends('layouts.auth')

@section('title', 'Ganti Password - HAMORA')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/logo.webp') }}" alt="HAMORA"
                    style="width: 220px; height: auto; border-radius: 24px; object-fit: contain; margin: 0 auto 28px; display: block; filter: drop-shadow(0 12px 40px rgba(5, 150, 105, 0.3));">
            </div>

            <div style="text-align: center; margin-bottom: 24px; background: rgba(5, 150, 105, 0.1); border-radius: 12px; padding: 16px;">
                <p style="margin: 0; color: var(--text-secondary); font-size: 14px; line-height: 1.5;">
                    Ini login pertama Anda. Silakan ganti password default Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.force-change.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="password">Password Baru</label>
                    <input id="password" type="password" name="password" class="form-input" required
                        autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8">
                    @error('password')
                        <div style="color: var(--danger); font-size: 13px; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required
                        autocomplete="new-password" placeholder="Masukkan ulang password baru">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Password</button>
            </form>
        </div>
    </div>
@endsection
