<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedSubscription extends Model
{
    protected $fillable = [
        'provider_id', 'amount', 'duration_days', 'starts_at', 'ends_at', 'payment_id', 'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('ends_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }
}
