<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\DirectService;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectBookingController extends Controller
{
    public function __construct(private BookingService $booking) {}

    public function create(DirectService $directService)
    {
        abort_if(!$directService->is_active, 404);
        $directService->load('provider.providerProfile', 'subcategory.category');
        return view('seeker.direct-booking.create', compact('directService'));
    }

    public function store(Request $request, DirectService $directService)
    {
        abort_if(!$directService->is_active, 404);

        $data = $request->validate([
            'service_date'   => ['required', 'date', 'after_or_equal:today'],
            'service_time'   => ['nullable', 'string'],
            'location_detail'=> ['nullable', 'string', 'max:500'],
            'seeker_note'    => ['nullable', 'string', 'max:500'],
        ]);

        $booking = $this->booking->createFromDirect($directService, Auth::user(), $data);

        return redirect()->route('seeker.bookings.show', $booking)
            ->with('success', 'বুকিং সফলভাবে তৈরি হয়েছে! প্রোভাইডার শীঘ্রই নিশ্চিত করবেন।');
    }
}
