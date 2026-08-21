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

    /**
     * Smart redirect for 'My Jobs' navigation tab based on auth and role
     */
    public function myJobs()
    {
        if (!auth()->check()) {
            session()->flash('info', 'আপনার পোস্ট করা কাজ বা বিড দেখতে অনুগ্রহ করে লগইন করুন।');
            return redirect()->guest(route('login'));
        }

        $user = auth()->user();

        if ($user->isSeeker()) {
            return redirect()->route('seeker.job-requests.index');
        }

        if ($user->isProvider()) {
            return redirect()->route('provider.bids.index');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.job-requests.index');
        }

        return redirect()->route('home');
    }

    /**
     * Smart redirect for 'Post Job' (+) button
     */
    public function postJob()
    {
        if (!auth()->check()) {
            session()->flash('info', 'নতুন কাজ পোস্ট করতে অনুগ্রহ করে লগইন করুন।');
            return redirect()->guest(route('login'));
        }

        $user = auth()->user();

        if ($user->isSeeker()) {
            return redirect()->route('seeker.job-requests.create');
        }

        if ($user->isProvider()) {
            session()->flash('info', 'প্রোভাইডার হিসেবে আপনি উন্মুক্ত কাজসমূহে বিড করতে পারেন।');
            return redirect()->route('jobs.index');
        }

        return redirect()->route('seeker.job-requests.create');
    }
}

