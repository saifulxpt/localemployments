<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'password', 'role', 'avatar',
        'district_id', 'area_id', 'address',
        'otp', 'otp_expires_at', 'phone_verified', 'status', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token', 'otp'];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'last_login_at'  => 'datetime',
        'phone_verified' => 'boolean',
    ];

    // ─────────────────────────────────────────
    // Role Helpers
    // ─────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isSeeker(): bool
    {
        return $this->role === 'seeker';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // ─────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function providerSkills()
    {
        return $this->hasMany(ProviderSkill::class, 'provider_id');
    }

    public function verificationDoc()
    {
        return $this->hasOne(ProviderVerificationDoc::class, 'provider_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'seeker_id');
    }

    public function bids()
    {
        return $this->hasMany(JobBid::class, 'provider_id');
    }

    public function bookingsAsSeeker()
    {
        return $this->hasMany(Booking::class, 'seeker_id');
    }

    public function bookingsAsProvider()
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    public function directServices()
    {
        return $this->hasMany(DirectService::class, 'provider_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'provider_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    // ─────────────────────────────────────────
    // Attribute Helpers
    // ─────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=0B4F3C&size=128';
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->userNotifications()->where('is_read', false)->count();
    }
}
