<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private NotificationService $notify,
        private SmsService $sms,
    ) {}

    public function index()
    {
        $bookings = Booking::where('provider_id', Auth::id())
            ->with('seeker', 'jobRequest', 'directService')
            ->latest()
            ->paginate(10);
        return view('provider.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        $booking->load('seeker', 'jobRequest', 'directService', 'payment', 'review');
        return view('provider.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        abort_if($booking->status !== 'pending', 403);

        $booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        $this->notify->bookingConfirmed($booking->seeker, $booking);
        $this->sms->sendBookingConfirmation($booking, $booking->seeker);

        return back()->with('success', 'বুকিং নিশ্চিত করা হয়েছে।');
    }

    public function start(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        abort_if($booking->status !== 'confirmed', 403);

        $booking->update(['status' => 'in_progress', 'started_at' => now()]);
        return back()->with('success', 'কাজ শুরু করা হয়েছে।');
    }

    public function complete(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        abort_if($booking->status !== 'in_progress', 403);

        $this->bookingService->complete($booking);
        $this->notify->bookingCompleted($booking->seeker, $booking);
        $this->notify->paymentReceived($booking->provider, $booking);

        return back()->with('success', 'কাজ সম্পন্ন হয়েছে! পেমেন্ট প্রক্রিয়াধীন।');
    }

    public function cancel(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['pending', 'confirmed']), 403);

        $booking->update([
            'status'        => 'cancelled',
            'cancelled_at'  => now(),
            'cancelled_by'  => Auth::id(),
            'cancel_reason' => request('cancel_reason'),
        ]);

        return back()->with('success', 'বুকিং বাতিল করা হয়েছে।');
    }
}
