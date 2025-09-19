<?php

namespace Modules\Mail\App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = 'mail_logs';
    protected $guarded = [];

    protected $fillable = [
        'template_id','template_code','config_id','to_email','subject',
        'success','error','message_id','payload','config_snapshot','sent_at',
    ];

    protected $casts = [
        'success'         => 'boolean',
        'payload'         => 'array',
        'config_snapshot' => 'array',
        'sent_at'         => 'datetime',
    ];
}
