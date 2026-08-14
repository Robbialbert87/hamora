@extends('layouts.app')

@section('title', $rekapBukti->nama . ' - HAMORA')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bukti.index') }}">Pengumpulan Data</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $rekapBukti->nama }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h5 class="card-title mb-0">{{ $rekapBukti->nama }}</h5>
                            @if ($rekapBukti->status === 'aktif')
                                <span class="badge bg-success" id="status-badge">Link Aktif</span>
                            @else
                                <span class="badge bg-secondary" id="status-badge">Link Nonaktif</span>
                            @endif
                        </div>
                        <p class="text-muted mb-2" style="font-size: 13px;">@if ($rekapBukti->deskripsi){!! nl2br(e($rekapBukti->deskripsi)) !!}@else Tidak ada deskripsi @endif</p>
                        <p class="text-muted mb-0" style="font-size: 12px;">
                            <i class="ti ti-user me-1"></i>{{ $rekapBukti->creator?->name ?? '-' }} ·
                            <i class="ti ti-clock me-1"></i>{{ $rekapBukti->created_at->format('d/m/Y H:i') }} ·
                            <i class="ti ti-hash me-1"></i>{{ $rekapBukti->fields->count() }} kolom
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex gap-1 justify-content-md-end">
                            @if ($stats['total_entri'] > 0)
                            <a href="{{ route('bukti.report', $rekapBukti->id) }}" class="btn btn-light-danger btn-icon" style="flex: 0 0 auto;" title="Unduh Laporan PDF"><i class="ti ti-file-text"></i></a>
                            @endif
                            @if ($stats['total_file'] > 0)
                            <a href="{{ route('bukti.files.zip', $rekapBukti->id) }}" class="btn btn-light-primary btn-icon" style="flex: 0 0 auto;" title="Unduh Semua Bukti (ZIP)"><i class="ti ti-file-zip"></i></a>
                            @endif
                            @can('kelola bukti')
                            <a href="{{ route('bukti.edit', $rekapBukti->id) }}" class="btn btn-light-warning btn-icon" style="flex: 0 0 auto;" title="Edit"><i class="ti ti-pencil"></i></a>
                            <button class="btn btn-light-danger btn-icon btn-delete-rekap" style="flex: 0 0 auto;" data-url="{{ route('bukti.destroy', $rekapBukti->id) }}" data-name="{{ $rekapBukti->nama }}" title="Hapus"><i class="ti ti-trash"></i></button>
                            @endcan
                        </div>
                    </div>
                </div>

                <hr>

                <label class="form-label fw-semibold mb-1">Link Publik (tanpa login)</label>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" id="public-link" value="{{ route('bukti.publik.form', $rekapBukti->token) }}" readonly>
                    <button class="btn btn-primary btn-sm btn-icon" type="button" id="btn-copy-link" title="Salin Link"><i class="ti ti-copy"></i></button>
                    <a class="btn btn-outline-secondary btn-sm btn-icon" href="{{ route('bukti.publik.form', $rekapBukti->token) }}" target="_blank" title="Buka di tab baru"><i class="ti ti-external-link"></i></a>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="toggle-status" data-url="{{ route('bukti.toggle', $rekapBukti->id) }}" {{ $rekapBukti->status === 'aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="toggle-status" style="font-size: 13px; font-weight: 500;">Terima data publik</label>
                    </div>
                    <small class="text-muted" style="font-size: 12px;" id="toggle-hint">
                        @if ($rekapBukti->status === 'aktif')
                            Link terbuka untuk menerima data dari siapapun.
                        @else
                            Link ditutup, pengunjung tidak bisa mengisi data.
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background:#eff2ff; color:#556ee5;">
                        <i class="ti ti-file-text" style="font-size: 20px;"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0" style="font-size: 12px;">Total Entri</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['total_entri'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background:#fff3ec; color:#f07c3c;">
                        <i class="ti ti-file" style="font-size: 20px;"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0" style="font-size: 12px;">Total File</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['total_file'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center" style="background:#ecfdf3; color:#1ab45d;">
                        <i class="ti ti-clock" style="font-size: 20px;"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0" style="font-size: 12px;">Terakhir Diisi</p>
                        <h4 class="mb-0 fw-bold" style="font-size: 18px;">{{ $stats['terakhir'] ? \Carbon\Carbon::parse($stats['terakhir'])->format('d/m/Y H:i') : '-' }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Data Masuk</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">Hanya pengguna yang login dengan permission khusus yang dapat melihat data ini</p>
                    </div>
                </div>

                <div class="table-responsive" style="margin: 0 -0.75rem; padding: 0 0.75rem;">
                    <table class="table table-sm table-hover align-middle" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                                @foreach ($rekapBukti->fields as $field)
                                    <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">
                                        {{ $field->label }} @if ($field->required)<span class="text-danger">*</span>@endif
                                    </th>
                                @endforeach
                                <th class="text-center" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Waktu Kirim</th>
                                @can('kelola bukti')
                                <th class="text-center" style="width: 80px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $records->firstItem() + $index }}</td>
                                    @foreach ($rekapBukti->fields as $field)
                                        <td>
                                            @if ($field->tipe === 'file')
                                                @php
                                                    $file = $record->fileFor($field);
                                                @endphp
                                                @if ($file && \Illuminate\Support\Facades\Storage::disk('public')->exists($file->path))
                                                    <div class="d-flex align-items-center gap-1">
                                                        <a href="{{ route('bukti.file.preview', [$record->id, $file->id]) }}" target="_blank" class="btn btn-light-info btn-icon btn-sm" style="flex: 0 0 auto;" title="Lihat: {{ $file->nama_asli }}"><i class="ti ti-eye"></i></a>
                                                        <a href="{{ route('bukti.file.download', [$record->id, $file->id]) }}" class="btn btn-light-primary btn-icon btn-sm" style="flex: 0 0 auto;" title="Download: {{ $file->nama_asli }}"><i class="ti ti-download"></i></a>
                                                        <span class="d-none d-lg-inline text-truncate text-muted" style="max-width: 150px; font-size: 11.5px;" title="{{ $file->nama_asli }}">{{ $file->nama_asli }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            @elseif ($field->tipe === 'date' && $record->valueFor($field))
                                                {{ \Carbon\Carbon::parse($record->valueFor($field))->format('d/m/Y') }}
                                            @else
                                                @php
                                                    $value = $record->valueFor($field);
                                                @endphp
                                                <span class="d-inline-block text-truncate" style="max-width: 220px;" title="{{ $value ?? '-' }}">{{ $value !== null && $value !== '' ? $value : '-' }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="text-center" style="font-size: 12px;">{{ $record->created_at->format('d/m/Y H:i') }}</td>
                                    @can('kelola bukti')
                                    <td class="text-center">
                                        <button class="btn btn-light-danger btn-icon btn-sm btn-delete-record" title="Hapus entri" data-url="{{ route('bukti.record.destroy', $record->id) }}" data-name="{{ $record->created_at->format('d/m/Y H:i') }}"><i class="ti ti-trash"></i></button>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $rekapBukti->fields->count() + 2 + (auth()->user()->can('kelola bukti') ? 1 : 0) }}" class="text-center py-5">
                                        <i class="ti ti-inbox" style="font-size: 36px; color: #ced4da;"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada data masuk. Bagikan link publik di atas untuk mulai mengumpulkan data.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($records->hasPages())
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $records->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#btn-copy-link').on('click', function() {
            var link = document.getElementById('public-link');
            link.select();
            link.setSelectionRange(0, 99999);
            try {
                navigator.clipboard.writeText(link.value).then(function() {
                    showToast('success', 'Link berhasil disalin.');
                }, function() {
                    document.execCommand('copy');
                    showToast('success', 'Link berhasil disalin.');
                });
            } catch (e) {
                document.execCommand('copy');
                showToast('success', 'Link berhasil disalin.');
            }
        });

        $('#toggle-status').on('change', function() {
            var chk = $(this);
            var url = chk.data('url');
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    var aktif = chk.is(':checked');
                    var badge = $('#status-badge');
                    if (aktif) {
                        badge.removeClass('bg-secondary').addClass('bg-success').text('Link Aktif');
                        $('#toggle-hint').text('Link terbuka untuk menerima data dari siapapun.');
                    } else {
                        badge.removeClass('bg-success').addClass('bg-secondary').text('Link Nonaktif');
                        $('#toggle-hint').text('Link ditutup, pengunjung tidak bisa mengisi data.');
                    }
                    showToast('success', aktif ? 'Link dibuka.' : 'Link ditutup.');
                },
                error: function() {
                    chk.prop('checked', !chk.is(':checked'));
                    showToast('error', 'Gagal mengubah status link.');
                }
            });
        });

        $(document).on('click', '.btn-delete-rekap', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Formulir?',
                html: 'Yakin ingin menghapus <strong>"' + name + '"</strong>? Semua entri dan file yang terkirim juga akan terhapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            window.location.href = '{{ route("bukti.index") }}';
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus formulir.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-delete-record', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Entri?',
                html: 'Yakin ingin menghapus entri <strong>"' + name + '"</strong> beserta file yang terlampir?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            window.location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus entri.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
