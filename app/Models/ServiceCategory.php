<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'banner_image', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function subcategories()
    {
        return $this->hasMany(ServiceSubcategory::class, 'category_id');
    }

    public function activeSubcategories()
    {
        return $this->hasMany(ServiceSubcategory::class, 'category_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function jobRequests()
    {
        return $this->hasManyThrough(JobRequest::class, ServiceSubcategory::class, 'category_id', 'subcategory_id');
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
