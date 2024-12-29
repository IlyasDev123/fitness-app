<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'type',
        'status',
        'is_read',
        'notifiable_id',
        'notifiable_type',
    ];

    protected $casts = [
        'created_at' => 'datetime:F d Y H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
