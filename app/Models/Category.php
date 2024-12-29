<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'type',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return match ($value) {
                    '1' =>  'Workout',
                    '2' => 'Insight',
                    default => 'Workout',
                };
            },
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
