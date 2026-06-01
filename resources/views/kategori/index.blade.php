@extends('layouts.app')

@section('title', 'Kategori - HAMORA')
@section('page-title', 'Kategori')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Kategori</h2>
                <p class="card-subtitle">Kelola kategori dokumen</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('kategori.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="kategori-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Jumlah Dokumen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori ?? [] as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->nama }}</td>
                        <td><code>{{ $k->slug }}</code></td>
                        <td>{{ $k->documents_count ?? $k->documents->count() }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn-outline-glass btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn-outline-glass btn-sm btn-delete-kategori"
                                        data-url="{{ route('kategori.destroy', $k->id) }}"
                                        data-name="{{ $k->nama }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada kategori</td>
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
        $('#kategori-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.11/i18n/id.json'
            }
        });

        $(document).on('click', '.btn-delete-kategori', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: 'Yakin ingin menghapus kategori "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
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
                            Swal.fire('Terhapus!', 'Kategori berhasil dihapus.', 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus kategori.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
