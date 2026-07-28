<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seeker_id', 'subcategory_id', 'title', 'description',
        'district_id', 'area_id', 'address_detail',
        'budget_min', 'budget_max', 'preferred_date', 'preferred_time',
        'flexibility', 'photos', 'status', 'expires_at', 'total_bids',
    ];

    protected $casts = [
        'photos'         => 'array',
        'preferred_date' => 'date',
        'expires_at'     => 'datetime',
    ];

    // ─────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ServiceSubcategory::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function bids()
    {
        return $this->hasMany(JobBid::class);
    }

    public function acceptedBid()
    {
        return $this->hasOne(JobBid::class)->where('status', 'accepted');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    // ─────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeForProvider($query, User $provider)
    {
        $skillIds = $provider->providerSkills()->pluck('subcategory_id');
        return $query->whereIn('subcategory_id', $skillIds)
                     ->where('district_id', $provider->district_id);
    }

    // ─────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function getBudgetDisplayAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return '৳' . number_format($this->budget_min) . ' - ৳' . number_format($this->budget_max);
        }
        if ($this->budget_min) {
            return '৳' . number_format($this->budget_min) . '+';
        }
        return 'আলোচনা সাপেক্ষ';
    }

    public function getFlexibilityBadgeAttribute(): array
    {
        return match ($this->flexibility) {
            'urgent'   => ['text' => 'জরুরি', 'class' => 'badge-red'],
            'fixed'    => ['text' => 'নির্দিষ্ট', 'class' => 'badge-blue'],
            default    => ['text' => 'নমনীয়', 'class' => 'badge-green'],
        };
    }
}
