<?php

namespace Modules\Mail\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailConfig extends Model
{
    use SoftDeletes;

    protected $table = 'mail_configs';
    protected $guarded = [];

    protected $fillable = [
        'name','driver','host','port','username','password','encryption',
        'from_address','from_name','reply_to','timeout','is_active','options'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'options' => 'array',
    ];
}
