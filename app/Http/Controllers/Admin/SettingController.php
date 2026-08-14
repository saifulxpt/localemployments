<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AdminActivityLog;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $excludeGroups = ['api', 'sms', 'sslcommerz', 'payment', 'smtp', 'mail', 'social'];
        $excludeKeys = [
            'bulksms_api_key', 'bulksms_sender_id', 'sms_active',
            'sslcommerz_store_id', 'sslcommerz_store_passwd', 'sslcommerz_sandbox',
            'bkash_app_key', 'bkash_app_secret', 'bkash_username', 'bkash_password', 'bkash_sandbox',
            'nagad_merchant_id', 'nagad_public_key', 'nagad_private_key', 'nagad_sandbox',
            'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            'google_client_id', 'google_client_secret', 'google_login_active'
        ];

        $settings = Setting::whereNotIn('group', $excludeGroups)
            ->whereNotIn('key', $excludeKeys)
            ->orderBy('group')
            ->orderBy('key')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            $setting = Setting::firstOrCreate(
                ['key' => $key],
                ['group' => 'general', 'type' => 'string', 'display_name' => ucwords(str_replace('_', ' ', $key))]
            );
            
            if ($request->hasFile($key) && $request->file($key)->isValid()) {
                $file = $request->file($key);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $setting->update(['value' => 'uploads/settings/' . $filename]);
            } elseif ($setting->type !== 'file') {
                $setting->update(['value' => $value]);
            }
        }

        AdminActivityLog::record('Updated platform settings');

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }

    /**
     * Display API Settings tabbed page.
     */
    public function apiIndex(SmsService $smsService)
    {
        $smsBalanceData = $smsService->getBalance();
        
        $apiSettings = [
            // SMS Gateway Settings
            'bulksms_api_key'    => Setting::get('bulksms_api_key', config('services.bulksms.api_key', '')),
            'bulksms_sender_id'  => Setting::get('bulksms_sender_id', config('services.bulksms.sender_id', '8809617611169')),
            'sms_active'         => Setting::get('sms_active', '1'),

            // Payment Gateway Settings
            'bkash_app_key'      => Setting::get('bkash_app_key', ''),
            'bkash_app_secret'   => Setting::get('bkash_app_secret', ''),
            'bkash_username'     => Setting::get('bkash_username', ''),
            'bkash_password'     => Setting::get('bkash_password', ''),
            'bkash_sandbox'      => Setting::get('bkash_sandbox', '1'),

            'nagad_merchant_id'  => Setting::get('nagad_merchant_id', ''),
            'nagad_public_key'   => Setting::get('nagad_public_key', ''),
            'nagad_private_key'  => Setting::get('nagad_private_key', ''),
            'nagad_sandbox'      => Setting::get('nagad_sandbox', '1'),

            'sslcommerz_store_id'     => Setting::get('sslcommerz_store_id', ''),
            'sslcommerz_store_passwd' => Setting::get('sslcommerz_store_passwd', ''),
            'sslcommerz_sandbox'      => Setting::get('sslcommerz_sandbox', '1'),

            // Mail / SMTP Settings
            'mail_host'          => Setting::get('mail_host', config('mail.mailers.smtp.host', '')),
            'mail_port'          => Setting::get('mail_port', config('mail.mailers.smtp.port', '587')),
            'mail_username'      => Setting::get('mail_username', config('mail.mailers.smtp.username', '')),
            'mail_password'      => Setting::get('mail_password', config('mail.mailers.smtp.password', '')),
            'mail_encryption'    => Setting::get('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_from_address'  => Setting::get('mail_from_address', config('mail.from.address', '')),
            'mail_from_name'     => Setting::get('mail_from_name', config('mail.from.name', '')),

            // Social Login API
            'google_client_id'     => Setting::get('google_client_id', ''),
            'google_client_secret' => Setting::get('google_client_secret', ''),
            'google_login_active'  => Setting::get('google_login_active', '0'),
        ];

        return view('admin.settings.api', compact('apiSettings', 'smsBalanceData'));
    }

    /**
     * Update API Settings.
     */
    public function updateApi(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '', 'group' => 'api', 'type' => 'string']
            );
        }

        AdminActivityLog::record('Updated API Configuration Settings');

        return back()->with('success', 'API কনফিগারেশন সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * AJAX endpoint to check real-time BulkSMSBD balance.
     */
    public function checkSmsBalance(SmsService $smsService)
    {
        $balanceData = $smsService->getBalance();
        return response()->json($balanceData);
    }

    /**
     * Send a test SMS to verify BulkSMSBD configuration.
     */
    public function testSms(Request $request, SmsService $smsService)
    {
        $request->validate([
            'test_phone' => ['required', 'string', 'regex:/^01[3-9][0-9]{8}$/'],
        ], [
            'test_phone.required' => 'ফোন নম্বর দিন।',
            'test_phone.regex'    => 'সঠিক ফোন নম্বর দিন (01XXXXXXXXX)।',
        ]);

        $otp     = rand(100000, 999999);
        $message = "LocalEmployments: টেস্ট OTP - {$otp}। এটি একটি পরীক্ষামূলক বার্তা।";
        $success = $smsService->send($request->test_phone, $message, 'test');

        if ($success) {
            return back()->with('sms_test_success', "✅ টেস্ট SMS সফলভাবে {$request->test_phone} নম্বরে পাঠানো হয়েছে! OTP: {$otp}");
        }

        return back()->with('sms_test_error', '❌ SMS পাঠানো ব্যর্থ হয়েছে। SMS Logs চেক করুন।');
    }
}
