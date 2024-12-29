<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsightFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'insight_id',
        'file',
        'type',
        'status',
    ];
}
