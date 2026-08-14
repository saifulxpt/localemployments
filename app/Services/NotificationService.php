<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send an in-app notification to a user.
     */
    public function send(
        User $user,
        string $title,
        string $message,
        string $type,
        ?string $url = null,
        array $data = []
    ): void {
        Notification::create([
            'user_id'    => $user->id,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'data'       => $data,
            'action_url' => $url,
        ]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllRead(User $user): void
    {
        $user->userNotifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get unread count and last 5 notifications for the bell component.
     */
    public function getBellData(User $user): array
    {
        return [
            'count'         => $user->userNotifications()->unread()->count(),
            'notifications' => $user->userNotifications()
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'title'      => $n->title,
                    'message'    => $n->message,
                    'type'       => $n->type,
                    'icon'       => $n->icon,
                    'url'        => $n->action_url,
                    'is_read'    => $n->is_read,
                    'time_ago'   => time_ago_bn($n->created_at),
                ])
                ->toArray(),
        ];
    }

    // ─────────────────────────────────────────
    // Specific notification senders
    // ─────────────────────────────────────────

    public function bidReceived(User $seeker, \App\Models\JobBid $bid): void
    {
        $this->send(
            $seeker,
            'নতুন বিড পেয়েছেন',
            "{$bid->provider->name} আপনার \"{$bid->jobRequest->title}\" কাজে বিড করেছেন।",
            'bid',
            route('seeker.job-requests.show', $bid->job_request_id),
            ['bid_id' => $bid->id, 'provider_id' => $bid->provider_id]
        );
    }

    public function bidAccepted(User $provider, \App\Models\JobBid $bid): void
    {
        $this->send(
            $provider,
            'বিড গ্রহণ করা হয়েছে',
            "আপনার বিড গ্রহণ করা হয়েছে। কাজ: \"{$bid->jobRequest->title}\"",
            'bid',
            route('provider.bookings.index'),
            ['bid_id' => $bid->id]
        );
    }

    public function bookingConfirmed(User $user, \App\Models\Booking $booking): void
    {
        $url = $user->isProvider()
            ? route('provider.bookings.show', $booking->id)
            : route('seeker.bookings.show', $booking->id);

        $this->send(
            $user,
            'বুকিং নিশ্চিত',
            "বুকিং {$booking->booking_ref} নিশ্চিত হয়েছে।",
            'booking',
            $url,
            ['booking_id' => $booking->id]
        );
    }

    public function bookingCompleted(User $user, \App\Models\Booking $booking): void
    {
        $url = $user->isProvider()
            ? route('provider.bookings.show', $booking->id)
            : route('seeker.bookings.show', $booking->id);

        $this->send(
            $user,
            'সেবা সম্পন্ন',
            "বুকিং {$booking->booking_ref} সম্পন্ন হয়েছে। রেটিং দিতে ভুলবেন না।",
            'booking',
            $url,
            ['booking_id' => $booking->id]
        );
    }

    public function newMessage(User $receiver, \App\Models\Message $message): void
    {
        $url = $receiver->isProvider()
            ? route('provider.messages.show', $message->booking_id)
            : route('seeker.messages.show', $message->booking_id);

        $this->send(
            $receiver,
            'নতুন বার্তা',
            "{$message->sender->name} আপনাকে একটি বার্তা পাঠিয়েছেন।",
            'booking',
            $url,
            ['booking_id' => $message->booking_id]
        );
    }

    public function paymentReceived(User $provider, \App\Models\Booking $booking): void
    {
        $this->send(
            $provider,
            'পেমেন্ট গৃহীত',
            "বুকিং {$booking->booking_ref} এর জন্য ৳" . format_taka($booking->provider_earning, false) . " আপনার ওয়ালেটে যোগ হবে।",
            'payment',
            route('provider.earnings.index'),
            ['booking_id' => $booking->id]
        );
    }
}
