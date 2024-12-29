<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LikeInsight extends Pivot
{
    protected $table = 'like_insight';

    protected $fillable = [
        'insight_id',
        'user_id',
    ];
}
