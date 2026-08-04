<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private ?string $apiKey;
    private ?string $senderId;
    private string $apiUrl = 'http://bulksmsbd.net/api/smsapi';

    public function __construct()
    {
        $this->apiKey   = (string) Setting::get('bulksms_api_key', config('services.bulksms.api_key', ''));
        $this->senderId = (string) Setting::get('bulksms_sender_id', config('services.bulksms.sender_id', '8809617611169'));
    }

    /**
     * Fetch real-time BulkSMSBD balance.
     */
    public function getBalance(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'balance' => '0.00',
                'message' => 'API Key সেট করা হয়নি (Dev Mode)',
            ];
        }

        try {
            $response = Http::get('http://bulksmsbd.net/api/getBalanceApi', [
                'api_key' => $this->apiKey,
            ]);

            $body = $response->json();

            if (isset($body['balance'])) {
                return [
                    'success' => true,
                    'balance' => number_format((float) $body['balance'], 2),
                    'message' => 'সফলভাবে ব্যালেন্স পাওয়ার তথ্য এসেছে',
                    'raw'     => $body,
                ];
            }

            if ($response->successful()) {
                $rawBody = trim($response->body());
                if (is_numeric($rawBody)) {
                    return [
                        'success' => true,
                        'balance' => number_format((float) $rawBody, 2),
                        'message' => 'সফলভাবে ব্যালেন্স পাওয়া গেছে',
                    ];
                }
            }

            return [
                'success' => false,
                'balance' => '0.00',
                'message' => $body['message'] ?? 'ব্যালেন্স আনা সম্ভব হয়নি।',
                'raw'     => $body ?? $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error("BulkSMSBD Balance check error: " . $e->getMessage());
            return [
                'success' => false,
                'balance' => '0.00',
                'message' => 'এরর: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS to a phone number.
     */
    public function send(string $phone, string $message, string $type = 'notification'): bool
    {
        // Normalize BD phone number
        $phone = $this->normalizePhone($phone);

        $success = false;
        $gatewayResponse = [];

        try {
            if (empty($this->apiKey)) {
                // In dev mode without API key — log only
                Log::info("SMS (dev mode) to {$phone}: {$message}");
                $success = true;
                $gatewayResponse = ['dev_mode' => true, 'message' => $message];
            } else {
                $response = Http::post($this->apiUrl, [
                    'api_key'  => $this->apiKey,
                    'type'     => 'text',
                    'number'   => $phone,
                    'senderid' => $this->senderId,
                    'message'  => $message,
                ]);

                $body = $response->json();
                $gatewayResponse = $body ?? [];
                $success = isset($body['response_code']) && $body['response_code'] == 202;
            }
        } catch (\Throwable $e) {
            Log::error("SMS failed to {$phone}: " . $e->getMessage());
            $gatewayResponse = ['error' => $e->getMessage()];
        }

        // Log every SMS attempt
        SmsLog::create([
            'phone'            => $phone,
            'message'          => $message,
            'type'             => $type,
            'status'           => $success ? 'sent' : 'failed',
            'gateway_response' => $gatewayResponse,
        ]);

        return $success;
    }

    /**
     * Generate OTP, save to user, and send via SMS.
     */
    public function sendOtp(\App\Models\User $user): string
    {
        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $message = "LocalEmployments: আপনার OTP কোড হলো {$otp}। এটি ৫ মিনিটের মধ্যে মেয়াদ শেষ হবে। কারো সাথে শেয়ার করবেন না।";

        $this->send($user->phone, $message, 'otp');

        return $otp; // Only for logging, never expose to users
    }

    /**
     * Send booking confirmation SMS.
     */
    public function sendBookingConfirmation(\App\Models\Booking $booking, \App\Models\User $user): void
    {
        $serviceDate = $booking->service_date ? $booking->service_date->format('d M Y') : '';
        $message = "LocalEmployments: আপনার বুকিং {$booking->booking_ref} নিশ্চিত হয়েছে। তারিখ: {$serviceDate}।";
        $this->send($user->phone, $message, 'booking');
    }

    /**
     * Send bid accepted notification.
     */
    public function sendBidAccepted(\App\Models\User $provider, \App\Models\JobRequest $jobRequest): void
    {
        $message = "LocalEmployments: আপনার বিড গ্রহণ করা হয়েছে। কাজ: {$jobRequest->title}। অ্যাপে লগইন করুন।";
        $this->send($provider->phone, $message, 'bid');
    }

    /**
     * Send withdrawal processed notification.
     */
    public function sendWithdrawalProcessed(\App\Models\WithdrawalRequest $withdrawal): void
    {
        $message = "LocalEmployments: আপনার ৳" . number_format($withdrawal->amount) . " উত্তোলন অনুরোধ অনুমোদিত হয়েছে।";
        $this->send($withdrawal->provider->phone, $message, 'withdrawal');
    }

    /**
     * Normalize Bangladeshi phone number to 88XXXXXXXXXX format.
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '88' . $phone;
        } elseif (!str_starts_with($phone, '88')) {
            $phone = '88' . $phone;
        }
        return $phone;
    }
}
