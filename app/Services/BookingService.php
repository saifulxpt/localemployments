<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\DirectService;
use App\Models\JobBid;
use App\Models\JobRequest;
use App\Models\User;

class BookingService
{
    public function __construct(private CommissionService $commission) {}

    /**
     * Create a booking from an accepted bid.
     */
    public function createFromBid(JobRequest $jobRequest, JobBid $bid): Booking
    {
        $commission = $this->commission->calculate($bid->bid_amount);

        $booking = Booking::create([
            'booking_type'    => 'job_request',
            'job_request_id'  => $jobRequest->id,
            'bid_id'          => $bid->id,
            'seeker_id'       => $jobRequest->seeker_id,
            'provider_id'     => $bid->provider_id,
            'booking_ref'     => $this->generateRef(),
            'service_date'    => $jobRequest->preferred_date ?? now()->addDay()->toDateString(),
            'service_time'    => $jobRequest->preferred_time,
            'location_detail' => $jobRequest->address_detail,
            'service_amount'  => $commission['service_amount'],
            'platform_fee'    => $commission['platform_fee'],
            'provider_earning'=> $commission['provider_earning'],
            'status'          => 'pending',
        ]);

        // Update job request and bid statuses
        $jobRequest->update(['status' => 'assigned']);
        $bid->update(['status' => 'accepted']);

        // Reject all other bids
        $jobRequest->bids()
            ->where('id', '!=', $bid->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        // Update provider total_jobs
        $bid->provider->providerProfile?->increment('total_jobs');

        return $booking;
    }

    /**
     * Create a direct booking (no bid involved).
     */
    public function createFromDirect(DirectService $service, User $seeker, array $data): Booking
    {
        $commission = $this->commission->calculate($service->price);

        $booking = Booking::create([
            'booking_type'      => 'direct',
            'direct_service_id' => $service->id,
            'seeker_id'         => $seeker->id,
            'provider_id'       => $service->provider_id,
            'booking_ref'       => $this->generateRef(),
            'service_date'      => $data['service_date'],
            'service_time'      => $data['service_time'] ?? null,
            'location_detail'   => $data['location_detail'] ?? null,
            'seeker_note'       => $data['seeker_note'] ?? null,
            'service_amount'    => $commission['service_amount'],
            'platform_fee'      => $commission['platform_fee'],
            'provider_earning'  => $commission['provider_earning'],
            'status'            => 'pending',
        ]);

        // Increment service booking count
        $service->increment('total_bookings');

        return $booking;
    }

    /**
     * Generate unique booking reference: "LE-2024-00001"
     */
    public function generateRef(): string
    {
        $year  = date('Y');
        $count = Booking::withTrashed()->count() + 1;
        return 'LE-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Mark booking as complete and update provider stats.
     */
    public function complete(Booking $booking): void
    {
        $booking->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        // Update job request status
        $booking->jobRequest?->update(['status' => 'completed']);

        // Update provider profile stats
        $profile = $booking->provider->providerProfile;
        if ($profile) {
            // Recalculate average rating
            $avgRating = $booking->provider->reviewsReceived()->avg('rating');
            $totalReviews = $booking->provider->reviewsReceived()->count();
            $profile->update([
                'rating_avg'    => round($avgRating ?? 0, 2),
                'total_reviews' => $totalReviews,
            ]);
        }
    }
}
