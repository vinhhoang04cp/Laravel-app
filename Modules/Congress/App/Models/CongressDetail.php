<?php

namespace Modules\Congress\App\Models;

use Illuminate\Database\Eloquent\Model;

class CongressDetail extends Model
{
    protected $fillable = [
        'order',
        'title',
        'description',
        'congress_id',
        'scheduled_at',
    ];

    /**
     * Mối quan hệ: CongressDetail thuộc về một Congress.
     */
    public function congress()
    {
        return $this->belongsTo(Congress::class);
    }
}
