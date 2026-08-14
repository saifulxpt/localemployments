<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Booking;
use App\Models\AdminActivityLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function __construct(private NotificationService $notify) {}

    public function index(Request $request)
    {
        $query = Dispute::with('booking.seeker', 'booking.provider', 'raisedBy')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $disputes = $query->paginate(20)->withQueryString();
        return view('admin.disputes.index', compact('disputes'));
    }

    public function show(Dispute $dispute)
    {
        $dispute->load('booking.seeker', 'booking.provider', 'raisedBy', 'resolvedBy');
        return view('admin.disputes.show', compact('dispute'));
    }

    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate(['resolution' => ['required', 'string', 'min:10']]);

        $dispute->update([
            'status'      => 'resolved',
            'resolution'  => $request->resolution,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        // Notify both parties
        if ($dispute->raisedBy) {
            $this->notify->send($dispute->raisedBy, 'বিরোধ সমাধান হয়েছে', $request->resolution, 'system');
        }
        if ($dispute->againstUser) {
            $this->notify->send($dispute->againstUser, 'বিরোধ সমাধান হয়েছে', $request->resolution, 'system');
        }

        AdminActivityLog::record("Resolved dispute #{$dispute->id}", $dispute);

        return back()->with('success', 'বিরোধ সমাধান করা হয়েছে।');
    }
}
