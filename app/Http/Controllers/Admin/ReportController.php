<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');

        $from = match ($period) {
            'week'  => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year'  => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $stats = [
            'new_users'        => User::where('created_at', '>=', $from)->count(),
            'new_bookings'     => Booking::where('created_at', '>=', $from)->count(),
            'completed'        => Booking::where('status', 'completed')->where('completed_at', '>=', $from)->count(),
            'total_revenue'    => Payment::completed()->where('paid_at', '>=', $from)->sum('amount'),
            'platform_income'  => Booking::where('status', 'completed')->where('completed_at', '>=', $from)->sum('platform_fee'),
            'total_paid_out'   => WithdrawalRequest::where('status', 'approved')->where('processed_at', '>=', $from)->sum('amount'),
        ];

        $dailyRevenue = Booking::where('status', 'completed')
            ->where('completed_at', '>=', $from)
            ->selectRaw('DATE(completed_at) as date, SUM(service_amount) as total, SUM(platform_fee) as commission')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact('stats', 'dailyRevenue', 'period'));
    }

    public function export(Request $request)
    {
        $bookings = Booking::with('seeker', 'provider')
            ->where('status', 'completed')
            ->get();

        $csv = "Booking Ref,Seeker,Provider,Amount,Commission,Date\n";
        foreach ($bookings as $b) {
            $csv .= implode(',', [
                $b->booking_ref,
                $b->seeker->name,
                $b->provider->name,
                $b->service_amount,
                $b->platform_fee,
                $b->completed_at?->format('Y-m-d'),
            ]) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="localemployments-report-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}
