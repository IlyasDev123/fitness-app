<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'type',
        'status',
    ];

    public function getTypeAttribute($value)
    {
        return  match ($value) {
            1 =>  'Term and condition',
            2 =>  'Privacy policy',
            default => 'Privacy policy',
        };
    }
}
