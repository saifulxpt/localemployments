<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('seeker', 'provider')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $bookings = $query->paginate(20)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load('seeker', 'provider', 'jobRequest', 'directService', 'payment', 'review', 'dispute', 'messages.sender');
        return view('admin.bookings.show', compact('booking'));
    }
}
