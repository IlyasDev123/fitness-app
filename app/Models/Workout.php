<?php

namespace App\Models;

use App\Models\Category;
use App\Models\WorkoutImage;
use App\Models\WorkoutVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'category_id',
        'is_premium',
        'is_featured',
        'views',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function videos()
    {
        return $this->hasMany(WorkoutVideo::class);
    }

    public function video()
    {
        return $this->hasOne(WorkoutVideo::class);
    }

    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class);
    }

    public function scheduleWorkouts()
    {
        return $this->hasMany(WorkoutSchedule::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function favourits()
    {
        return $this->belongsToMany(User::class, 'favourit_workout', 'workout_id', 'user_id')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(WorkoutImage::class)->where('user_id', auth()->id());
    }

    public function watchedVideo()
    {
        return $this->hasOne(UserWorkout::class)->select('id', 'workout_id', 'watched_time', 'is_played')->where('user_id', auth()->id());
    }

    public function isFavourited()
    {
        return $this->favourits()->where('user_id', auth()->id());
    }

    public function scopeIsPremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%');
    }

    public function scopeFilterByCategory($query, $category)
    {
        return $query->where('category_id', $category);
    }

    public function scopeFilterByStatus($query, $status)
    {
        $status = $status === "true" ? 1 : 0;
        return $query->where('status', $status);
    }

    public function scopeActiveWorkout($query)
    {
        return $query->where('status', 1);
    }
}
