<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'seeker_id', 'amount', 'currency', 'payment_method',
        'gateway', 'transaction_id', 'val_id', 'session_key',
        'gateway_response', 'status', 'paid_at', 'refunded_at', 'refund_ref',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
        'refunded_at'      => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
