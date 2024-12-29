<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_id',
        'url',
        'thumbnail',
        'is_premium',
        'views',
        'duration',
        'status',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    public function getThumbnailAttribute($value)
    {
        return $value ? env('AWS_URL') . $value : null;
    }

    public function getUrlAttribute($value)
    {
        return $value ? env('AWS_URL') . $value : null;
    }
}
