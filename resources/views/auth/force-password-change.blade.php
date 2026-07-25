@extends('layouts.auth')

@section('title', 'Ganti Password - HAMORA')

@section('inline-content')
<div class="auth-inline-header">
    <h4>Ganti Password</h4>
    <p class="text-muted mb-0">Ini login pertama Anda. Silakan ganti password default Anda.</p>
</div>

<form method="POST" action="{{ route('password.force-change.store') }}">
    @csrf

    <div class="form-group mb-3">
        <label class="form-label" for="password">
            <i class="ti ti-lock me-1"></i>Password Baru
        </label>
        <input id="password" type="password" name="password" class="form-control" required
            autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8">
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-4">
        <label class="form-label" for="password_confirmation">
            <i class="ti ti-lock-check me-1"></i>Konfirmasi Password
        </label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required
            autocomplete="new-password" placeholder="Masukkan ulang password baru">
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i>Simpan Password
        </button>
    </div>
</form>
@endsection
