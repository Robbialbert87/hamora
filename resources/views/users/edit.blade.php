@extends('layouts.app')

@section('title', 'Edit User - HAMORA')
@section('page-title', 'Edit User')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Edit User</h2>
                <p class="card-subtitle">Ubah data pengguna</p>
            </div>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 8 karakter">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                               value="{{ old('nip', $user->nip) }}">
                        @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Bidang</label>
                        <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                            <option value="">Pilih Bidang</option>
                            @foreach($bidang ?? [] as $b)
                            <option value="{{ $b->id }}" {{ old('bidang_id', $user->bidang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                            @endforeach
                        </select>
                        @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="">Pilih Role</option>
                            @foreach($roles ?? [] as $role)
                            <option value="{{ $role->name }}" {{ old('role', $user->roles->pluck('name')->first()) == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror"
                               value="{{ old('jabatan', $user->jabatan) }}">
                        @error('jabatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                               value="{{ old('no_telp', $user->no_telp) }}">
                        @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $user->is_active) === 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('users.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection
