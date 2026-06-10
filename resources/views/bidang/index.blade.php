@extends('layouts.app')

@section('title', 'Bidang - HAMORA')
@section('page-title', 'Bidang')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Bidang</h2>
                <p class="card-subtitle">Kelola bidang dokumen</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('bidang.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Tambah Bidang
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="bidang-table">
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
                    @forelse($bidang ?? [] as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $b->nama }}</td>
                        <td><code>{{ $b->slug }}</code></td>
                        <td>{{ $b->documents_count ?? $b->documents->count() }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('bidang.edit', $b->id) }}" class="btn-outline-glass btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn-outline-glass btn-sm btn-delete-bidang"
                                        data-url="{{ route('bidang.destroy', $b->id) }}"
                                        data-name="{{ $b->nama }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada bidang</td>
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
        $('#bidang-table').DataTable({
            responsive: true,
            language: {
                url: '/assets/lang/Indonesian.json'
            }
        });

        $(document).on('click', '.btn-delete-bidang', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Bidang?',
                text: 'Yakin ingin menghapus bidang "' + name + '"?',
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
                            Swal.fire('Terhapus!', 'Bidang berhasil dihapus.', 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Gagal menghapus bidang.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
