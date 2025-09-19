<?php

namespace Modules\Congress\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Vote\App\Models\Vote;

class Congress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'scheduled_at',
        'description',
        'type',
        'organization_form',
        'location',
    ];

    protected $dates = [
        'scheduled_at',
        'deleted_at',
    ];

    /**
     * Ví dụ quan hệ: 1 congress có nhiều user
     */
    public function shareholders()
    {
        return $this->hasMany(Shareholder::class, 'congress_id');
    }

    /**
     * Ví dụ quan hệ: 1 congress có nhiều votes
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function details()
    {
        return $this->hasMany(CongressDetail::class);
    }

    public function voteSessions()
    {
        return $this->hasMany(\Modules\Vote\App\Models\VoteSession::class);
    }
}
