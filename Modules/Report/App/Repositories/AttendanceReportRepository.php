<?php

namespace Modules\Report\App\Repositories;

use Modules\Shareholder\App\Models\Shareholder;

class AttendanceReportRepository
{
    public function getAttendanceSummary($congressId)
    {
        // Số cổ đông tham dự
        $attendedCount = Shareholder::where('congress_id', $congressId)
            ->where('registration_status', 'Đã đăng ký')
            ->count();

        $proxyCount = Shareholder::where('congress_id', $congressId)
            ->where('registration_status', 'Ủy quyền')
            ->count();

        // Số cổ phần cổ đông tham dự
        $attendedShares = Shareholder::where('congress_id', $congressId)
            ->where('registration_status', 'Đã đăng ký')
            ->sum('allocation_total');

        $proxyShares = Shareholder::where('congress_id', $congressId)
            ->where('registration_status', 'Ủy quyền')
            ->sum('allocation_total');

        // Vốn điều lệ
        $totalShares = Shareholder::where('congress_id', $congressId)
            ->sum('allocation_total');

        return [
            'attended_count' => $attendedCount,
            'proxy_count' => $proxyCount,
            'attended_shares' => $attendedShares,
            'proxy_shares' => $proxyShares,
            'total_shares' => $totalShares,
        ];
    }
}
