@extends('layouts.app')

@section('title', 'Upload Dokumen - HAMORA')
@section('page-title', 'Upload Dokumen')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Form Upload Dokumen</h2>
                <p class="card-subtitle">Pilih jenis upload dan lengkapi form di bawah</p>
            </div>
        </div>

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="formUpload">
            @csrf
            <input type="hidden" name="jenis_upload" id="jenisUpload" value="baru">
            <input type="hidden" name="parent_document_id" id="parentDocumentId" value="">

            <div class="accordion" id="uploadAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBaru" aria-expanded="false">
                            <div class="d-flex align-items-center gap-3 w-100">
                                <div class="accordion-icon">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Upload Dokumen Baru</div>
                                    <small class="text-muted">Upload dokumen baru tanpa masa berlaku</small>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseBaru" class="accordion-collapse collapse" data-bs-parent="#uploadAccordion">
                        <div class="accordion-body"></div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMOU">
                            <div class="d-flex align-items-center gap-3 w-100">
                                <div class="accordion-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Upload MOU (Kerja Sama)</div>
                                    <small class="text-muted">Upload dokumen MOU dengan masa berlaku</small>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseMOU" class="accordion-collapse collapse" data-bs-parent="#uploadAccordion">
                        <div class="accordion-body"></div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUpdate">
                            <div class="d-flex align-items-center gap-3 w-100">
                                <div class="accordion-icon">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Update Dokumen</div>
                                    <small class="text-muted">Update dokumen yang sudah tidak berlaku atau dicabut</small>
                                </div>
                            </div>
                        </button>
                    </h2>
                    <div id="collapseUpdate" class="accordion-collapse collapse" data-bs-parent="#uploadAccordion">
                        <div class="accordion-body"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

