@extends('layouts.app')

@section('title', 'Log Aktivitas - HAMORA')
@section('page-title', 'Log Aktivitas')

@section('content')
<section class="content-grid" style="grid-template-columns: 1fr;">
    <div class="glass-card table-card" style="grid-column: span 1;">
        <div class="card-header">
            <div>
                <h2 class="card-title">Log Aktivitas</h2>
                <p class="card-subtitle">Riwayat aktivitas pengguna</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table" id="logs-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#logs-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route("logs.data") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user.name' },
                { data: 'action_badge', name: 'action' },
                { data: 'description', name: 'description' },
                { data: 'created_at', name: 'created_at' }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.11/i18n/id.json'
            },
            order: [[4, 'desc']]
        });
    });
</script>
@endsection
