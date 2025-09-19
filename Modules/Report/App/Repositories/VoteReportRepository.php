<?php

namespace Modules\Report\App\Repositories;

use Modules\Shareholder\App\Models\Shareholder;
use Modules\Vote\App\Models\Vote;
use Modules\Vote\App\Models\VoteSession;

class VoteReportRepository
{
    public function getSharesAndAttendees($congressId)
    {
        // Số cổ đông tham dự
        $attendeeCount = Shareholder::where('congress_id', $congressId)
            ->whereIn('registration_status', ['Đã đăng ký', 'Ủy quyền'])
            ->count();

        // Tổng số cổ phần của cổ đông tham dự
        $attendedShares = Shareholder::where('congress_id', $congressId)
            ->whereIn('registration_status', ['Đã đăng ký', 'Ủy quyền'])
            ->sum('allocation_total');

        // Tổng số cổ phần (tất cả cổ đông)
        $totalShares = Shareholder::where('congress_id', $congressId)
            ->sum('allocation_total');

        // Lấy tất cả vote_session của kỳ đại hội
        $sessions = VoteSession::where('congress_id', $congressId)->get();

        // Lấy tất cả vote cho các session
        $votes = Vote::whereIn('vote_session_id', $sessions->pluck('id'))->get();

        return [
            'attendee_count' => $attendeeCount,
            'attended_shares' => $attendedShares,
            'total_shares' => $totalShares,
            'sessions' => $sessions,
            'votes' => $votes,
        ];
    }
}
