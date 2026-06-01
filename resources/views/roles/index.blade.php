@extends('layouts.app')

@section('title', 'Role Management - HAMORA')
@section('page-title', 'Role Management')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Daftar Role</h2>
                <p class="card-subtitle">Kelola hak akses pengguna</p>
            </div>
            <div class="card-header-actions">
                <a href="{{ route('roles.create') }}" class="btn-emerald btn-sm">
                    <i class="fas fa-plus"></i> Tambah Role
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="roles-table">
                <thead>
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
                                <span style="background: rgba(52, 211, 153, 0.15); color: var(--emerald-light); padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $perm->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn-outline-glass btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($role->name !== 'Super Admin')
                                <button class="btn-outline-glass btn-sm btn-delete-role"
                                        data-url="{{ route('roles.destroy', $role->id) }}"
                                        data-name="{{ $role->name }}">
                                    <i class="fas fa-trash"></i> Hapus
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
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#roles-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.11/i18n/id.json'
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
