@extends('layouts.app')

@section('title', 'Update Dokumen - Diubah - HAMORA')
@section('page-title', 'Update Dokumen - Diubah')

@section('content')
    <style>
        .form-section {
            display: block;
        }
    </style>

    <section class="content-grid" style="grid-template-columns: 1fr;">
        <div class="glass-card" style="grid-column: span 1;">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Diubah</h2>
                    <p class="card-subtitle">Revisi dokumen dengan perubahan konten secara menyeluruh</p>
                </div>
                <a href="{{ route('documents.create.update') }}" class="btn-outline-glass btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" id="formUpload" novalidate>
                @csrf
                <input type="hidden" name="jenis_upload" value="update">
                <input type="hidden" name="parent_document_id" id="parentDocumentId" value="">

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Pencarian Dokumen --}}
                <div class="form-group mb-4">
                    <label class="form-label">Cari Dokumen yang akan diubah <span class="text-danger">*</span></label>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="filterPencarian"
                                placeholder="Cari Nomor atau Nama Dokumen" autocomplete="off">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="filterBidang">
                                <option value="">Semua Bidang</option>
                                @foreach ($bidang ?? [] as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterKategori">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategori ?? [] as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterTahun">
                                <option value="">Semua Tahun</option>
                                @foreach (range(date('Y') + 1, date('Y') - 10) as $thn)
                                    <option value="{{ $thn }}">{{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="button" class="btn-emerald btn-sm flex-fill"
                                onclick="filterDocuments()"><i class="fas fa-search"></i> Cari</button>
                            <button type="button" class="btn-outline-glass btn-sm flex-fill"
                                onclick="resetFilter()"><i class="fas fa-undo"></i> Reset</button>
                        </div>
                    </div>
                    <div id="documentList"
                        style="max-height: 260px; overflow-y: auto; border: 1px solid var(--glass-border); border-radius: 12px; display: none;">
                        <div id="filterEmpty" class="px-3 py-3 text-muted text-center"
                            style="font-size: 14px; display: none;">Tidak ada dokumen yang cocok dengan pencarian.</div>
                        @forelse($documents ?? [] as $d)
                            <div class="document-item px-3 py-2" data-id="{{ $d->id }}"
                                data-nomor="{{ strtolower(e($d->nomor_dokumen)) }}"
                                data-nama="{{ strtolower(e($d->nama_dokumen)) }}"
                                data-bidang="{{ $d->bidang_id }}"
                                data-kategori="{{ $d->kategori_id }}"
                                data-tahun="{{ $d->tahun }}"
                                data-tanggal-berlaku="{{ $d->tanggal_berlaku ? $d->tanggal_berlaku->format('Y-m-d') : '' }}"
                                style="cursor: pointer; border-bottom: 1px solid var(--glass-border); transition: background 0.15s;"
                                onclick="selectDoc({{ $d->id }}, '{{ e($d->nomor_dokumen) }} - {{ e($d->nama_dokumen) }}')">
                                <div class="fw-medium" style="font-size: 14px;">
                                    {{ $d->nomor_dokumen }} - {{ $d->nama_dokumen }}</div>
                                <small class="text-muted">{{ $d->bidang?->nama ?? '-' }} |
                                    {{ $d->kategori?->nama ?? '-' }} | {{ $d->tahun }}</small>
                            </div>
                        @empty
                            <div class="px-3 py-3 text-muted text-center" style="font-size: 14px;">Tidak ada dokumen yang tersedia untuk diupdate.</div>
                        @endforelse
                    </div>
                    <div id="selectedDoc" class="mt-2" style="display: none;">
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3"
                            style="background: rgba(5,150,105,0.1);">
                            <i class="fas fa-check-circle text-success"></i>
                            <span id="selectedDocText" class="text-success fw-medium" style="font-size: 14px;"></span>
                            <button type="button" class="btn btn-sm ms-auto"
                                onclick="clearDoc()"
                                style="background: none; border: none; color: var(--text-muted); padding: 4px 8px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Hidden fields (auto-filled from parent document) --}}
                <input type="hidden" name="bidang_id" id="hiddenBidangId" value="">
                <input type="hidden" name="kategori_id" id="hiddenKategoriId" value="">
                <input type="hidden" name="tanggal_berlaku" id="hiddenTanggalBerlaku" value="">
                <input type="hidden" name="status" value="aktif">

                {{-- Form Fields --}}
                <div id="sectionUpdate" class="form-section active">
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.2); color: var(--text-primary);">
                        <i class="fas fa-info-circle text-emerald"></i>
                        <span>Bidang, Kategori, dan Tanggal Berlaku akan otomatis mengikuti dokumen yang dipilih.</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_dokumen"
                                    class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                    value="{{ old('nomor_dokumen') }}" placeholder="Otomatis jika dikosongkan">
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
                                    value="{{ old('nama_dokumen') }}" required placeholder="Nama update dokumen">
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
                                            {{ old('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
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
                                <p class="form-control-plaintext" id="displayBidang" style="padding: 12px 18px;">-</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <p class="form-control-plaintext" id="displayKategori" style="padding: 12px 18px;">-</p>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Berlaku</label>
                                <p class="form-control-plaintext" id="displayTanggalBerlaku" style="padding: 12px 18px;">-</p>
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
                                <div class="text-muted mt-1" style="font-size: 12px;">Format: PDF, Maks: 20MB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <button type="submit" class="btn-emerald"><i class="fas fa-save"></i> Simpan Update</button>
                    <a href="{{ route('documents.create.update') }}" class="btn-outline-glass"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function filterDocuments() {
            var pencarian = document.getElementById('filterPencarian').value.toLowerCase().trim();
            var bidang = document.getElementById('filterBidang').value;
            var kategori = document.getElementById('filterKategori').value;
            var tahun = document.getElementById('filterTahun').value;

            document.getElementById('documentList').style.display = '';

            var items = document.querySelectorAll('.document-item');
            var hasVisible = false;
            items.forEach(function(it) {
                var match = true;
                if (pencarian && !it.getAttribute('data-nomor').includes(pencarian) && !it.getAttribute('data-nama')
                    .includes(pencarian)) match = false;
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
            document.getElementById('filterPencarian').value = '';
            document.getElementById('filterBidang').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('parentDocumentId').value = '';
            document.getElementById('selectedDoc').style.display = 'none';
            document.getElementById('documentList').style.display = 'none';
            var empty = document.getElementById('filterEmpty');
            if (empty) empty.style.display = 'none';
            resetDocInfo();
        }

        function populateDocInfo(item) {
            var bidangId = item.getAttribute('data-bidang');
            var kategoriId = item.getAttribute('data-kategori');
            var tglBerlaku = item.getAttribute('data-tanggal-berlaku');

            document.getElementById('hiddenBidangId').value = bidangId;
            document.getElementById('hiddenKategoriId').value = kategoriId;
            document.getElementById('hiddenTanggalBerlaku').value = tglBerlaku;

            var bidangMap = {};
            var kategoriMap = {};
            @foreach ($bidang ?? [] as $b)
                bidangMap['{{ $b->id }}'] = '{{ $b->nama }}';
            @endforeach
            @foreach ($kategori ?? [] as $k)
                kategoriMap['{{ $k->id }}'] = '{{ $k->nama }}';
            @endforeach

            document.getElementById('displayBidang').textContent = bidangMap[bidangId] || '-';
            document.getElementById('displayKategori').textContent = kategoriMap[kategoriId] || '-';
            document.getElementById('displayTanggalBerlaku').textContent = tglBerlaku ? tglBerlaku.split('-').reverse().join('/') : '-';
        }

        function resetDocInfo() {
            document.getElementById('hiddenBidangId').value = '';
            document.getElementById('hiddenKategoriId').value = '';
            document.getElementById('hiddenTanggalBerlaku').value = '';
            document.getElementById('displayBidang').textContent = '-';
            document.getElementById('displayKategori').textContent = '-';
            document.getElementById('displayTanggalBerlaku').textContent = '-';
        }

        function selectDoc(id, text) {
            document.getElementById('parentDocumentId').value = id;
            document.getElementById('selectedDocText').textContent = text;
            document.getElementById('selectedDoc').style.display = '';
            document.querySelectorAll('.document-item').forEach(function(it) {
                it.style.display = 'none';
            });

            var selected = document.querySelector('.document-item[data-id="' + id + '"]');
            if (selected) populateDocInfo(selected);
        }

        function clearDoc() {
            document.getElementById('parentDocumentId').value = '';
            document.getElementById('selectedDoc').style.display = 'none';
            document.querySelectorAll('.document-item').forEach(function(it) {
                it.style.display = '';
            });
            resetDocInfo();
        }

        function attachFilters() {
            var ids = ['filterPencarian', 'filterBidang', 'filterKategori', 'filterTahun'];
            ids.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.addEventListener('input', filterDocuments);
                if (el && el.tagName === 'SELECT') el.addEventListener('change', filterDocuments);
            });
        }

        attachFilters();
    </script>
@endsection
