<?php

namespace Modules\Shareholder\App\Enums;

enum RegistrationStatus: string
{
    case INIT     = 'Vừa khởi tạo';
    case REGISTER = 'Đã đăng ký';
    case PENDING  = 'Chưa đăng ký';
    case PROXY    = 'Ủy quyền';

    /**
     * Danh sách tất cả giá trị enum (dùng cho form select, validation, seed…)
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
