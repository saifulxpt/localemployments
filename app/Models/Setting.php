<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) return $default;

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'integer' => (int) $setting->value,
            'json'    => json_decode($setting->value, true),
            default   => $setting->value,
        };
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    /**
     * Get all settings as a grouped array.
     */
    public static function getAllGrouped(): array
    {
        return static::all()->groupBy('group')->toArray();
    }
}
