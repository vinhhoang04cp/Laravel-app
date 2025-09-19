<?php

namespace Modules\Mail\App\Services;

use Illuminate\Support\Facades\Crypt;
use Modules\Mail\App\Models\MailConfig;

class MailConfigService
{
    public function encryptPassword(?string $plain): ?string
    {
        return $plain ? Crypt::encryptString($plain) : null;
    }

    public function decryptPassword(?string $cipher): ?string
    {
        try {
            return $cipher ? Crypt::decryptString($cipher) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function toMailerArray(MailConfig $cfg): array
    {
        $password = $this->decryptPassword($cfg->password);

        $arr = [
            'transport' => $cfg->driver,
            'host' => $cfg->host,
            'port' => $cfg->port,
            'username' => $cfg->username,
            'password' => $password,
            'encryption' => $cfg->encryption,
            'timeout' => $cfg->timeout,
            'auth_mode' => null,
        ];

        $options = is_array($cfg->options) ? $cfg->options : [];
        return array_filter(array_merge($arr, $options), fn($v) => !is_null($v));
    }

    public function applyToRuntime(string $mailerName, MailConfig $cfg): void
    {
        config([
            'mail.from.address' => $cfg->from_address ?: config('mail.from.address'),
            'mail.from.name' => $cfg->from_name ?: config('mail.from.name'),
            "mail.mailers.$mailerName" => $this->toMailerArray($cfg),
        ]);
    }
}
