<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('district', 'area');

        $stats = [
            'active_requests'  => JobRequest::where('seeker_id', $user->id)->whereIn('status', ['open', 'assigned'])->count(),
            'total_bookings'   => Booking::where('seeker_id', $user->id)->count(),
            'completed'        => Booking::where('seeker_id', $user->id)->where('status', 'completed')->count(),
            'pending_review'   => Booking::where('seeker_id', $user->id)->where('status', 'completed')
                                         ->whereDoesntHave('review')->count(),
        ];

        $recentRequests = JobRequest::where('seeker_id', $user->id)
            ->with('subcategory.category', 'district')
            ->latest()->take(5)->get();

        $recentBookings = Booking::where('seeker_id', $user->id)
            ->with('provider', 'directService')
            ->latest()->take(5)->get();

        $unreadMessages = \App\Models\Message::where('receiver_id', $user->id)
            ->where('is_read', false)->count();

        return view('seeker.dashboard', compact('user', 'stats', 'recentRequests', 'recentBookings', 'unreadMessages'));
    }
}
