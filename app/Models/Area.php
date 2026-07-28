<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['district_id', 'name', 'bn_name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('name');
    }

    public function scopeForDistrict($query, int $districtId)
    {
        return $query->where('district_id', $districtId);
    }
}
