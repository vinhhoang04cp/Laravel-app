<?php

namespace Modules\Congress\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Congress\App\Services\QueueService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function __construct(protected QueueService $queueService)
    {
    }

    /**
     * View blade hiển thị bảng jobs_histories
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $start = $request->input('start');
        $finish = $request->input('finish');
        $perPage = $request->input('perPage', 20);

        $jobs = $this->queueService->getJobsByFilter($status, $start, $finish, $perPage);

        return view('congress::queue', compact('jobs', 'status', 'start', 'finish'));
    }
}
