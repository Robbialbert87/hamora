@extends('layouts.app')

@section('title', 'Trashed Dokumen - HAMORA')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                    <li class="breadcrumb-item active">Trashed</li>
                </ol>
            </div>
            <h4 class="page-title">Dokumen Terhapus</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Dokumen Terhapus</h5>
                        <p class="text-muted mb-0" style="font-size: 12.5px;">Daftar dokumen yang telah dihapus</p>
                    </div>
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary btn-sm btn-icon" title="Kembali">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover w-100" id="trashed-table" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nama Dokumen</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Alasan</th>
                                <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Dihapus oleh</th>
                                <th class="text-center" style="width: 120px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Tanggal Hapus</th>
                                <th class="text-center" style="width: 130px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents ?? [] as $doc)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $doc->nama_dokumen }}</td>
                                <td>{{ $doc->deleted_reason ?? '-' }}</td>
                                <td>{{ $doc->deleted_by_user->name ?? '-' }}</td>
                                <td class="text-center">{{ $doc->deleted_at ? $doc->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <form action="{{ route('documents.restore', $doc->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-light-success btn-icon" title="Restore">
                                                <i class="ti ti-restore"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-light-danger btn-icon btn-force-delete"
                                                data-url="{{ route('documents.force-delete', $doc->id) }}"
                                                data-name="{{ $doc->nama_dokumen }}" title="Hapus Permanen">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 24px;">Tidak ada dokumen terhapus</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-force-delete', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Permanen?',
                html: 'Yakin ingin menghapus permanen <strong>"' + name + '"</strong>?<br>Tindakan ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus permanen!',
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
                            Swal.fire('Berhasil!', 'Dokumen berhasil dihapus permanen.', 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus dokumen.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection