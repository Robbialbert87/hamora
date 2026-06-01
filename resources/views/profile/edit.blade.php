@extends('layouts.app')

@section('title', 'Profile - HAMORA')
@section('page-title', 'Profile')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Informasi Profile</h2>
                <p class="card-subtitle">Perbarui data akun Anda</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                        <div class="text-muted mt-1" style="font-size: 12px;">Email tidak dapat diubah</div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Foto Profile</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                            @else
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--emerald), var(--gold)); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        </div>
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Ubah Password</h2>
                <p class="card-subtitle">Ganti password akun Anda</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required min="8">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan Password</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection