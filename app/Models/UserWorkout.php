<?php

namespace App\Models;

use App\Models\Workout;
use App\Models\WorkoutVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWorkout extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'workout_id',
        'workout_video_id',
        'is_played',
        'status',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workout_id');
    }

    public function workoutVideo()
    {
        return $this->belongsTo(WorkoutVideo::class);
    }
}
