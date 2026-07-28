<?php

namespace App\Services;

use App\Models\Setting;

class CommissionService
{
    /**
     * Calculate platform commission and provider earning.
     * Returns array: [service_amount, platform_fee, provider_earning]
     */
    public function calculate(float $amount): array
    {
        $rate = Setting::get('commission_rate', 12) / 100;
        $fee  = round($amount * $rate, 2);

        return [
            'service_amount'   => $amount,
            'platform_fee'     => $fee,
            'provider_earning' => round($amount - $fee, 2),
        ];
    }

    /**
     * Get current commission rate percentage.
     */
    public function getRate(): int
    {
        return (int) Setting::get('commission_rate', 12);
    }
}
