<?php

namespace Modules\Shareholder\App\Services;

use Illuminate\Support\Str;

class TokenServices
{
    /**
     * Sinh token UUID v4
     */
    public function generate(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Sinh token kèm prefix (tuỳ chọn)
     */
    public function generateWithPrefix(string $prefix): string
    {
        return $prefix . '-' . Str::uuid()->toString();
    }

    /**
     * Kiểm tra token có phải UUID hợp lệ không
     */
    public function isValid(string $token): bool
    {
        return preg_match(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $token
            ) === 1;
    }
}
