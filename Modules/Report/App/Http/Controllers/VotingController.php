<?php

namespace Modules\Report\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Congress\App\Models\Congress;
use Modules\Report\App\Services\VoteReportService;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VotingController extends Controller
{

    public function __construct(protected VoteReportService $voteReportService)
    {
    }

    public function index()
    {
        // Lấy danh sách kỳ đại hội để chọn
        $congresses = Congress::all(['id', 'name']);

        return view('report::voting.index', ['congresses' => $congresses]);
    }

    public function loadReport(Request $request)
    {
        $congressId = $request->get('congress_id');

        $data = $this->voteReportService->getSharesAndAttendees($congressId);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function export(Request $request)
    {
        $congressId = $request->get('congress_id');
        $data = $this->voteReportService->getSharesAndAttendees($congressId);

        $rows = [];

        // --- Dòng tổng hợp đầu tiên ---
        $totalShareholders = $data['attendee_count'] ?? 0;
        $totalSharesParticipated = $data['attended_shares'] ?? 0;
        $totalSharesCapital = $data['total_shares'] ?? 0;
        $participationRatio = $totalSharesCapital > 0
            ? ($totalSharesParticipated / $totalSharesCapital) * 100
            : 0;

        $rows[] = [
            "Số cổ đông tham dự: " . number_format($totalShareholders),
            "Tổng CP tham dự: " . number_format($totalSharesParticipated),
            "Tổng CP vốn: " . number_format($totalSharesCapital),
            "Tỷ lệ: " . number_format($participationRatio, 3) . '%'
        ];

        // --- Header chi tiết phiên biểu quyết ---
        $rows[] = [
            'STT',
            'Nội dung biểu quyết',
            'Tổng SL Tán thành',
            'Tổng CP Tán thành',
            'Tỷ lệ (%) Tán thành',
            'Tổng SL Không tán thành',
            'Tổng CP Không tán thành',
            'Tỷ lệ (%) Không tán thành',
            'Tổng SL Không ý kiến',
            'Tổng CP Không ý kiến',
            'Tỷ lệ (%) Không ý kiến',
        ];

        foreach ($data['sessions'] as $index => $session) {
            $rows[] = [
                $index + 1,
                $session['vote_session_name'] ?? 'Chưa đặt tên',
                number_format($session['total_vote_yes'] ?? 0),
                number_format($session['total_shares_yes'] ?? 0),
                number_format($session['vote_yes_ratio'] ?? 0, 3) . '%',
                number_format($session['total_vote_no'] ?? 0),
                number_format($session['total_shares_no'] ?? 0),
                number_format($session['vote_no_ratio'] ?? 0, 3) . '%',
                number_format($session['total_vote_abstain'] ?? 0),
                number_format($session['total_shares_abstain'] ?? 0),
                number_format($session['vote_abstain_ratio'] ?? 0, 3) . '%',
            ];
        }

        return Excel::download(new class($rows) implements FromArray, WithHeadings {
            protected $data;

            public function __construct(array $data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [];
            }
        }, 'voting_report.xlsx');
    }
}
