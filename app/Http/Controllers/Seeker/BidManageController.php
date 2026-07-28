<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobBid;
use App\Services\BookingService;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidManageController extends Controller
{
    public function __construct(
        private BookingService $booking,
        private NotificationService $notify,
        private SmsService $sms
    ) {}

    public function accept(JobBid $bid)
    {
        $jobRequest = $bid->jobRequest;
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        abort_if(!$jobRequest->isOpen(), 403, 'এই অনুরোধে আর বিড গ্রহণ করা যাচ্ছে না।');
        abort_if($bid->status !== 'pending', 403);

        $booking = $this->booking->createFromBid($jobRequest, $bid);

        // Notify provider
        $this->notify->bidAccepted($bid->provider, $bid);
        $this->sms->sendBidAccepted($bid->provider, $jobRequest);

        return redirect()->route('seeker.bookings.show', $booking)
            ->with('success', 'বিড গ্রহণ করা হয়েছে! বুকিং তৈরি হয়েছে।');
    }

    public function reject(JobBid $bid)
    {
        $jobRequest = $bid->jobRequest;
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        abort_if($bid->status !== 'pending', 403);

        $bid->update(['status' => 'rejected']);

        return back()->with('success', 'বিডটি প্রত্যাখ্যান করা হয়েছে।');
    }
}
