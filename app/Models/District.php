<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['name', 'bn_name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function areas()
    {
        return $this->hasMany(Area::class);
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
}
