<?php

namespace Modules\Mail\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'mail_templates';
    protected $guarded = [];

    protected $fillable = [
        'name', 'code', 'subject', 'body', 'placeholders', 'is_html', 'enabled'
    ];

    protected $casts = [
        'placeholders' => 'array',
        'is_html' => 'boolean',
        'enabled' => 'boolean',
    ];
}
