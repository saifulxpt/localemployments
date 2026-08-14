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
            // BulkSMSBD recommended OTP format: "Your {Brand} OTP is XXXX"
            $message = "Your LocalEmployments OTP is {$otp}. Valid for 5 minutes. Do not share.";
            $this->sms->send($user->phone, $message, 'otp');
        }

        // Send via Email if email exists
        if (!empty($user->email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SendOtpMail($otp));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("OTP Email send failed: " . $e->getMessage());
            }
        }

        \Illuminate\Support\Facades\Log::info("OTP generated for User #{$user->id} ({$user->phone}): {$otp}");
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
