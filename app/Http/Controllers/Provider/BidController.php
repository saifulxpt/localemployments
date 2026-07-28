<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\JobBid;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function __construct(private NotificationService $notify) {}

    public function index()
    {
        $bids = JobBid::where('provider_id', Auth::id())
            ->with('jobRequest.subcategory.category', 'jobRequest.district', 'jobRequest.seeker')
            ->latest()
            ->paginate(10);
        return view('provider.bids.index', compact('bids'));
    }

    public function store(Request $request, JobRequest $jobRequest)
    {
        $user = Auth::user();

        // Check: job is open
        abort_if(!$jobRequest->isOpen(), 403, 'এই কাজে আর বিড করা যাচ্ছে না।');

        // Check: hasn't already bid
        if ($jobRequest->bids()->where('provider_id', $user->id)->exists()) {
            return back()->with('error', 'আপনি ইতোমধ্যে এই কাজে বিড করেছেন।');
        }

        // Check: max bids not reached
        $maxBids = (int) setting('max_bid_per_job', 10);
        if ($jobRequest->bids()->count() >= $maxBids) {
            return back()->with('error', 'এই কাজে সর্বোচ্চ বিড সংখ্যা পূর্ণ হয়েছে।');
        }

        $data = $request->validate([
            'bid_amount'     => ['required', 'numeric', 'min:50'],
            'message'        => ['required', 'string', 'min:20', 'max:1000'],
            'estimated_hours'=> ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $bid = JobBid::create(array_merge($data, [
            'job_request_id' => $jobRequest->id,
            'provider_id'    => $user->id,
        ]));

        // Increment bid count
        $jobRequest->increment('total_bids');

        // Notify seeker
        $this->notify->bidReceived($jobRequest->seeker, $bid);

        return back()->with('success', 'বিড সফলভাবে দেওয়া হয়েছে!');
    }

    public function update(Request $request, JobBid $bid)
    {
        abort_if($bid->provider_id !== Auth::id(), 403);
        abort_if($bid->status !== 'pending', 403);

        $data = $request->validate([
            'bid_amount'     => ['required', 'numeric', 'min:50'],
            'message'        => ['required', 'string', 'min:20', 'max:1000'],
            'estimated_hours'=> ['nullable', 'integer', 'min:1'],
        ]);

        $bid->update($data);
        return back()->with('success', 'বিড আপডেট হয়েছে।');
    }

    public function destroy(JobBid $bid)
    {
        abort_if($bid->provider_id !== Auth::id(), 403);
        abort_if($bid->status !== 'pending', 403);

        $bid->update(['status' => 'withdrawn']);
        $bid->jobRequest->decrement('total_bids');

        return back()->with('success', 'বিড প্রত্যাহার করা হয়েছে।');
    }
}
