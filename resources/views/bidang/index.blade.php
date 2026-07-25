@extends('layouts.app')

@section('title', 'Bidang - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('bidang.index') }}">Bidang</a></li>
                    <li class="breadcrumb-item active">Daftar Bidang</li>
                </ol>
            </div>
            <h4 class="page-title">Daftar Bidang</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Daftar Bidang</h4>
                        <p class="text-muted mb-0">Kelola bidang dokumen</p>
                    </div>
                    <div>
                        <a href="{{ route('bidang.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Tambah Bidang
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="bidang-table">
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
                            @forelse($bidang ?? [] as $b)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $b->nama }}</td>
                                <td><code>{{ $b->slug }}</code></td>
                                <td>{{ $b->documents_count ?? $b->documents->count() }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('bidang.edit', $b->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="ti ti-pencil"></i> Edit
                                        </a>
                                        <button class="btn btn-outline-danger btn-sm btn-delete-bidang"
                                                data-url="{{ route('bidang.destroy', $b->id) }}"
                                                data-name="{{ $b->nama }}">
                                            <i class="ti ti-trash"></i> Hapus
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
        </div>
    </div>
</div>
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
