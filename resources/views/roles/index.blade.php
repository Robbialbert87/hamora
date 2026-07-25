@extends('layouts.app')

@section('title', 'Role Management - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Daftar Role</li>
                </ol>
            </div>
            <h4 class="page-title">Daftar Role</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-0">Daftar Role</h4>
                        <p class="text-muted mb-0">Kelola hak akses pengguna</p>
                    </div>
                    <div>
                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="ti ti-plus"></i> Tambah Role
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="roles-table">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Role</th>
                                <th>Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles ?? [] as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $role->name }}</strong></td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        @foreach($role->permissions as $perm)
                                        <span class="badge bg-light text-dark">{{ $perm->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="ti ti-pencil"></i> Edit
                                        </a>
                                        @if($role->name !== 'Super Admin')
                                        <button class="btn btn-outline-danger btn-sm btn-delete-role"
                                                data-url="{{ route('roles.destroy', $role->id) }}"
                                                data-name="{{ $role->name }}">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada role</td>
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
        $('#roles-table').DataTable({
            responsive: true,
            language: {
                url: '/assets/lang/Indonesian.json'
            }
        });

        $(document).on('click', '.btn-delete-role', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Role?',
                text: 'Yakin ingin menghapus role "' + name + '"?',
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
                            Swal.fire('Terhapus!', 'Role berhasil dihapus.', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON?.message || 'Gagal menghapus role.';
                            Swal.fire('Error!', msg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
