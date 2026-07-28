<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'experience_years', 'nid_number', 'profile_photo',
        'portfolio_photos', 'availability', 'is_verified', 'verified_at',
        'verification_status', 'rating_avg', 'total_reviews', 'total_jobs',
        'is_featured', 'featured_until', 'hourly_rate_min', 'hourly_rate_max', 'response_rate',
    ];

    protected $casts = [
        'portfolio_photos'  => 'array',
        'availability'      => 'array',
        'is_verified'       => 'boolean',
        'is_featured'       => 'boolean',
        'verified_at'       => 'datetime',
        'featured_until'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFeaturedActive(): bool
    {
        return $this->is_featured && $this->featured_until && $this->featured_until->isFuture();
    }

    public function getCompletionPercentageAttribute(): int
    {
        $fields = ['bio', 'experience_years', 'profile_photo', 'hourly_rate_min'];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        $base = (int) round(($filled / count($fields)) * 70);
        $skills = $this->user->providerSkills()->count() > 0 ? 20 : 0;
        $verified = $this->is_verified ? 10 : 0;
        return min(100, $base + $skills + $verified);
    }
}
