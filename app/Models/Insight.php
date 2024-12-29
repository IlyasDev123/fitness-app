<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insight extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category_id',
        'is_featured',
        'status',
        'duration',
        'thumbnail',
    ];

    protected $hidden = [
        'is_featured'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function files()
    {
        return $this->hasMany(InsightFile::class);
    }

    public function image()
    {
        return $this->hasOne(InsightFile::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function favourites()
    {
        return $this->belongsToMany(User::class, 'favourit_insight', 'insight_id', 'user_id')->withTimestamps();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'like_insight', 'insight_id', 'user_id')->withTimestamps();
    }

    public function isFavourited()
    {
        return $this->favourites()->where('user_id', auth()->id());
    }

    public function isLiked()
    {
        return $this->likes()->where('user_id', auth()->id());
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('title', 'like', '%' . $searchTerm . '%');
    }

    public function getThumbnailAttribute($value)
    {
        return $value ? env('STORAGE_IMAGE_PATH') . $value : null;
    }
}
