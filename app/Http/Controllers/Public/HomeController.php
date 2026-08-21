<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Booking;
use App\Models\JobRequest;

class HomeController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::active()->take(20)->get();

        $latestJobs = JobRequest::with(['subcategory.category', 'district', 'area', 'seeker'])
            ->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->take(6)
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

        return view('public.home', compact('categories', 'latestJobs', 'stats'));
    }
}

