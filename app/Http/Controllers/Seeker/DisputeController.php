<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ImageService;

class DisputeController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function create(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if(!in_array($booking->status, ['in_progress', 'completed']), 403);
        abort_if($booking->dispute, 403, 'এই বুকিং এ ইতোমধ্যে একটি বিরোধ দায়ের করা হয়েছে।');

        return view('seeker.disputes.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        abort_if($booking->dispute, 403);

        $data = $request->validate([
            'reason'          => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string', 'min:20'],
            'evidence_photos' => ['nullable', 'array', 'max:3'],
            'evidence_photos.*'=> ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $photos = [];
        if ($request->hasFile('evidence_photos')) {
            $photos = $this->image->storeMultiple($request->file('evidence_photos'), 'dispute-evidence');
        }

        Dispute::create([
            'booking_id'      => $booking->id,
            'raised_by'       => Auth::id(),
            'against'         => $booking->provider_id,
            'reason'          => $data['reason'],
            'description'     => $data['description'],
            'evidence_photos' => $photos,
        ]);

        $booking->update(['status' => 'disputed']);

        return redirect()->route('seeker.bookings.show', $booking)
            ->with('success', 'বিরোধ দায়ের হয়েছে। আমাদের টিম শীঘ্রই পর্যালোচনা করবে।');
    }
}
