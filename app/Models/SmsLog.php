<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'phone', 'message', 'type', 'status', 'gateway_response',
    ];

    protected $casts = [
        'gateway_response' => 'array',
    ];

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
