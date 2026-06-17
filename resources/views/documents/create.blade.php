@extends('layouts.app')

@section('title', 'Upload Dokumen - HAMORA')
@section('page-title', 'Upload Dokumen')

@section('content')
    <style>
        .upload-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            padding: 32px 24px;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-normal);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            display: block;
            color: inherit;
            height: 100%;
        }
        .upload-card:hover {
            border-color: rgba(5, 150, 105, 0.35);
            box-shadow: 0 8px 32px rgba(5, 150, 105, 0.15);
            transform: translateY(-4px);
            color: inherit;
        }
        .upload-card-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.15), rgba(5, 150, 105, 0.05));
            color: var(--emerald);
            transition: all var(--transition-normal);
        }
        .upload-card:hover .upload-card-icon {
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.25), rgba(5, 150, 105, 0.1));
            transform: scale(1.05);
        }
        .upload-card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .upload-card-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .upload-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            background: linear-gradient(135deg, var(--emerald), var(--emerald-light));
            color: white;
            border: none;
            transition: all var(--transition-fast);
            box-shadow: 0 4px 16px rgba(5, 150, 105, 0.25);
        }
        .upload-card:hover .upload-card-btn {
            box-shadow: 0 8px 24px rgba(5, 150, 105, 0.35);
            transform: translateY(-1px);
        }
        @media (max-width: 768px) {
            .upload-card {
                padding: 24px 16px;
            }
            .upload-card-icon {
                width: 56px;
                height: 56px;
                font-size: 24px;
            }
        }
    </style>

    <section class="content-grid" style="grid-template-columns: 1fr;">
        <div class="glass-card" style="grid-column: span 1;">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Pilih Jenis Upload</h2>
                    <p class="card-subtitle">Pilih jenis dokumen yang akan diupload</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('documents.create.baru') }}" class="upload-card">
                        <div class="upload-card-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="upload-card-title">Upload Dokumen Baru</div>
                        <div class="upload-card-desc">Upload dokumen baru tanpa masa berlaku, seperti kebijakan internal, SOP, atau laporan</div>
                        <span class="upload-card-btn"><i class="fas fa-arrow-right"></i> Pilih</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('documents.create.mou') }}" class="upload-card">
                        <div class="upload-card-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="upload-card-title">Upload MOU (Kerja Sama)</div>
                        <div class="upload-card-desc">Upload dokumen MOU atau perjanjian kerja sama yang memiliki masa berlaku</div>
                        <span class="upload-card-btn"><i class="fas fa-arrow-right"></i> Pilih</span>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('documents.create.update') }}" class="upload-card">
                        <div class="upload-card-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <div class="upload-card-title">Update Dokumen</div>
                        <div class="upload-card-desc">Update atau perbarui dokumen yang sudah tidak berlaku atau dicabut</div>
                        <span class="upload-card-btn"><i class="fas fa-arrow-right"></i> Pilih</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
