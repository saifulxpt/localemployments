<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('booking.seeker', 'booking.provider')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $payments = $query->paginate(20)->withQueryString();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        return view('admin.payments.index', compact('payments', 'totalRevenue'));
    }
}
