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

                <table class="table table-sm table-hover w-100" id="bidang-table" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Nama</th>
                            <th style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Slug</th>
                            <th class="text-center" style="width: 120px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Jumlah Dokumen</th>
                            <th class="text-center" style="width: 100px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bidang ?? [] as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->nama }}</td>
                            <td><code>{{ $b->slug }}</code></td>
                            <td class="text-center">{{ $b->documents_count ?? $b->documents->count() }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('bidang.edit', $b->id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm btn-delete-bidang"
                                            data-url="{{ route('bidang.destroy', $b->id) }}"
                                            data-name="{{ $b->nama }}">
                                        <i class="ti ti-trash"></i>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#bidang-table').DataTable({
            responsive: false,
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
