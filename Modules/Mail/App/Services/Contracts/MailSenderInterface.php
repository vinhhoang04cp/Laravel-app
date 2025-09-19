<?php

// Modules/Mail/App/Services/Contracts/MailSenderInterface.php
namespace Modules\Mail\App\Services\Contracts;

use Modules\Mail\App\Models\MailConfig;
use Modules\Mail\App\Models\MailTemplate;
use Modules\Mail\App\Models\MailLog;

interface MailSenderInterface
{
    /**
     * Gửi email bằng template + ghi log.
     * Trả về MailLog (đã lưu DB).
     */
    public function sendUsingTemplate(
        string $to,
        MailTemplate $tpl,
        array $data = [],
        ?MailConfig $cfg = null
    ): MailLog;
}
