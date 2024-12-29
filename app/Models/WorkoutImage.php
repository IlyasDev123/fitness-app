<?php

namespace App\Models;

use App\Models\Workout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkoutImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'workout_id',
        'user_id',
        'image',
        'status',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? env('STORAGE_IMAGE_PATH') . $value : null,
        );
    }

    public function scopeUserImage($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
