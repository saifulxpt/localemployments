<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if(!$booking->isCompleted(), 403, 'শুধুমাত্র সম্পন্ন বুকিং রেটিং দেওয়া যায়।');
        abort_if($booking->review, 403, 'এই বুকিং এ ইতোমধ্যে রেটিং দেওয়া হয়েছে।');

        $booking->load('provider.providerProfile');
        return view('seeker.reviews.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if(!$booking->canBeReviewed(), 403);

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'booking_id'  => $booking->id,
            'reviewer_id' => Auth::id(),
            'reviewee_id' => $booking->provider_id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        // Recalculate provider rating
        $profile = $booking->provider->providerProfile;
        if ($profile) {
            $avg = $booking->provider->reviewsReceived()->visible()->avg('rating');
            $total = $booking->provider->reviewsReceived()->visible()->count();
            $profile->update(['rating_avg' => round($avg, 2), 'total_reviews' => $total]);
        }

        return redirect()->route('seeker.bookings.show', $booking)
            ->with('success', 'রেটিং সফলভাবে দেওয়া হয়েছে। ধন্যবাদ!');
    }
}
