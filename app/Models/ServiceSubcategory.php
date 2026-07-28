<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSubcategory extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function providerSkills()
    {
        return $this->hasMany(ProviderSkill::class, 'subcategory_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'subcategory_id');
    }

    public function directServices()
    {
        return $this->hasMany(DirectService::class, 'subcategory_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
