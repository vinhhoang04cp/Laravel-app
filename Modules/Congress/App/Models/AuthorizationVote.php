<?php

namespace Modules\Congress\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuthorizationVote extends Model
{
    protected $fillable = [
        'status',
        'deputy',
        'authorized',
        'evidence',
        'congress_id',
    ];

    /**
     * Đại hội liên quan đến ủy quyền này.
     */
    public function congress()
    {
        return $this->belongsTo(Congress::class);
    }

    /**
     * Người được ủy quyền (user đại diện).
     */
    public function deputy()
    {
        return $this->belongsTo(User::class, 'deputy');
    }

    /**
     * Người ủy quyền (user chính chủ).
     */
    public function authorized()
    {
        return $this->belongsTo(User::class, 'authorized');
    }
}
