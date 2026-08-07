@extends('layouts.app')

@section('title', 'Log Aktivitas - HAMORA')

@section('content')
<!-- Page-Title -->
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="float-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
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

                <table class="table table-sm table-hover w-100" id="logs-table" style="border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th class="text-center" data-priority="4" style="width: 42px; background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">No</th>
                            <th data-priority="1" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">User</th>
                            <th data-priority="2" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Aksi</th>
                            <th data-priority="5" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Deskripsi</th>
                            <th data-priority="3" style="background: #f8f9fa; font-size: 11.5px; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.3px; border-bottom: 2px solid #dee2e6; padding: 8px 10px;">Waktu</th>
                        </tr>
                    </thead>
                </table>
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, responsivePriority: 4 },
                { data: 'user_name', name: 'user.name', responsivePriority: 1 },
                { data: 'action_badge', name: 'action', responsivePriority: 2 },
                { data: 'description', name: 'description', responsivePriority: 5 },
                { data: 'created_at', name: 'created_at', responsivePriority: 3 }
            ],
            language: {
                url: '/assets/lang/Indonesian.json'
            },
            order: [[4, 'desc']]
        });
    });
</script>
@endsection
