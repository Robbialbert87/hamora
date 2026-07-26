@extends('layouts.app')

@section('title', $document->nama_dokumen . ' - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">{{ Str::limit($document->nama_dokumen, 30) }}</li>
                </ol>
            </div>
            <h4 class="page-title text-truncate" style="max-width: 350px;">{{ $document->nama_dokumen }}</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Left Column: Metadata --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Detail Dokumen</h4>
                <p class="text-muted mb-4">Informasi lengkap dokumen</p>

            {{-- Riwayat Dokumen --}}
            @if(count($revisionHistory) > 0)
            <div class="mb-0">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title mb-0">Riwayat Revisi</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ count($revisionHistory) }} versi</span>
                </div>

                <div class="revision-timeline">
                    @php
                        $visibleCount = 3;
                        $totalRevisions = count($revisionHistory);
                        $hideUntil = $totalRevisions - $visibleCount;
                    @endphp
                    @foreach($revisionHistory as $idx => $doc)
                    @php
                        $isLatest = $doc->id === $latestDocId;
                        $isCurrent = $doc->id === $document->id;
                        $isLastAll = $idx === $totalRevisions - 1;
                        $isHidden = $idx < $hideUntil;
                        $statusIcons = [
                            'aktif'     => 'ti ti-circle-check',
                            'draft'     => 'ti ti-pencil',
                            'direvisi'  => 'ti ti-git-branch',
                            'diubah'    => 'ti ti-refresh',
                            'kadaluarsa'=> 'ti ti-clock',
                            'dicabut'   => 'ti ti-circle-x',
                        ];
                        $icon = $statusIcons[$doc->status] ?? 'ti ti-file';
                    @endphp
                    <a href="{{ route('documents.show', $doc->id) }}" class="revision-item {{ $isCurrent ? 'current' : '' }} {{ ($isLastAll && !$isHidden) ? 'is-last' : '' }}" @if($isHidden) style="display:none;" id="revision-extra-{{ $idx }}" @endif>
                        <div class="revision-node">
                            <div class="revision-icon {{ $isCurrent ? 'active' : ($doc->status === 'aktif' ? 'success' : ($doc->status === 'dicabut' ? 'danger' : 'default')) }}">
                                <i class="{{ $icon }}"></i>
                            </div>
                            @if(!$isLastAll)
                            <div class="revision-line {{ $doc->status === 'aktif' ? 'line-success' : '' }}"></div>
                            @endif
                        </div>
                        <div class="revision-content">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-semibold {{ $isCurrent ? 'text-primary' : 'text-dark' }}" style="font-size: 13px;">v{{ $doc->versi ?? 1 }}</span>
                                <span class="text-muted" style="font-size: 12px;">{{ $doc->nomor_dokumen }}</span>
                                @if($isCurrent)
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size: 10px;">Sedang Dilihat</span>
                                @elseif($isLatest)
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">Aktif</span>
                                @elseif($doc->status === 'dicabut')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 10px;">Dicabut</span>
                                @endif
                            </div>
                            <p class="mb-1 text-muted" style="font-size: 12px;">{{ $doc->nama_dokumen }}</p>
                            <div class="d-flex align-items-center gap-3" style="font-size: 11.5px;">
                                @if($doc->uploader)
                                <span class="text-muted"><i class="ti ti-user" style="font-size: 12px;"></i> {{ $doc->uploader->name }}</span>
                                @endif
                                <span class="text-muted"><i class="ti ti-calendar" style="font-size: 12px;"></i> {{ $doc->updated_at ? $doc->updated_at->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @if($totalRevisions > $visibleCount)
                <button type="button" id="btn-toggle-revision" class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="toggleRevisionHistory()">
                    <i class="ti ti-chevron-down me-1"></i> Lihat Semua Riwayat ({{ $totalRevisions }} versi)
                </button>
                @endif
            </div>
            @endif

            <hr class="my-4">

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Nomor Dokumen</label>
                    <span class="fw-medium">{{ $document->nomor_dokumen }}</span>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Nama Dokumen</label>
                    <span class="fw-medium" style="word-break: break-word;">{{ $document->nama_dokumen }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Bidang</label>
                        <span class="fw-medium">{{ $document->bidang->nama ?? '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Kategori</label>
                        <span class="fw-medium">{{ $document->kategori->nama ?? '-' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tahun</label>
                        <span class="fw-medium">{{ $document->tahun }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Versi</label>
                        <span class="fw-medium">v{{ $document->versi ?? '1' }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Status</label>
                    <span class="badge badge-{{ $document->status }}" style="font-size: 13px; padding: 6px 12px;">{{ ucfirst($document->status) }}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tanggal Terbit</label>
                        <span class="fw-medium">{{ $document->tanggal_terbit ? $document->tanggal_terbit->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted text-uppercase small d-block mb-1">Tanggal Berlaku</label>
                        <span class="fw-medium">{{ $document->tanggal_berlaku ? $document->tanggal_berlaku->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>

                @if($document->status === 'dicabut' && $document->tanggal_pencabutan)
                <div class="mb-3 p-3 bg-light rounded">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="ti ti-archive text-muted"></i>
                        <small class="text-muted">Dicabut pada</small>
                    </div>
                    <span class="fw-medium">{{ $document->tanggal_pencabutan->format('d/m/Y') }}</span>
                </div>
                @endif

                <div class="mb-3">
                    <label class="text-muted text-uppercase small d-block mb-1">Diupload oleh</label>
                    <span class="fw-medium">{{ $document->uploader->name ?? '-' }}</span>
                </div>

                <div class="mb-4">
                    <label class="text-muted text-uppercase small d-block mb-1">Deskripsi</label>
                    <span>{{ $document->deskripsi ?? '-' }}</span>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('documents.download', $document->id) }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-download me-1"></i> Download
                    </a>
                    @can('edit dokumen')
                    <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-pencil me-1"></i> Edit
                    </a>
                    @endcan
                    @can('upload dokumen')
                    @if(in_array($document->status, ['aktif', 'kadaluarsa', 'direvisi']))
                    <div class="dropdown">
                        <button class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-refresh me-1"></i> Revisi
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('documents.create.update') }}"><i class="ti ti-pencil me-2"></i>Pilih Jenis Revisi</a></li>
                        </ul>
                    </div>
                    @endif
                    @endcan
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: PDF Preview --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Preview PDF</h4>
                <p class="text-muted mb-3">Pratinjau dokumen</p>

                <div class="pdf-toolbar d-none" id="pdf-toolbar">
                    <button class="btn btn-outline-secondary btn-sm" id="prev-page"><i class="ti ti-chevron-left"></i></button>
                    <span id="page-info">Halaman <span id="current-page">1</span> dari <span id="total-pages">0</span></span>
                    <button class="btn btn-outline-secondary btn-sm" id="next-page"><i class="ti ti-chevron-right"></i></button>
                    <div class="ms-auto d-flex gap-2 align-items-center">
                        <button class="btn btn-outline-secondary btn-sm" id="zoom-out"><i class="ti ti-minus"></i></button>
                        <span id="zoom-level" class="small text-muted">100%</span>
                        <button class="btn btn-outline-secondary btn-sm" id="zoom-in"><i class="ti ti-plus"></i></button>
                    </div>
                </div>

                <div class="pdf-container" id="pdf-container">
                    <div id="pdf-loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat PDF...</p>
                    </div>
                    <canvas id="pdf-canvas" style="display: none;"></canvas>
                    <div id="pdf-error" style="display: none;" class="text-center py-5 text-muted">
                        <i class="ti ti-alert-triangle font-48 mb-3 text-warning"></i>
                        <p>Gagal memuat PDF</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleRevisionHistory() {
        var btn = document.getElementById('btn-toggle-revision');
        var extras = document.querySelectorAll('[id^="revision-extra-"]');
        var isExpanded = extras.length > 0 && extras[0].style.display !== 'none';

        extras.forEach(function(el) {
            el.style.display = isExpanded ? 'none' : '';
        });

        if (isExpanded) {
            btn.innerHTML = '<i class="ti ti-chevron-down me-1"></i> Lihat Semua Riwayat ({{ $totalRevisions }} versi)';
        } else {
            btn.innerHTML = '<i class="ti ti-chevron-up me-1"></i> Tutup Riwayat';
        }
    }

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
                toolbar.classList.remove('d-none');
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
