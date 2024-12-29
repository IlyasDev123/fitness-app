<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'status',
        'sort_order'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSort($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('question', 'like', '%' . $search . '%');
    }
}
