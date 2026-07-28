<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\JobRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('providerProfile', 'district', 'area');

        $profile = $user->providerProfile;
        if (!$profile) {
            return redirect()->route('provider.profile.setup');
        }

        $stats = [
            'total_jobs'       => $profile->total_jobs,
            'active_bookings'  => Booking::where('provider_id', $user->id)->whereIn('status', ['confirmed', 'in_progress'])->count(),
            'completed'        => Booking::where('provider_id', $user->id)->where('status', 'completed')->count(),
            'total_earned'     => Booking::where('provider_id', $user->id)->where('status', 'completed')->sum('provider_earning'),
            'pending_bids'     => $user->bids()->where('status', 'pending')->count(),
            'rating'           => $profile->rating_avg,
            'total_reviews'    => $profile->total_reviews,
            'profile_complete' => $profile->completion_percentage,
        ];

        $newJobs = JobRequest::open()
            ->forProvider($user)
            ->with('subcategory.category', 'district', 'area', 'seeker')
            ->latest()
            ->take(6)
            ->get();

        $recentBookings = Booking::where('provider_id', $user->id)
            ->with('seeker', 'jobRequest', 'directService')
            ->latest()
            ->take(5)
            ->get();

        $unreadMessages = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)->count();

        return view('provider.dashboard', compact(
            'user', 'profile', 'stats', 'newJobs', 'recentBookings', 'unreadMessages'
        ));
    }
}
