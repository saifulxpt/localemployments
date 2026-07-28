<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct(private NotificationService $notify) {}

    public function index()
    {
        // Get distinct bookings where user participated in messages
        $bookings = Booking::where('seeker_id', Auth::id())
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->with(['provider.providerProfile', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()
            ->get();

        return view('seeker.messages.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);
        $booking->load('provider.providerProfile', 'messages.sender');

        // Mark received messages as read
        Message::where('booking_id', $booking->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('seeker.messages.show', compact('booking'));
    }

    public function send(Request $request, Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);

        $request->validate(['message' => ['required', 'string', 'max:2000']]);

        $message = Message::create([
            'booking_id'  => $booking->id,
            'sender_id'   => Auth::id(),
            'receiver_id' => $booking->provider_id,
            'message'     => $request->message,
        ]);

        // Notify provider
        $this->notify->newMessage($booking->provider, $message);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function poll(Booking $booking)
    {
        abort_if($booking->seeker_id !== Auth::id(), 403);

        $messages = Message::where('booking_id', $booking->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'           => $m->id,
                'message'      => $m->message,
                'is_mine'      => $m->sender_id === Auth::id(),
                'sender_name'  => $m->sender->name,
                'sender_avatar'=> $m->sender->avatar_url,
                'formatted_time' => $m->created_at->format('h:i A'),
                'created_at'   => $m->created_at->toISOString(),
            ]);

        // Mark as read
        Message::where('booking_id', $booking->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }
}
