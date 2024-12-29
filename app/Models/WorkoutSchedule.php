<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workout_id',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
