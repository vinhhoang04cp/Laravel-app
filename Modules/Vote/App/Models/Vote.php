<?php

namespace Modules\Vote\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Shareholder\App\Models\Shareholder;

class Vote extends Model
{
    protected $fillable = ['vote_session_id', 'shareholder_id', 'choice', 'shares'];

    public function session()
    {
        return $this->belongsTo(VoteSession::class, 'vote_session_id');
    }

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }
}
