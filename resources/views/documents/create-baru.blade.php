@extends('layouts.app')

@section('title', 'Upload Dokumen Baru - HAMORA')
@section('page-title', 'Upload Dokumen Baru')

@section('content')
    <section class="content-grid" style="grid-template-columns: 1fr;">
        <div class="glass-card" style="grid-column: span 1;">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Form Upload Dokumen Baru</h2>
                    <p class="card-subtitle">Lengkapi form di bawah untuk upload dokumen baru</p>
                </div>
            </div>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="jenis_upload" value="baru">

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_dokumen"
                                class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                value="{{ old('nomor_dokumen') }}" required
                                placeholder="Contoh: 001/HAMORA/2024">
                            @error('nomor_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="nama_dokumen"
                                class="form-control @error('nama_dokumen') is-invalid @enderror"
                                value="{{ old('nama_dokumen') }}" required
                                placeholder="Nama lengkap dokumen">
                            @error('nama_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                                @foreach (range(date('Y') + 1, date('Y') - 10) as $thn)
                                    <option value="{{ $thn }}"
                                        {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Bidang</label>
                            <select name="bidang_id"
                                class="form-select @error('bidang_id') is-invalid @enderror">
                                <option value="">Pilih Bidang</option>
                                @foreach ($bidang ?? [] as $b)
                                    <option value="{{ $b->id }}"
                                        {{ old('bidang_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama }}</option>
                                @endforeach
                            </select>
                            @error('bidang_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <select name="kategori_id"
                                class="form-select @error('kategori_id') is-invalid @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori ?? [] as $k)
                                    <option value="{{ $k->id }}"
                                        {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_terbit"
                                class="form-control @error('tanggal_terbit') is-invalid @enderror"
                                value="{{ old('tanggal_terbit') }}">
                            @error('tanggal_terbit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">File PDF <span class="text-danger">*</span></label>
                            <input type="file" name="file_pdf"
                                class="form-control @error('file_pdf') is-invalid @enderror"
                                accept="application/pdf" required>
                            @error('file_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status"
                                class="form-select @error('status') is-invalid @enderror">
                                <option value="draft">Draft</option>
                                <option value="aktif">Aktif</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan Dokumen Baru</button>
                    <a href="{{ route('documents.create') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection
