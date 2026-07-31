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

if (!function_exists('category_icon')) {
    function category_icon(?string $name, string $classes = 'w-6 h-6'): string
    {
        $svgs = [
            'sparkles' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'wrench' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'bolt' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
            'sun' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'paint-brush' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
            'scissors' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>',
            'fire' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>',
            'heart' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
            'academic-cap' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>',
            'bug-ant' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4m15.364 6.364l-1.414-1.414M5.05 6.464l-1.414 1.414m12.728 0l1.414 1.414M6.464 17.657l-1.414-1.414M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'cloud' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>',
            'truck' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',
            'computer-desktop' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
            'camera' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            'calendar' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
            'leaf' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'shield-check' => '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        ];

        return $svgs[$name] ?? '<svg class="'.$classes.'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>';
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
