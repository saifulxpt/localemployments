<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'data', 'action_url', 'is_read', 'read_at',
    ];

    protected $casts = [
        'data'     => 'array',
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'booking'  => '📋',
            'payment'  => '💳',
            'bid'      => '🏷️',
            'review'   => '⭐',
            'system'   => '🔔',
            default    => '📢',
        };
    }
}
