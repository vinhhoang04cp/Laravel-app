<?php

namespace Modules\Mail\App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Modules\Mail\App\Models\MailConfig;
use Modules\Mail\App\Models\MailTemplate;
use Modules\Mail\App\Models\MailLog;

class MailService
{
    public function __construct(
        private MailTemplateRenderer $renderer,
        private MailConfigService $configService,
    ) {}

    public function renderSubject(MailTemplate $tpl, array $data = []): string
    {
        return $this->renderer->safeRender((string) $tpl->subject, $data);
    }

    public function renderBody(MailTemplate $tpl, array $data = []): string
    {
        return $this->renderer->safeRender((string) $tpl->body, $data);
    }

    public function sendUsingTemplate(string $to, MailTemplate $tpl, array $data = [], ?MailConfig $cfg = null): MailLog
    {
        $subject = $this->renderSubject($tpl, $data);
        $html    = $this->renderBody($tpl, $data);

        // Chuẩn bị log trước
        $log = new MailLog([
            'template_id'     => $tpl->id ?? null,
            'template_code'   => $tpl->code ?? null,
            'config_id'       => $cfg?->id,
            'to_email'        => trim($to),
            'subject'         => $subject,
            'payload'         => $data,
            'config_snapshot' => $cfg ? [
                'driver'   => $cfg->driver,
                'host'     => $cfg->host,
                'port'     => $cfg->port,
                'from'     => [$cfg->from_address, $cfg->from_name],
                'reply_to' => $cfg->reply_to,
                'timeout'  => $cfg->timeout,
            ] : null,
            'success'         => false,
        ]);

        $mailerName = null;
        $fromAddress = config('mail.from.address');
        $fromName    = config('mail.from.name');

        if ($cfg) {
            $mailerName = 'runtime_mailer';
            $this->configService->applyToRuntime($mailerName, $cfg);
            $fromAddress = $cfg->from_address ?: $fromAddress;
            $fromName    = $cfg->from_name ?: $fromName;
        }

        $messageId = null;

        try {
            $callback = function ($message) use ($to, $subject, $html, $cfg, $fromAddress, $fromName, &$messageId) {
                $message->to($to)->subject($subject)->html($html);

                if ($fromAddress) {
                    $message->from($fromAddress, $fromName ?: $fromAddress);
                }
                if ($cfg && $cfg->reply_to) {
                    $message->replyTo($cfg->reply_to);
                }

                // Lấy Message-ID từ Symfony Email object
                $symfonyEmail = $message->getSymfonyMessage();
                $messageId = $symfonyEmail->getHeaders()->getHeaderBody('Message-ID');
            };

            if ($mailerName) {
                Mail::mailer($mailerName)->send([], [], $callback);
            } else {
                Mail::send([], [], $callback);
            }

            $log->success    = true;
            $log->message_id = $messageId;
            $log->sent_at    = now();
            $log->save();

            return $log;
        } catch (\Throwable $e) {
            $log->success = false;
            $log->error   = $e->getMessage();
            $log->save();

            Log::error('[MailService] Send failed', [
                'to' => $to,
                'template_code' => $tpl->code ?? null,
                'config_id' => $cfg?->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
