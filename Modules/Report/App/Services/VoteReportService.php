<?php

namespace Modules\Report\App\Services;

use Modules\Report\App\Repositories\VoteReportRepository;

class VoteReportService
{
    public function __construct(protected VoteReportRepository $voteReportRepository)
    {
    }

    public function getSharesAndAttendees($congressId)
    {
        $data = $this->voteReportRepository->getSharesAndAttendees($congressId);

        $totalShares = $data['total_shares'] ?? 0;
        $votes = $data['votes'] ?? collect();

        // Tính tỷ lệ cho từng session
        $sessions = $data['sessions']->map(function ($session) use ($votes, $totalShares) {

            $sessionVotes = $votes->where('vote_session_id', $session->id);

            $attendedShares = $sessionVotes->sum('shares');
            $attendeeCount = $sessionVotes->pluck('shareholder_id')->unique()->count();

            $votesSummary = $sessionVotes->groupBy('choice')->map(function ($items) {
                return [
                    'votes' => $items->count(),
                    'shares' => $items->sum('shares'),
                ];
            });

            return [
                'vote_session_id' => $session->id,
                'vote_session_name' => $session->title,
                'attendee_count' => $attendeeCount,
                'attended_shares' => $attendedShares,
                'attended_shares_ratio' => $totalShares > 0 ? round($attendedShares / $totalShares * 100, 3) : 0,
                'total_vote_yes' => $votesSummary['yes']['votes'] ?? 0,
                'total_shares_yes' => $votesSummary['yes']['shares'] ?? 0,
                'vote_yes_ratio' => $attendedShares > 0 ? round(($votesSummary['yes']['shares'] ?? 0) / $attendedShares * 100, 3) : 0,
                'total_vote_no' => $votesSummary['no']['votes'] ?? 0,
                'total_shares_no' => $votesSummary['no']['shares'] ?? 0,
                'vote_no_ratio' => $attendedShares > 0 ? round(($votesSummary['no']['shares'] ?? 0) / $attendedShares * 100, 3) : 0,
                'total_vote_abstain' => $votesSummary['abstain']['votes'] ?? 0,
                'total_shares_abstain' => $votesSummary['abstain']['shares'] ?? 0,
                'vote_abstain_ratio' => $attendedShares > 0 ? round(($votesSummary['abstain']['shares'] ?? 0) / $attendedShares * 100, 3) : 0,
            ];
        })->toArray();

        return [
            'attendee_count' => $data['attendee_count'],
            'attended_shares' => $data['attended_shares'],
            'total_shares' => $totalShares,
            'sessions' => $sessions,
        ];
    }
}
