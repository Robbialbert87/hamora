@extends('layouts.app')

@section('title', $document->nama_dokumen . ' - HAMORA')
@section('page-title', $document->nama_dokumen)

@section('content')
<div class="row g-4">
    {{-- Left Column: Metadata --}}
    <div class="col-lg-5">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Detail Dokumen</h2>
                    <p class="card-subtitle">Informasi lengkap dokumen</p>
                </div>
            </div>

            @if($document->status === 'direvisi' && $document->latestRevision())
            <div class="alert d-flex align-items-center gap-2" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 12px; padding: 16px; color: var(--info); margin-bottom: 20px;">
                <i class="fas fa-info-circle"></i>
                <div class="flex-grow-1" style="font-size: 14px;">
                    Dokumen ini telah direvisi. Silakan gunakan versi terbaru.
                </div>
                <a href="{{ route('documents.show', $document->latestRevision()->id) }}" class="btn-emerald btn-sm">
                    <i class="fas fa-external-link-alt"></i> Lihat Versi Terbaru
                </a>
            </div>
            @endif

            <div class="detail-label">Nomor Dokumen</div>
            <div class="detail-value">{{ $document->nomor_dokumen }}</div>

            <div class="detail-label">Nama Dokumen</div>
            <div class="detail-value">{{ $document->nama_dokumen }}</div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="detail-label">Bidang</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->bidang->nama ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">Kategori</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->kategori->nama ?? '-' }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="detail-label">Tahun</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->tahun }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">Versi</div>
                    <div class="detail-value" style="font-size: 14px;">v{{ $document->versi ?? '1' }}</div>
                </div>
            </div>

            <div class="mb-3">
                <div class="detail-label">Status</div>
                <div><span class="badge badge-{{ $document->status }}" style="font-size: 14px; padding: 8px 16px;">{{ ucfirst($document->status) }}</span></div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="detail-label">Tanggal Terbit</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->tanggal_terbit ? $document->tanggal_terbit->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">Tanggal Berlaku</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->tanggal_berlaku ? $document->tanggal_berlaku->format('d/m/Y') : '-' }}</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <div class="detail-label">Diupload oleh</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->uploader->name ?? '-' }}</div>
                </div>
                <div class="col-6">
                    <div class="detail-label">Diverifikasi oleh</div>
                    <div class="detail-value" style="font-size: 14px;">{{ $document->verifier->name ?? '-' }}</div>
                </div>
            </div>

            <div class="mb-4">
                <div class="detail-label">Deskripsi</div>
                <div style="font-size: 14px; color: var(--text-secondary);">{{ $document->deskripsi ?? '-' }}</div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('documents.download', $document->id) }}" class="btn-emerald btn-sm">
                    <i class="fas fa-download"></i> Download
                </a>
                @can('edit dokumen')
                <a href="{{ route('documents.edit', $document->id) }}" class="btn-outline-glass btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                @can('verifikasi dokumen')
                <form action="{{ route('documents.verify', $document->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-outline-glass btn-sm">
                        <i class="fas fa-check-circle"></i> Verify
                    </button>
                </form>
                @endcan
                <a href="{{ route('documents.index') }}" class="btn-outline-glass btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Right Column: PDF Preview --}}
    <div class="col-lg-7">
        <div class="glass-card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Preview PDF</h2>
                    <p class="card-subtitle">Pratinjau dokumen</p>
                </div>
            </div>

            <div class="pdf-toolbar" id="pdf-toolbar" style="display: none;">
                <button class="btn-outline-glass btn-sm" id="prev-page"><i class="fas fa-chevron-left"></i></button>
                <span id="page-info">Halaman <span id="current-page">1</span> dari <span id="total-pages">0</span></span>
                <button class="btn-outline-glass btn-sm" id="next-page"><i class="fas fa-chevron-right"></i></button>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn-outline-glass btn-sm" id="zoom-out"><i class="fas fa-search-minus"></i></button>
                    <span id="zoom-level" style="font-size: 14px; color: var(--text-secondary); display: flex; align-items: center;">100%</span>
                    <button class="btn-outline-glass btn-sm" id="zoom-in"><i class="fas fa-search-plus"></i></button>
                </div>
            </div>

            <div class="pdf-container" id="pdf-container">
                <div id="pdf-loading" class="text-center py-5">
                    <div class="spinner-border text-emerald" role="status" style="color: var(--emerald-light);">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat PDF...</p>
                </div>
                <canvas id="pdf-canvas" style="display: none;"></canvas>
                <div id="pdf-error" style="display: none;" class="text-center py-5 text-muted">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3" style="color: var(--coral);"></i>
                    <p>Gagal memuat PDF</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var url = '{{ route("documents.preview", $document->id) }}';
        var pdfDoc = null;
        var pageNum = 1;
        var scale = 1.0;
        var canvas = document.getElementById('pdf-canvas');
        var ctx = canvas.getContext('2d');
        var loading = document.getElementById('pdf-loading');
        var errorEl = document.getElementById('pdf-error');
        var toolbar = document.getElementById('pdf-toolbar');

        function renderPage(num) {
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({ scale: scale });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                return page.render(renderContext).promise;
            }).then(function() {
                document.getElementById('current-page').textContent = num;
                document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
            });
        }

        function loadPDF() {
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdfDoc = pdf;
                document.getElementById('total-pages').textContent = pdf.numPages;
                loading.style.display = 'none';
                canvas.style.display = 'block';
                toolbar.style.display = 'flex';
                renderPage(1);
            }).catch(function(err) {
                console.error('PDF load error:', err);
                loading.style.display = 'none';
                errorEl.style.display = 'block';
            });
        }

        document.getElementById('prev-page').addEventListener('click', function() {
            if (pageNum <= 1) return;
            pageNum--;
            renderPage(pageNum);
        });

        document.getElementById('next-page').addEventListener('click', function() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            renderPage(pageNum);
        });

        document.getElementById('zoom-in').addEventListener('click', function() {
            scale = Math.min(scale + 0.25, 3.0);
            renderPage(pageNum);
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            scale = Math.max(scale - 0.25, 0.5);
            renderPage(pageNum);
        });

        loadPDF();
    });
</script>
@endsection
