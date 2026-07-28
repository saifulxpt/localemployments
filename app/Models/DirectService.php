<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectService extends Model
{
    protected $fillable = [
        'provider_id', 'subcategory_id', 'title', 'description', 'price',
        'price_type', 'estimated_duration', 'service_areas', 'photos', 'is_active', 'total_bookings',
    ];

    protected $casts = [
        'service_areas' => 'array',
        'photos'        => 'array',
        'is_active'     => 'boolean',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ServiceSubcategory::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getPriceDisplayAttribute(): string
    {
        $price = '৳' . number_format($this->price);
        return match ($this->price_type) {
            'hourly'        => $price . '/ঘণ্টা',
            'starting_from' => $price . ' থেকে',
            default         => $price,
        };
    }
}
