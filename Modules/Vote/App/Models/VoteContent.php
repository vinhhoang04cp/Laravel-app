<?php

namespace Modules\Vote\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Congress\App\Models\Congress;

class VoteContent extends Model
{
    protected $fillable = [
        'title',
        'type',
        'congress_id',
    ];

    public function congress()
    {
        return $this->belongsTo(Congress::class);
    }
}
