<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SocialLogin;
use App\Models\Notification;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'timezone',
        'email_verified_at',
        'status',
        'is_premium',
    ];

    protected $appends = [
        'is_verified_email',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_premium' => 'boolean',
    ];

    public function getIsVerifiedEmailAttribute(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Get the user's device.
     */
    public function device()
    {
        return $this->hasOne(Device::class);
    }

    /**
     * Get the user's otp.
     */
    public function otp()
    {
        return $this->hasOne(Otp::class);
    }

    public function socialLogin()
    {
        return $this->hasMany(SocialLogin::class, 'user_id', 'id');
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? env('STORAGE_IMAGE_PATH') . $value : null,
        );
    }

    public function userWorkouts()
    {
        return $this->hasMany(UserWorkout::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }
}
