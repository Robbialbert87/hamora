@extends('layouts.app')

@section('title', 'Edit Kategori - HAMORA')
@section('page-title', 'Edit Kategori')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Edit Kategori</h2>
                <p class="card-subtitle">Ubah data kategori</p>
            </div>
        </div>

        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $kategori->nama) }}" required placeholder="Masukkan nama kategori">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="4">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3">
                <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Update</button>
                <a href="{{ route('kategori.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
</section>
@endsection
