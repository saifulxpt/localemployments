<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',              'value' => 'LocalEmployments',         'type' => 'string',  'group' => 'general',  'description' => 'Website name'],
            ['key' => 'site_tagline',           'value' => 'আপনার এলাকায়, আপনার মানুষ', 'type' => 'string', 'group' => 'general', 'description' => 'Site tagline'],
            ['key' => 'contact_phone',          'value' => '',                          'type' => 'string',  'group' => 'general',  'description' => 'Contact phone number'],
            ['key' => 'contact_email',          'value' => '',                          'type' => 'string',  'group' => 'general',  'description' => 'Contact email'],

            // Commission
            ['key' => 'commission_rate',        'value' => '12',                        'type' => 'integer', 'group' => 'commission','description' => 'Platform commission percentage'],
            ['key' => 'min_withdrawal',         'value' => '200',                       'type' => 'integer', 'group' => 'commission','description' => 'Minimum withdrawal amount (BDT)'],
            ['key' => 'max_bid_per_job',        'value' => '10',                        'type' => 'integer', 'group' => 'commission','description' => 'Max bids allowed per job request'],
            ['key' => 'job_request_expiry_days','value' => '7',                         'type' => 'integer', 'group' => 'commission','description' => 'Days before job request auto-expires'],

            // Featured
            ['key' => 'featured_price_30days',  'value' => '500',                       'type' => 'integer', 'group' => 'featured',  'description' => 'Featured price for 30 days (BDT)'],
            ['key' => 'featured_price_7days',   'value' => '150',                       'type' => 'integer', 'group' => 'featured',  'description' => 'Featured price for 7 days (BDT)'],

            // SMS (BulkSMSBD)
            ['key' => 'bulksms_api_key',        'value' => '',                          'type' => 'string',  'group' => 'sms',       'description' => 'BulkSMSBD API key'],
            ['key' => 'bulksms_sender_id',      'value' => '8809617611169',             'type' => 'string',  'group' => 'sms',       'description' => 'BulkSMSBD sender ID'],

            // Payment (SSLCommerz)
            ['key' => 'sslcommerz_store_id',    'value' => '',                          'type' => 'string',  'group' => 'payment',   'description' => 'SSLCommerz store ID'],
            ['key' => 'sslcommerz_store_password','value' => '',                        'type' => 'string',  'group' => 'payment',   'description' => 'SSLCommerz store password'],
            ['key' => 'sslcommerz_sandbox',     'value' => 'true',                      'type' => 'boolean', 'group' => 'payment',   'description' => 'Use sandbox mode'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
