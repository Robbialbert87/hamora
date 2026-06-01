@extends('layouts.app')

@section('title', 'Tambah Kategori - HAMORA')
@section('page-title', 'Tambah Kategori')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Kategori Baru</h2>
                <p class="card-subtitle">Tambahkan kategori baru</p>
            </div>
        </div>

        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama') }}" required placeholder="Masukkan nama kategori">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="4">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection
