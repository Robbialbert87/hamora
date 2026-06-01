<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function getAll(int $perPage = 50): LengthAwarePaginator
    {
        return ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByUser(int $userId, int $perPage = 50): LengthAwarePaginator
    {
        return ActivityLog::with('user')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByAction(string $action, int $perPage = 50): LengthAwarePaginator
    {
        return ActivityLog::with('user')
            ->where('action', $action)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function search(string $query, int $perPage = 50): LengthAwarePaginator
    {
        return ActivityLog::with('user')
            ->where('description', 'like', "%{$query}%")
            ->orWhere('action', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
