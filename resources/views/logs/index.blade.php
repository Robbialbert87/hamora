@extends('layouts.app')

@section('title', 'Log Aktivitas - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">HAMORA</a></li>
                    <li class="breadcrumb-item active">Log Aktivitas</li>
                </ol>
            </div>
            <h4 class="page-title">Log Aktivitas</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="card-title mb-0">Log Aktivitas</h4>
                    <p class="text-muted mb-0">Riwayat aktivitas pengguna</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="logs-table">
                        <thead class="table-light">
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
        </div>
    </div>
</div>
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
                url: '/assets/lang/Indonesian.json'
            },
            order: [[4, 'desc']]
        });
    });
</script>
@endsection
