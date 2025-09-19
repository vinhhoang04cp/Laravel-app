<?php

namespace Modules\Report\App\Services;

use Modules\Report\App\Repositories\AttendanceReportRepository;

class AttendanceReportService
{
    public function __construct(protected AttendanceReportRepository $attendanceReportRepository)
    {
    }

    public function getAttendanceSummary($congressId)
    {
        $data = $this->attendanceReportRepository->getAttendanceSummary($congressId);

        // Tổng số cổ phần cổ đông tham dự
        $data['participated_shares'] = $data['attended_shares'] + $data['proxy_shares'];

        $data['participation_rate'] = $data['participated_shares'] > 0 ? round($data['participated_shares'] / $data['total_shares'] * 100, 2) : 0;

        return $data;
    }
}
