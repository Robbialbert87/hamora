@extends('layouts.app')

@section('title', 'Kategori - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
                    <li class="breadcrumb-item active">Daftar Kategori</li>
                </ol>
            </div>
            <h4 class="page-title">Daftar Kategori</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Daftar Kategori</h4>
                        <p class="text-muted mb-0">Kelola kategori dokumen</p>
                    </div>
                    <div>
                        <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Tambah Kategori
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="kategori-table">
                        <thead class="table-light">
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
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="ti ti-pencil"></i> Edit
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm btn-delete-kategori"
                                                data-url="{{ route('kategori.destroy', $k->id) }}"
                                                data-name="{{ $k->nama }}">
                                            <i class="ti ti-trash"></i> Hapus
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#kategori-table').DataTable({
            responsive: true,
            language: {
                url: '/assets/lang/Indonesian.json'
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
