<?php

namespace App\Http\Controllers\Provider;

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
        $bookings = Booking::where('provider_id', Auth::id())
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->with(['seeker', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->latest()
            ->get();
        return view('provider.messages.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        $booking->load('seeker', 'messages.sender');

        Message::where('booking_id', $booking->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('provider.messages.show', compact('booking'));
    }

    public function send(Request $request, Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);
        $request->validate(['message' => ['required', 'string', 'max:2000']]);

        $message = Message::create([
            'booking_id'  => $booking->id,
            'sender_id'   => Auth::id(),
            'receiver_id' => $booking->seeker_id,
            'message'     => $request->message,
        ]);

        $this->notify->newMessage($booking->seeker, $message);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function poll(Booking $booking)
    {
        abort_if($booking->provider_id !== Auth::id(), 403);

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

        Message::where('booking_id', $booking->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }
}
