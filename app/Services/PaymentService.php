<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private string $storeId;
    private string $storePassword;
    private bool $isSandbox;
    private string $initiateUrl;
    private string $validateUrl;

    public function __construct()
    {
        $this->storeId       = Setting::get('sslcommerz_store_id', config('services.sslcommerz.store_id', ''));
        $this->storePassword = Setting::get('sslcommerz_store_password', Setting::get('sslcommerz_store_passwd', config('services.sslcommerz.store_password', '')));
        $this->isSandbox     = (bool) Setting::get('sslcommerz_sandbox', true);

        $this->initiateUrl  = $this->isSandbox
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $this->validateUrl  = $this->isSandbox
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
    }

    /**
     * Initiate payment — returns redirect URL.
     */
    public function initiate(Booking $booking): string
    {
        $seeker = $booking->seeker;
        $tranId = 'LE-' . $booking->id . '-' . time();

        // Create payment record
        $booking->payment()->create([
            'seeker_id'      => $booking->seeker_id,
            'amount'         => $booking->service_amount,
            'currency'       => 'BDT',
            'gateway'        => 'sslcommerz',
            'transaction_id' => $tranId,
            'status'         => 'processing',
        ]);

        $postData = [
            'store_id'       => $this->storeId,
            'store_passwd'   => $this->storePassword,
            'total_amount'   => $booking->service_amount,
            'currency'       => 'BDT',
            'tran_id'        => $tranId,
            'success_url'    => route('seeker.payments.success'),
            'fail_url'       => route('seeker.payments.fail'),
            'cancel_url'     => route('seeker.payments.cancel'),
            'ipn_url'        => route('seeker.payments.ipn'),
            'cus_name'       => $seeker->name,
            'cus_email'      => $seeker->email ?? 'customer@localemployments.com',
            'cus_phone'      => $seeker->phone,
            'cus_add1'       => $booking->location_detail ?? 'Bangladesh',
            'cus_city'       => $seeker->district?->name ?? 'Dhaka',
            'cus_country'    => 'Bangladesh',
            'shipping_method'=> 'NO',
            'product_name'   => 'Service Booking ' . $booking->booking_ref,
            'product_category'=> 'Service',
            'product_profile' => 'general',
        ];

        try {
            $response = Http::asForm()->post($this->initiateUrl, $postData);
            $data = $response->json();

            if (isset($data['GatewayPageURL'])) {
                return $data['GatewayPageURL'];
            }
        } catch (\Throwable $e) {
            Log::error('SSLCommerz initiate failed: ' . $e->getMessage());
        }

        throw new \RuntimeException('Payment gateway initiation failed.');
    }

    /**
     * Verify IPN (Instant Payment Notification) from SSLCommerz.
     */
    public function verifyIpn(Request $request): bool
    {
        $valId = $request->input('val_id');

        if (!$valId) return false;

        try {
            $response = Http::get($this->validateUrl, [
                'val_id'       => $valId,
                'store_id'     => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format'       => 'json',
            ]);

            $data = $response->json();

            if (($data['status'] ?? '') === 'VALID' || ($data['status'] ?? '') === 'VALIDATED') {
                $payment = \App\Models\Payment::where('transaction_id', $data['tran_id'] ?? '')->first();

                if ($payment && $payment->status !== 'completed') {
                    $payment->update([
                        'val_id'           => $valId,
                        'gateway_response' => $data,
                        'status'           => 'completed',
                        'paid_at'          => now(),
                        'payment_method'   => $data['card_type'] ?? 'sslcommerz',
                    ]);

                    // Update booking status
                    $payment->booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
                }
                return true;
            }
        } catch (\Throwable $e) {
            Log::error('SSLCommerz IPN verification failed: ' . $e->getMessage());
        }

        return false;
    }
}
