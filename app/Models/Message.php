<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'booking_id', 'sender_id', 'receiver_id', 'message', 'attachment', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function isMine(int $userId): bool
    {
        return $this->sender_id === $userId;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'is_mine'          => auth()->check() && $this->isMine(auth()->id()),
            'sender_name'      => $this->sender?->name,
            'sender_avatar'    => $this->sender?->avatar_url,
            'formatted_time'   => $this->created_at->format('h:i A'),
        ]);
    }
}
