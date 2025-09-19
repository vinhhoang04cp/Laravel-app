<?php

namespace Modules\Congress\App\Services;

use Illuminate\Support\Facades\DB;

class QueueService
{
    public function __construct()
    {
    }

    /**
     * Lấy danh sách job_histories với filter
     */
    public function getJobsByFilter(?string $status = null, ?string $start = null, ?string $finish = null, int $perPage = 15)
    {
        $query = DB::table('job_histories')
            ->select('job_id', 'name', 'status', 'started_at', 'finished_at', 'log_path');

        if ($status) {
            $query->where('status', $status);
        }

        if ($start) {
            $query->where('started_at', '>=', $start);
        }

        if ($finish) {
            $query->where('finished_at', '<=', $finish);
        }

        return $query->orderByDesc('started_at')
            ->paginate($perPage)
            ->appends(request()->query()); // giữ query string khi phân trang
    }
}
