<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Booking;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::active()->take(20)->get();

        $featuredProviders = User::where('role', 'provider')
            ->whereHas('providerProfile', fn($q) => $q->where('is_featured', true)->where('featured_until', '>', now()))
            ->with('providerProfile', 'district', 'providerSkills.subcategory')
            ->take(8)
            ->get();

        $stats = [
            'providers'  => User::where('role', 'provider')->count(),
            'districts'  => \App\Models\District::active()->count(),
            'jobs'       => Booking::where('status', 'completed')->count(),
            'rating'     => round(
                \App\Models\Review::where('is_visible', true)->avg('rating') ?? 4.8,
                1
            ),
        ];

        return view('public.home', compact('categories', 'featuredProviders', 'stats'));
    }
}
