<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderVerificationDoc extends Model
{
    protected $fillable = [
        'provider_id', 'nid_front', 'nid_back', 'selfie_with_nid',
        'other_docs', 'admin_note', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'other_docs'  => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
