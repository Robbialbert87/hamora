@extends('layouts.app')

@section('title', 'Trashed Dokumen - HAMORA')
@section('page-title', 'Trashed Dokumen')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Dokumen Terhapus</h2>
                <p class="card-subtitle">Daftar dokumen yang telah dihapus</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('documents.index') }}" class="btn-outline-glass btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="trashed-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Dokumen</th>
                        <th>Alasan</th>
                        <th>Dihapus oleh</th>
                        <th>Tanggal Hapus</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents ?? [] as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doc->nama_dokumen }}</td>
                        <td>{{ $doc->deleted_reason ?? '-' }}</td>
                        <td>{{ $doc->deleted_by_user->name ?? '-' }}</td>
                        <td>{{ $doc->deleted_at ? $doc->deleted_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="action-btns">
                                <form action="{{ route('documents.restore', $doc->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn-emerald btn-sm">
                                        <i class="fas fa-trash-restore"></i> Restore
                                    </button>
                                </form>
                                <button class="btn-outline-glass btn-sm btn-force-delete"
                                        data-url="{{ route('documents.force-delete', $doc->id) }}"
                                        data-name="{{ $doc->nama_dokumen }}">
                                    <i class="fas fa-times-circle"></i> Hapus Permanen
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada dokumen terhapus</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
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
                text: 'Yakin ingin menghapus permanen "' + name + '"? Tindakan ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
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
                            Swal.fire('Terhapus!', 'Dokumen berhasil dihapus permanen.', 'success');
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
