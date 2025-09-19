<?php

namespace Modules\Shareholder\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Shareholder\App\Models\Shareholder;

class InviteShareholderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shareholder;

    public function __construct(Shareholder $shareholder)
    {
        $this->shareholder = $shareholder;
    }

    public function build()
    {
        return $this->subject('Thư mời đăng ký cổ đông')
            ->view('shareholder::emails.invite')
            ->with([
                'name' => $this->shareholder->full_name,
                'url'  => url('/shareholders/register?token='.$this->shareholder->confirmation_token),
            ]);
    }
}
