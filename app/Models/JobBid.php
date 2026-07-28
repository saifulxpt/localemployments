<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBid extends Model
{
    protected $fillable = [
        'job_request_id', 'provider_id', 'bid_amount', 'message',
        'estimated_hours', 'status', 'is_highlighted',
    ];

    protected $casts = ['is_highlighted' => 'boolean'];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'bid_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
