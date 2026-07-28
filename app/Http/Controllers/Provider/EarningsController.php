<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\WithdrawalRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalEarned = Booking::where('provider_id', $user->id)->where('status', 'completed')->sum('provider_earning');
        $withdrawn   = WithdrawalRequest::where('provider_id', $user->id)->where('status', 'approved')->sum('amount');
        $pending     = WithdrawalRequest::where('provider_id', $user->id)->whereIn('status', ['pending', 'processing'])->sum('amount');
        $available   = max(0, $totalEarned - $withdrawn - $pending);

        $monthlyEarnings = Booking::where('provider_id', $user->id)
            ->where('status', 'completed')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(provider_earning) as total')
            ->groupBy('year', 'month')
            ->orderByDesc('year')->orderByDesc('month')
            ->take(12)->get();

        $recentBookings = Booking::where('provider_id', $user->id)
            ->where('status', 'completed')
            ->with('seeker')
            ->latest('completed_at')
            ->paginate(15);

        return view('provider.earnings.index', compact(
            'totalEarned', 'withdrawn', 'pending', 'available', 'monthlyEarnings', 'recentBookings'
        ));
    }
}
