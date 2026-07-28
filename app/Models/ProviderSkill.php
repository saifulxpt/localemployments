<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSkill extends Model
{
    protected $fillable = ['provider_id', 'subcategory_id'];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(ServiceSubcategory::class);
    }
}
