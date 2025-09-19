<?php

namespace Modules\Report\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Congress\App\Models\Congress;
use Modules\Report\App\Services\AttendanceReportService;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceReportService $attendanceReportService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách kỳ đại hội để chọn
        $congresses = Congress::all(['id', 'name']);

        return view('report::attendance.index', ['congresses' => $congresses]);
    }

    public function loadReport(Request $request)
    {
        $congressId = $request->get('congress_id');

        $data = $this->attendanceReportService->getAttendanceSummary($congressId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function export(Request $request)
    {
        $congressId = $request->get('congress_id');
        $data = $this->attendanceReportService->getAttendanceSummary($congressId);

        return Excel::download(new class($data) implements FromArray, WithHeadings {
            protected $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return [
                    [
                        number_format($this->data['proxy_count'] ?? 0),
                        number_format($this->data['proxy_shares'] ?? 0),
                        number_format($this->data['attended_count'] ?? 0),
                        number_format($this->data['attended_shares'] ?? 0)
                    ],
                    ['Vốn điều lệ', number_format($this->data['total_shares'] ?? 0)],
                    ['Tổng số lượng cổ phần', number_format($this->data['participated_shares'] ?? 0)],
                    ['Tỷ lệ tham dự ĐHĐCĐ', number_format($this->data['participation_rate'] ?? 0, 2) . '%'],
                ];
            }

            public function headings(): array
            {
                return [
                    [
                        'Số cổ đông ủy quyền',
                        'Tổng số cổ phần ủy quyền',
                        'Số cổ đông tham dự',
                        'Tổng số cổ phần tham dự'
                    ]
                ];
            }
        }, 'attendances_report.xlsx');
    }
}
