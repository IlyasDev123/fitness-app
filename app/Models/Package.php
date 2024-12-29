<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'inapp_package_id',
        'inapp_android_package',
        'price',
        'duration',
        'description',
        'is_active',
    ];

    public function setDurationAttribute($key, $value)
    {
        $this->attributes['duration'] = match ($value) {
            'Daily' => 1,
            'Weekly' => 2,
            'Monthly' => 3,
            '3 Months' => 4,
            '6 Months' => 5,
            'Yearly' => 6,
            'Lifetime' => 7,
        };
    }
    public function getDurationAttribute($value)
    {
        return  match ($value) {
            1 =>  'Daily',
            2 =>  'Weekly',
            3 =>  'Monthly',
            4 =>  '3 Months',
            5 =>  '6 Months',
            6 =>  'Yearly',
            7 =>  'Lifetime',
            default => 'Lifetime',
        };
    }

    public function userSubscriptionHistory()
    {
        return $this->hasMany(UserSubscriptionHistory::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
