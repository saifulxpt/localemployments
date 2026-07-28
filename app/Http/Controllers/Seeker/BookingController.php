<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        private NotificationService $notify,
        private SmsService $sms
    ) {}

    public function index()
    {
        $bookings = Booking::where('seeker_id', Auth::id())
            ->with('provider.providerProfile', 'directService', 'jobRequest')
            ->latest()
            ->paginate(10);

        return view('seeker.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        $booking->load('provider.providerProfile', 'seeker', 'jobRequest', 'directService', 'payment', 'review');
        return view('seeker.bookings.show', compact('booking'));
    }

    public function complete(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if($booking->status !== 'in_progress', 403);

        $booking->update(['status' => 'completed', 'completed_at' => now()]);
        $booking->jobRequest?->update(['status' => 'completed']);

        // Notify provider
        $this->notify->bookingCompleted($booking->provider, $booking);

        return back()->with('success', 'সেবা সম্পন্ন হিসেবে চিহ্নিত হয়েছে। একটি রেটিং দিন!');
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending', 'confirmed']), 403);

        $booking->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancel_reason'=> request('cancel_reason'),
        ]);

        return back()->with('success', 'বুকিং বাতিল করা হয়েছে।');
    }
}
