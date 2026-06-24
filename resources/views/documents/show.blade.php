@extends('layouts.app')

@section('title', $document->nama_dokumen . ' - HAMORA')
@section('page-title', $document->nama_dokumen)

@section('content')
<style>
    .page-title { max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .badge-digantikan { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
    .badge-digantikan::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 6px; background: #6c757d; box-shadow: 0 0 8px #6c757d; }
</style>
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

            <div class="mb-4">
                <div style="font-weight: 600; font-size: 13px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Riwayat Dokumen</div>
                @forelse($revisionHistory as $i => $doc)
                @php
                    $isLatest = $doc->id === $latestDocId;
                    $isCurrent = $doc->id === $document->id;
                @endphp
                <div style="display: flex; gap: 12px; position: relative; padding-bottom: 16px; {{ $isCurrent ? 'background: rgba(5,150,105,0.06); border-radius: 12px; padding-left: 8px; padding-right: 8px; margin-left: -8px; margin-right: -8px;' : '' }}">
                    @if($isCurrent)
                    <div style="position: absolute; left: 0; top: 4px; bottom: 12px; width: 3px; background: var(--emerald); border-radius: 2px;"></div>
                    @endif
                    <div style="display: flex; flex-direction: column; align-items: center; width: 24px; flex-shrink: 0;">
                        @if($loop->first && $loop->count > 1)
                        <div style="width: 2px; flex: 1; background: var(--glass-border);"></div>
                        @endif
                        @if(!$loop->first && !$loop->last)
                        <div style="width: 2px; flex: 1; background: var(--glass-border);"></div>
                        @endif
                        <div style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0; {{ $doc->status === 'dicabut' ? 'background: rgba(108,117,125,0.25); color: #6c757d;' : ($isLatest ? 'background: var(--emerald); color: #fff;' : ($isCurrent ? 'background: var(--emerald); color: #fff;' : 'background: rgba(108,117,125,0.15); color: #6c757d;')) }}">
                            <i class="fas {{ $doc->status === 'dicabut' ? 'fa-archive' : ($isLatest || $isCurrent ? 'fa-chevron-right' : 'fa-file') }}" style="font-size: 10px;"></i>
                        </div>
                        @if(!$loop->last)
                        <div style="width: 2px; flex: 1; background: var(--glass-border);"></div>
                        @endif
                    </div>
                    <div style="flex: 1; padding-top: 2px;">
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <a href="{{ route('documents.show', $doc->id) }}" style="font-size: 13px; {{ $isCurrent ? 'font-weight: 700; color: var(--emerald);' : ($isLatest ? 'font-weight: 600; color: var(--emerald);' : 'font-weight: 500; color: var(--text-primary);') }} text-decoration: none; overflow-wrap: break-word; word-break: break-word;">{{ $doc->nomor_dokumen }}</a>
                            @if($doc->status === 'dicabut')
                            <span style="font-size: 10px; background: rgba(108,117,125,0.2); color: #6c757d; padding: 1px 8px; border-radius: 8px; font-weight: 600;">Dicabut</span>
                            @elseif($isCurrent)
                            <span style="font-size: 10px; background: rgba(5,150,105,0.15); color: var(--emerald); padding: 1px 8px; border-radius: 8px; font-weight: 600;">Sedang dilihat</span>
                            @elseif($isLatest)
                            <span style="font-size: 10px; background: rgba(5,150,105,0.15); color: var(--emerald); padding: 1px 8px; border-radius: 8px; font-weight: 600;">Saat ini</span>
                            @else
                            <span style="font-size: 10px; background: rgba(108,117,125,0.12); color: #6c757d; padding: 1px 8px; border-radius: 8px; font-weight: 600;">Lama</span>
                            @endif
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary); overflow-wrap: break-word; word-break: break-word;">{{ $doc->nama_dokumen }}</div>
                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px; flex-wrap: wrap;">
                            @if($doc->status === 'dicabut')
                                <span class="badge badge-dicabut" style="font-size: 10px; padding: 2px 8px;">Dicabut</span>
                            @elseif($isCurrent)
                                <span class="badge badge-aktif" style="font-size: 10px; padding: 2px 8px;">Aktif</span>
                            @elseif($isLatest)
                                <span class="badge badge-aktif" style="font-size: 10px; padding: 2px 8px;">Aktif</span>
                            @else
                                <span class="badge badge-digantikan" style="font-size: 10px; padding: 2px 8px;">Digantikan</span>
                                @if(isset($revisionHistory[$i + 1]))
                                <span style="font-size: 11px; color: var(--text-muted);">oleh V{{ $revisionHistory[$i + 1]->versi }}</span>
                                @endif
                            @endif
                            <span style="font-size: 11px; color: var(--text-muted);">v{{ $doc->versi ?? '1' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="font-size: 13px; color: var(--text-muted); padding: 8px 0;">Tidak ada riwayat revisi.</div>
                @endforelse
            </div>

            <div class="detail-label">Nomor Dokumen</div>
            <div class="detail-value">{{ $document->nomor_dokumen }}</div>

            <div class="detail-label">Nama Dokumen</div>
            <div class="detail-value" style="overflow-wrap: break-word; word-break: break-word;">{{ $document->nama_dokumen }}</div>

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
                @php
                    $isDocLatest = $document->id === $latestDocId;
                    $docNext = $document->latestRevision();
                @endphp
                @if($document->status === 'dicabut')
                    <div><span class="badge badge-dicabut" style="font-size: 14px; padding: 8px 16px;">Dicabut</span></div>
                @elseif($isDocLatest)
                    <div><span class="badge badge-aktif" style="font-size: 14px; padding: 8px 16px;">Aktif</span></div>
                @elseif($docNext)
                    <div>
                        <span class="badge badge-digantikan" style="font-size: 14px; padding: 8px 16px;">Digantikan</span>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">oleh V{{ $docNext->versi }} — <a href="{{ route('documents.show', $docNext->id) }}" style="color: var(--emerald); text-decoration: none;">{{ $docNext->nomor_dokumen }}</a></div>
                    </div>
                @else
                    <div><span class="badge badge-{{ $document->status }}" style="font-size: 14px; padding: 8px 16px;">{{ ucfirst($document->status) }}</span></div>
                @endif
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

            @if($document->status === 'dicabut' && $document->tanggal_pencabutan)
            <div class="mb-3 p-3 rounded-3" style="background: rgba(108,117,125,0.1); border: 1px solid rgba(108,117,125,0.2);">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-archive" style="color: var(--text-muted);"></i>
                    <span class="detail-label" style="margin-bottom: 0;">Dicabut pada</span>
                </div>
                <div class="detail-value" style="font-size: 15px; margin-bottom: 0;">
                    {{ $document->tanggal_pencabutan->format('d/m/Y') }}
                </div>
            </div>
            @endif

            <div class="mb-3">
                <div class="detail-label">Diupload oleh</div>
                <div class="detail-value" style="font-size: 14px;">{{ $document->uploader->name ?? '-' }}</div>
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
