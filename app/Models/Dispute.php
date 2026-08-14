<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'booking_id', 'raised_by', 'against', 'reason', 'description',
        'evidence_photos', 'status', 'resolution', 'resolved_by', 'resolved_at',
    ];

    protected $casts = [
        'evidence_photos' => 'array',
        'resolved_at'     => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function raisedBy()
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function against()
    {
        return $this->belongsTo(User::class, 'against');
    }

    public function againstUser()
    {
        return $this->belongsTo(User::class, 'against');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
