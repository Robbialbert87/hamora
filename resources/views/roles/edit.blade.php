@extends('layouts.app')

@section('title', 'Edit Role - HAMORA')
@section('page-title', 'Edit Role')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Edit Role</h2>
                <p class="card-subtitle">Ubah hak akses role</p>
            </div>
        </div>

        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Nama Role <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $role->name) }}" required placeholder="Masukkan nama role">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="form-label">Permissions <span class="text-danger">*</span></label>
                <p class="text-muted" style="font-size: 13px; margin-bottom: 16px;">Pilih hak akses yang dimiliki role ini</p>

                @foreach($permissions ?? [] as $group => $groupPerms)
                <div class="mb-4">
                    <h6 style="color: var(--emerald-light); font-size: 14px; margin-bottom: 12px;">{{ $group }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($groupPerms as $perm)
                        <label class="d-flex align-items-center gap-2" style="cursor: pointer; padding: 8px 14px; background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: 8px; transition: all var(--transition-fast);">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                   style="accent-color: var(--emerald); width: 16px; height: 16px;"
                                   {{ in_array($perm->name, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}>
                            <span style="font-size: 13px; color: var(--text-secondary);">{{ $perm->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @error('permissions') <div class="text-danger" style="font-size: 13px;">{{ $message }}</div> @enderror
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('roles.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection
