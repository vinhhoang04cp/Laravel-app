<?php

namespace Modules\Vote\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Congress\App\Models\Congress;

class VoteSession extends Model
{
    protected $fillable = [
        'congress_id', 'title', 'description', 'required_percentage',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class,'vote_session_id');
    }

    public function congress()
    {
        return $this->belongsTo(Congress::class, 'congress_id');
    }

}
