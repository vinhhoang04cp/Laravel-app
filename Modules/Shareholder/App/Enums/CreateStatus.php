<?php

namespace Modules\Shareholder\App\Enums;

enum CreateStatus: string
{
    case MANUAL = 'Tạo thủ công';  // default khi khởi tạo
    case TOOL    = 'Tạo bằng tool';    // gửi thành công

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
