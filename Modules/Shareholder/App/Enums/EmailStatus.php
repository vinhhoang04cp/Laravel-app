<?php

namespace Modules\Shareholder\App\Enums;

enum EmailStatus: string
{
    case PENDING = 'Chưa gửi';  // default khi khởi tạo
    case SEND    = 'Đã gửi';    // gửi thành công
    case FAILED  = 'Lỗi gửi';   // gửi thất bại

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
