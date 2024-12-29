<?php

namespace App\Models;

use App\Models\Package;
use App\Models\UserSubscriptionHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'in_app_id',
        'expire_date',
        'is_active',
    ];

    protected $casts = [
        'expire_date' => 'datetime:F d Y H:i:s',
        'is_active' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSubscriptionHistory()
    {
        return $this->hasMany(UserSubscriptionHistory::class);
    }
}
