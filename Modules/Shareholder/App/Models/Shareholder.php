<?php

namespace Modules\Shareholder\App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Congress\App\Models\Congress;
use Modules\Shareholder\App\Enums\EmailStatus;
use Modules\Shareholder\App\Enums\RegistrationStatus;


class Shareholder extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'congress_id',
        'full_name',
        'email',
        'phone',
        'address',
        'ownership_registration_number',
        'ownership_registration_issue_date',
        'nationality',
        'registration_status',
        'email_status',
        'transaction_date',
        'init_method',
        'confirmation_token',
        'confirmation_expires_at',
        'otp_code',
        'otp_expires_at',
        'is_confirmed',
        'share_unregistered',
        'share_deposited',
        'share_total',
        'allocation_unregistered',
        'allocation_deposited',
        'allocation_total',
        'sid',
        'investor_code',
        'ratio',
    ];

    protected $casts = [
        'ownership_registration_issue_date' => 'date:Y-m-d',
        'transaction_date' => 'date:Y-m-d',
        'confirmation_expires_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'is_confirmed' => 'boolean',
        'registration_status' => RegistrationStatus::class,
        'email_status' => EmailStatus::class,
        'ratio' => 'decimal:3', // đảm bảo lấy ra luôn đúng 3 chữ số thập phân
    ];

    public function congress()
    {
        return $this->belongsTo(Congress::class, 'congress_id');
    }
}
