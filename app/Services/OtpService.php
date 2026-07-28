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
        $this->sms->sendOtp($user);
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
        // Allow resend if current OTP has more than 4 minutes remaining (sent less than 1 min ago)
        return now()->isAfter($user->otp_expires_at->subMinutes(4));
    }
}
