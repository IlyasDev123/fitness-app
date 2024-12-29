<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FavouritInsight extends Pivot
{
    protected $table = 'favourit_insight';

    protected $fillable = [
        'insight_id',
        'user_id',
    ];
}
