<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    protected $activityLog;

    public function __construct()
    {
        $this->activityLog = new ActivityLogService();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->activityLog->getAll(50);
        }
        return view('logs.index');
    }

    public function data()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc');
        return \Yajra\DataTables\Facades\DataTables::of($logs)
            ->addIndexColumn()
            ->addColumn('user_name', function ($log) {
                return $log->user ? $log->user->name : 'System';
            })
            ->addColumn('action_badge', function ($log) {
                $colors = [
                    'login' => 'info',
                    'upload' => 'primary',
                    'edit' => 'warning',
                    'verifikasi' => 'success',
                    'hapus' => 'danger',
                    'restore' => 'secondary',
                    'hapus_permanen' => 'dark',
                ];
                $color = $colors[$log->action] ?? 'secondary';
                return "<span class=\"badge bg-{$color}\">" . e($log->action) . "</span>";
            })
            ->rawColumns(['action_badge'])
            ->make(true);
    }
}
