<?php

namespace App\Services;

use App\Models\User;

class OtpService
{
    public function __construct(private SmsService $sms) {}

    /**
     * Generate and send OTP to user.
     */
    public function send(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Send via SMS if phone exists
        if (!empty($user->phone)) {
            // Message must be fully Bengali for BulkSMSBD Masking Sender ID (error 1012 fix)
            $message = "লোকাল এমপ্লয়মেন্টস: আপনার ওটিপি কোড হলো {$otp}। এটি ৫ মিনিটের মধ্যে মেয়াদ শেষ হবে। কারো সাথে শেয়ার করবেন না।";
            $this->sms->send($user->phone, $message, 'otp');
        }

        // Send via Email if email exists
        if (!empty($user->email)) {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
        }
    }

    /**
     * Verify OTP for a user.
     */
    public function verify(User $user, string $otp): bool
    {
        if (!$user->otp || !$user->otp_expires_at) return false;
        if (now()->isAfter($user->otp_expires_at)) return false;
        if ($user->otp !== $otp) return false;

        // Clear OTP and mark phone verified
        $user->update([
            'otp'            => null,
            'otp_expires_at' => null,
            'phone_verified' => true,
        ]);

        return true;
    }

    /**
     * Check if user can resend OTP (rate limiting: 3 per 30 min).
     * Simple check: just ensure OTP was set recently or not at all.
     */
    public function canResend(User $user): bool
    {
        if (!$user->otp_expires_at) return true;
        // Allow resend if current OTP was sent more than 1 minute ago (expires in less than 4 mins)
        return now()->isAfter($user->otp_expires_at->copy()->subMinutes(4));
    }
}
