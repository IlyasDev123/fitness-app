<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSubscriptionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_subscription_id',
        'in_app_id',
        'inapp_response',
        'expire_date',
        'in_app_type',
        'user_id',
        'package_id'
    ];

    protected $casts = [
        'inapp_response' => 'array',
        'created_at' => 'datetime:F d Y H:i:s',
        'expire_date' => 'datetime:F d Y H:i:s',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
