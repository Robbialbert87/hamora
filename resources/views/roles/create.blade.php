@extends('layouts.app')

@section('title', 'Tambah Role - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Tambah Role</li>
                </ol>
            </div>
            <h4 class="page-title">Tambah Role</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Form Role Baru</h4>
                <p class="text-muted mb-4">Buat role dengan hak akses tertentu</p>

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Role <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required placeholder="Masukkan nama role">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Permissions <span class="text-danger">*</span></label>
                        <p class="text-muted" style="font-size: 13px; margin-bottom: 16px;">Pilih hak akses yang dimiliki role ini</p>

                        @foreach($permissions ?? [] as $group => $groupPerms)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">{{ $group }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($groupPerms as $perm)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-check-input"
                                           {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $perm->name }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @error('permissions') <div class="text-danger" style="font-size: 13px;">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
