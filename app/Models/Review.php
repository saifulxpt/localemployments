<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id', 'reviewer_id', 'reviewee_id', 'rating', 'comment', 'is_visible', 'admin_note',
    ];

    protected $casts = ['is_visible' => 'boolean'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function getStarsHtmlAttribute(): string
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            $class = $i <= $this->rating ? 'star-filled' : 'star-empty';
            $html .= "<span class=\"{$class}\">★</span>";
        }
        return $html;
    }
}
