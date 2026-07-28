<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payment) {}

    public function initiate(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if($booking->payment?->isCompleted(), 403, 'এই বুকিং এ ইতোমধ্যে পেমেন্ট হয়েছে।');

        try {
            $url = $this->payment->initiate($booking);
            return redirect($url);
        } catch (\RuntimeException $e) {
            return back()->with('error', 'পেমেন্ট শুরু করা যায়নি। পরে আবার চেষ্টা করুন।');
        }
    }

    public function success(Request $request)
    {
        $this->payment->verifyIpn($request);
        return redirect()->route('seeker.bookings.index')->with('success', 'পেমেন্ট সফল হয়েছে!');
    }

    public function fail(Request $request)
    {
        return redirect()->route('seeker.bookings.index')->with('error', 'পেমেন্ট ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('seeker.bookings.index')->with('info', 'পেমেন্ট বাতিল করা হয়েছে।');
    }

    public function ipn(Request $request)
    {
        $this->payment->verifyIpn($request);
        return response('OK', 200);
    }
}
