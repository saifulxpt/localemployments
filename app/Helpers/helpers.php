<?php

if (!function_exists('format_taka')) {
    /**
     * Format amount as Bangladeshi Taka
     * format_taka(1500) → "৳1,500"
     */
    function format_taka(float $amount, bool $symbol = true): string
    {
        $formatted = number_format($amount, 0, '.', ',');
        return $symbol ? '৳' . $formatted : $formatted;
    }
}

if (!function_exists('format_bd_phone')) {
    /**
     * Format BD phone number for display
     * format_bd_phone("01711234567") → "017-1123-4567"
     */
    function format_bd_phone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 11) {
            return substr($phone, 0, 3) . '-' . substr($phone, 3, 4) . '-' . substr($phone, 7);
        }
        return $phone;
    }
}

if (!function_exists('time_ago_bn')) {
    /**
     * Returns time ago in Bengali
     */
    function time_ago_bn(\Carbon\Carbon|string $date): string
    {
        $date = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        $diff = $date->diffInSeconds(now());

        if ($diff < 60)           return 'এইমাত্র';
        if ($diff < 3600)         return (int)($diff / 60) . ' মিনিট আগে';
        if ($diff < 86400)        return (int)($diff / 3600) . ' ঘণ্টা আগে';
        if ($diff < 604800)       return (int)($diff / 86400) . ' দিন আগে';
        if ($diff < 2592000)      return (int)($diff / 604800) . ' সপ্তাহ আগে';
        if ($diff < 31536000)     return (int)($diff / 2592000) . ' মাস আগে';
        return (int)($diff / 31536000) . ' বছর আগে';
    }
}

if (!function_exists('rating_stars')) {
    /**
     * Returns HTML string of star rating
     */
    function rating_stars(float $rating, int $max = 5): string
    {
        $html = '';
        for ($i = 1; $i <= $max; $i++) {
            if ($i <= floor($rating)) {
                $html .= '<span class="star-filled">★</span>';
            } elseif ($i - 0.5 <= $rating) {
                $html .= '<span class="star-filled" style="opacity:0.6">★</span>';
            } else {
                $html .= '<span class="star-empty">★</span>';
            }
        }
        return $html;
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncate text to given length with ellipsis
     */
    function truncate(string $text, int $length = 100, string $end = '...'): string
    {
        if (mb_strlen($text) <= $length) return $text;
        return mb_substr($text, 0, $length) . $end;
    }
}

if (!function_exists('booking_ref')) {
    /**
     * Generate a unique booking reference
     * e.g. "LE-2024-00001"
     */
    function booking_ref(): string
    {
        $year = date('Y');
        $count = \App\Models\Booking::withTrashed()->count() + 1;
        return 'LE-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('setting')) {
    /**
     * Get a setting value with optional default
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('active_class')) {
    /**
     * Return 'active' CSS class if current route matches
     */
    function active_class(string|array $routeName, string $activeClass = 'active', string $inactiveClass = ''): string
    {
        return request()->routeIs($routeName) ? $activeClass : $inactiveClass;
    }
}

if (!function_exists('flex_badge')) {
    /**
     * Return flexibility label in Bengali
     */
    function flex_badge(string $flexibility): string
    {
        return match ($flexibility) {
            'urgent'   => '<span class="badge badge-red">জরুরি</span>',
            'fixed'    => '<span class="badge badge-blue">নির্দিষ্ট</span>',
            default    => '<span class="badge badge-green">নমনীয়</span>',
        };
    }
}
