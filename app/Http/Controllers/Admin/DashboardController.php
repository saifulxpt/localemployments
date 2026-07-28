<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\JobRequest;
use App\Models\WithdrawalRequest;
use App\Models\Review;
use App\Models\Dispute;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Platform stats
        $stats = [
            'total_users'     => User::count(),
            'total_seekers'   => User::where('role', 'seeker')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'total_bookings'  => Booking::count(),
            'completed'       => Booking::where('status', 'completed')->count(),
            'total_revenue'   => Payment::where('status', 'completed')->sum('amount'),
            'platform_income' => Booking::where('status', 'completed')->sum('platform_fee'),
            'open_jobs'       => JobRequest::where('status', 'open')->count(),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'open_disputes'   => Dispute::where('status', 'open')->count(),
            'pending_verifications' => User::where('role', 'provider')
                ->whereHas('providerProfile', fn($q) => $q->where('verification_status', 'pending'))
                ->count(),
        ];

        // Revenue last 7 days
        $revenueChart = Booking::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(platform_fee) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent registrations
        $recentUsers = User::latest()->take(5)->get();

        // Recent bookings
        $recentBookings = Booking::with('seeker', 'provider')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'revenueChart', 'recentUsers', 'recentBookings'
        ));
    }
}
