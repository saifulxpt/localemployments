<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'provider_id', 'amount', 'method', 'account_number', 'account_name',
        'bank_name', 'branch_name', 'routing_number', 'status', 'admin_note',
        'processed_by', 'processed_at',
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForProvider($query, int $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'    => ['text' => 'অপেক্ষমাণ', 'class' => 'badge-yellow'],
            'processing' => ['text' => 'প্রক্রিয়াধীন', 'class' => 'badge-blue'],
            'approved'   => ['text' => 'অনুমোদিত', 'class' => 'badge-green'],
            'rejected'   => ['text' => 'প্রত্যাখ্যাত', 'class' => 'badge-red'],
            default      => ['text' => $this->status, 'class' => 'badge-gray'],
        };
    }
}
