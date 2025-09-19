<?php

namespace Modules\Shareholder\App\Observers;

use App\Jobs\SendShareholderInviteJob;
use Modules\Shareholder\App\Enums\CreateStatus;
use Modules\Shareholder\App\Enums\EmailStatus;
use Modules\Shareholder\App\Enums\RegistrationStatus;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Shareholder\App\Services\TokenServices;

class ShareholderObserver
{
    protected TokenServices $tokenService;

    public function __construct(TokenServices $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function created(Shareholder $shareholder)
    {
        if ($shareholder->email && $shareholder->init_method===CreateStatus::MANUAL) {
            // ✅ Tạo token xác nhận bằng UUID
            $token = $this->tokenService->generate();

            $shareholder->forceFill([
                'confirmation_token'      => $token,
                'confirmation_expires_at' => now()->addDays(3),
            ])->saveQuietly();

            // ✅ Dispatch job gửi mail mời
            SendShareholderInviteJob::dispatch($shareholder);

            // ✅ Cập nhật trạng thái
            $shareholder->updateQuietly([
                'registration_status' => RegistrationStatus::PENDING,
                'email_status'        => EmailStatus::SEND,
            ]);
        }
    }
}