<template id="templateBaru">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror" value="{{ old('nomor_dokumen') }}" required placeholder="Contoh: 001/HAMORA/2024">
                @error('nomor_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" value="{{ old('nama_dokumen') }}" required placeholder="Nama lengkap dokumen">
                @error('nama_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                    @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                    <option value="{{ $thn }}" {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Bidang</label>
                <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                    <option value="">Pilih Bidang</option>
                    @foreach($bidang ?? [] as $b)
                    <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                    @endforeach
                </select>
                @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori ?? [] as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror" value="{{ old('tanggal_terbit') }}">
                @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">File PDF <span class="text-danger">*</span></label>
                <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror" accept="application/pdf" required>
                @error('file_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB</div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="draft">Draft</option>
                    <option value="aktif">Aktif</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan Dokumen Baru</button>
        <a href="{{ route('documents.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</template>

<template id="templateMOU">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror" value="{{ old('nomor_dokumen') }}" required placeholder="Contoh: MOU/001/HAMORA/2024">
                @error('nomor_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" value="{{ old('nama_dokumen') }}" required placeholder="Nama lengkap dokumen MOU">
                @error('nama_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                    @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                    <option value="{{ $thn }}" {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Bidang</label>
                <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                    <option value="">Pilih Bidang</option>
                    @foreach($bidang ?? [] as $b)
                    <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                    @endforeach
                </select>
                @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori ?? [] as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror" value="{{ old('tanggal_terbit') }}">
                @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Tanggal Berlaku <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_berlaku" class="form-control @error('tanggal_berlaku') is-invalid @enderror" value="{{ old('tanggal_berlaku') }}">
                @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">File PDF <span class="text-danger">*</span></label>
                <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror" accept="application/pdf" required>
                @error('file_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB</div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="draft">Draft</option>
                    <option value="aktif">Aktif</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan MOU</button>
        <a href="{{ route('documents.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</template>

<template id="templateUpdate">
    <div class="row g-4">
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Cari Dokumen yang akan di Update <span class="text-danger">*</span></label>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="filterPencarian" placeholder="Cari Nomor atau Nama Dokumen" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="filterBidang">
                            <option value="">Semua Bidang</option>
                            @foreach($bidang ?? [] as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filterKategori">
                            <option value="">Semua Kategori</option>
                            @foreach($kategori ?? [] as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filterTahun">
                            <option value="">Semua Tahun</option>
                            @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                            <option value="{{ $thn }}">{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="button" class="btn-emerald btn-sm flex-fill" onclick="filterDocuments()"><i class="fas fa-search"></i> Cari</button>
                        <button type="button" class="btn-outline-glass btn-sm flex-fill" onclick="resetFilter()"><i class="fas fa-undo"></i> Reset</button>
                    </div>
                </div>
                <div id="documentList" style="max-height: 260px; overflow-y: auto; border: 1px solid var(--glass-border); border-radius: 12px; display: none;">
                    <div id="filterEmpty" class="px-3 py-3 text-muted text-center" style="font-size: 14px; display: none;">Tidak ada dokumen yang cocok dengan pencarian.</div>
                    @forelse($documents ?? [] as $d)
                    <div class="document-item px-3 py-2" data-id="{{ $d->id }}" data-nomor="{{ strtolower(e($d->nomor_dokumen)) }}" data-nama="{{ strtolower(e($d->nama_dokumen)) }}" data-bidang="{{ $d->bidang_id }}" data-kategori="{{ $d->kategori_id }}" data-tahun="{{ $d->tahun }}" style="cursor: pointer; border-bottom: 1px solid var(--glass-border); transition: background 0.15s;" onclick="selectDoc({{ $d->id }}, '{{ e($d->nomor_dokumen) }} - {{ e($d->nama_dokumen) }}')">
                        <div class="fw-medium" style="font-size: 14px;">{{ $d->nomor_dokumen }} - {{ $d->nama_dokumen }}</div>
                        <small class="text-muted">{{ $d->bidang?->nama ?? '-' }} | {{ $d->kategori?->nama ?? '-' }} | {{ $d->tahun }}</small>
                    </div>
                    @empty
                    <div class="px-3 py-3 text-muted text-center" style="font-size: 14px;">Tidak ada dokumen yang tersedia untuk diupdate.</div>
                    @endforelse
                </div>
                <div id="selectedDoc" class="mt-2" style="display: none;">
                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: rgba(5,150,105,0.1);">
                        <i class="fas fa-check-circle text-success"></i>
                        <span id="selectedDocText" class="text-success fw-medium" style="font-size: 14px;"></span>
                        <button type="button" class="btn btn-sm ms-auto" onclick="clearDoc()" style="background: none; border: none; color: var(--text-muted); padding: 4px 8px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nomor_dokumen" class="form-control @error('nomor_dokumen') is-invalid @enderror" value="{{ old('nomor_dokumen') }}" required placeholder="Otomatis jika dikosongkan">
                @error('nomor_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" value="{{ old('nama_dokumen') }}" required placeholder="Nama update dokumen">
                @error('nama_dokumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select @error('tahun') is-invalid @enderror">
                    @foreach(range(date('Y') + 1, date('Y') - 10) as $thn)
                    <option value="{{ $thn }}" {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
                @error('tahun') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Bidang</label>
                <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
                    <option value="">Pilih Bidang</option>
                    @foreach($bidang ?? [] as $b)
                    <option value="{{ $b->id }}" {{ old('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                    @endforeach
                </select>
                @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategori ?? [] as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_terbit" class="form-control @error('tanggal_terbit') is-invalid @enderror" value="{{ old('tanggal_terbit') }}">
                @error('tanggal_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Tanggal Berlaku</label>
                <input type="date" name="tanggal_berlaku" class="form-control @error('tanggal_berlaku') is-invalid @enderror" value="{{ old('tanggal_berlaku') }}">
                @error('tanggal_berlaku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">File PDF <span class="text-danger">*</span></label>
                <input type="file" name="file_pdf" class="form-control @error('file_pdf') is-invalid @enderror" accept="application/pdf" required>
                @error('file_pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB</div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="draft">Draft</option>
                    <option value="aktif">Aktif</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex gap-3">
        <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan Update</button>
        <a href="{{ route('documents.index') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</template>

@section('scripts')
<style>
.accordion {
    --bs-accordion-bg: transparent;
    --bs-accordion-border-color: var(--glass-border);
    --bs-accordion-border-radius: 16px;
    --bs-accordion-btn-bg: transparent;
    --bs-accordion-btn-color: var(--text-primary);
    --bs-accordion-active-bg: transparent;
    --bs-accordion-active-color: var(--emerald);
    --bs-accordion-body-bg: transparent;
    --bs-accordion-btn-focus-box-shadow: none;
    --bs-accordion-btn-padding-y: 16px;
    --bs-accordion-btn-padding-x: 20px;
    --bs-accordion-body-padding-y: 16px;
    --bs-accordion-body-padding-x: 20px;
}
.accordion-item {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius) !important;
    margin-bottom: 16px;
    overflow: hidden;
    transition: all var(--transition-normal);
}
.accordion-item:hover {
    background: var(--glass-hover);
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 25px 50px -12px var(--glass-shadow);
}
.accordion-button {
    background: transparent !important;
    color: var(--text-primary) !important;
    font-weight: 500;
    font-size: 15px;
    box-shadow: none !important;
}
.accordion-button:not(.collapsed) {
    background: transparent !important;
    color: var(--emerald) !important;
}
.accordion-button::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23059669' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    transition: transform var(--transition-normal);
}
.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23059669' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
}
.accordion-body {
    padding: var(--bs-accordion-body-padding-y) var(--bs-accordion-body-padding-x);
    border-top: 1px solid var(--glass-border);
}
.accordion-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: linear-gradient(135deg, rgba(5, 150, 105, 0.15), rgba(5, 150, 105, 0.05));
    color: var(--emerald);
    flex-shrink: 0;
}
.accordion-button:not(.collapsed) .accordion-icon {
    background: linear-gradient(135deg, rgba(5, 150, 105, 0.25), rgba(5, 150, 105, 0.1));
}
.text-gold { color: var(--gold); }
.text-emerald { color: var(--emerald); }
</style>
<script>
    var isResetting = false;

    function selectDoc(id, text) {
        document.getElementById('parentDocumentId').value = id;
        document.getElementById('selectedDocText').textContent = text;
        document.getElementById('selectedDoc').style.display = '';
        document.querySelectorAll('.document-item').forEach(function(it) { it.style.display = 'none'; });
    }

    function clearDoc() {
        document.getElementById('parentDocumentId').value = '';
        document.getElementById('selectedDoc').style.display = 'none';
        document.querySelectorAll('.document-item').forEach(function(it) { it.style.display = ''; });
    }

    function filterDocuments() {
        if (isResetting) return;
        var pencarian = document.getElementById('filterPencarian').value.toLowerCase().trim();
        var bidang = document.getElementById('filterBidang').value;
        var kategori = document.getElementById('filterKategori').value;
        var tahun = document.getElementById('filterTahun').value;

        document.getElementById('documentList').style.display = '';

        var items = document.querySelectorAll('.document-item');
        var hasVisible = false;
        items.forEach(function(it) {
            var match = true;
            if (pencarian && !it.getAttribute('data-nomor').includes(pencarian) && !it.getAttribute('data-nama').includes(pencarian)) match = false;
            if (bidang && it.getAttribute('data-bidang') !== bidang) match = false;
            if (kategori && it.getAttribute('data-kategori') !== kategori) match = false;
            if (tahun && it.getAttribute('data-tahun') !== tahun) match = false;
            it.style.display = match ? '' : 'none';
            if (match) hasVisible = true;
        });

        var empty = document.getElementById('filterEmpty');
        if (empty) empty.style.display = hasVisible ? 'none' : '';

        var sel = document.getElementById('selectedDoc');
        if (sel && sel.style.display !== 'none') {
            var pid = document.getElementById('parentDocumentId').value;
            var stillVisible = false;
            items.forEach(function(it) {
                if (it.getAttribute('data-id') === pid && it.style.display !== 'none') stillVisible = true;
            });
            if (!stillVisible) clearDoc();
        }
    }

    function resetFilter() {
        isResetting = true;
        document.getElementById('filterPencarian').value = '';
        document.getElementById('filterBidang').value = '';
        document.getElementById('filterKategori').value = '';
        document.getElementById('filterTahun').value = '';
        document.getElementById('parentDocumentId').value = '';
        document.getElementById('selectedDoc').style.display = 'none';
        document.getElementById('documentList').style.display = 'none';
        var empty = document.getElementById('filterEmpty');
        if (empty) empty.style.display = 'none';
        isResetting = false;
    }

    function loadSection(targetId) {
        document.querySelectorAll('#uploadAccordion .accordion-body').forEach(function(b) { b.innerHTML = ''; });
        var tpl = document.getElementById(targetId.replace('collapse', 'template'));
        var body = document.querySelector('#' + targetId + ' .accordion-body');
        if (tpl && body) body.appendChild(tpl.content.cloneNode(true));
    }

    function attachFilters() {
        var ids = ['filterPencarian', 'filterBidang', 'filterKategori', 'filterTahun'];
        ids.forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('input', filterDocuments);
            if (el && el.tagName === 'SELECT') el.addEventListener('change', filterDocuments);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var accordion = document.getElementById('uploadAccordion');
        if (accordion) {
            accordion.addEventListener('show.bs.collapse', function(e) {
                var id = e.target.id;
                var jenis = document.getElementById('jenisUpload');
                if (id === 'collapseBaru') jenis.value = 'baru';
                else if (id === 'collapseMOU') jenis.value = 'mou';
                else if (id === 'collapseUpdate') jenis.value = 'update';
                loadSection(id);
                if (id === 'collapseUpdate') attachFilters();
            });
        }
    });
</script>
@endsection