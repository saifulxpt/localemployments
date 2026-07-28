<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_type', 'job_request_id', 'bid_id', 'direct_service_id',
        'seeker_id', 'provider_id', 'booking_ref', 'service_date', 'service_time',
        'location_detail', 'service_amount', 'platform_fee', 'provider_earning',
        'seeker_note', 'provider_note', 'status',
        'confirmed_at', 'started_at', 'completed_at', 'cancelled_at',
        'cancelled_by', 'cancel_reason',
    ];

    protected $casts = [
        'service_date'  => 'date',
        'confirmed_at'  => 'datetime',
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'cancelled_at'  => 'datetime',
    ];

    // ─────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class);
    }

    public function bid()
    {
        return $this->belongsTo(JobBid::class);
    }

    public function directService()
    {
        return $this->belongsTo(DirectService::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function dispute()
    {
        return $this->hasOne(Dispute::class);
    }

    public function cancelledByUser()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ─────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────

    public function scopeForProvider($query, int $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function scopeForSeeker($query, int $seekerId)
    {
        return $query->where('seeker_id', $seekerId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // ─────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeReviewed(): bool
    {
        return $this->isCompleted() && !$this->review;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'     => ['text' => 'অপেক্ষমাণ', 'class' => 'badge-yellow'],
            'confirmed'   => ['text' => 'নিশ্চিত', 'class' => 'badge-blue'],
            'in_progress' => ['text' => 'চলমান', 'class' => 'badge-green'],
            'completed'   => ['text' => 'সম্পন্ন', 'class' => 'badge-green'],
            'disputed'    => ['text' => 'বিতর্কিত', 'class' => 'badge-red'],
            'cancelled'   => ['text' => 'বাতিল', 'class' => 'badge-gray'],
            'refunded'    => ['text' => 'ফেরত', 'class' => 'badge-yellow'],
            default       => ['text' => $this->status, 'class' => 'badge-gray'],
        };
    }
}
