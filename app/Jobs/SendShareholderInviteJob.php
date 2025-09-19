<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Mail\App\Models\MailConfig;
use Modules\Mail\App\Models\MailTemplate;
use Modules\Mail\App\Services\MailService;
use Modules\Shareholder\App\Models\Shareholder;

class SendShareholderInviteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Shareholder $shareholder;

    /**
     * Create a new job instance.
     */
    public function __construct(Shareholder $shareholder)
    {
        $this->shareholder = $shareholder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tpl = MailTemplate::where('code', 'shareholder_invite')->first();
        $cfg = MailConfig::where('is_active', 1)->first();

        if (!$tpl) {
            Log::warning('[SendShareholderInviteJob] MailTemplate not found: shareholder_invite');
            return;
        }

        if (!$cfg) {
            Log::warning('[SendShareholderInviteJob] Active MailConfig not found');
            return;
        }

        if (!$this->shareholder->email) {
            Log::warning('[SendShareholderInviteJob] Shareholder email missing', [
                'shareholder_id' => $this->shareholder->id,
            ]);
            return;
        }

        $data = [
            'full_name' => $this->shareholder->full_name,
            'ownership_registration_number' => $this->shareholder->ownership_registration_number,
            'address' => $this->shareholder->address,
            'url'  => route('shareholders.register', [
                'token' => $this->shareholder->confirmation_token,
            ], false),
        ];

        Log::info('[SendShareholderInviteJob] Sending invite email', [
            'to'   => $this->shareholder->email,
            'data' => $data,
        ]);

        try {
            app(MailService::class)->sendUsingTemplate(
                $this->shareholder->email,
                $tpl,
                $data,
                $cfg
            );

            Log::info('[SendShareholderInviteJob] Invite email sent successfully', [
                'to' => $this->shareholder->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('[SendShareholderInviteJob] Failed to send invite email', [
                'to'    => $this->shareholder->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // vẫn ném ra để queue retry
        }
    }
}
