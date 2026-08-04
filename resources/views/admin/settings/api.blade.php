@extends('layouts.admin')

@section('title', 'API সেটিং সমূহ')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="{ activeTab: 'sms', checkingBalance: false, balance: '{{ $smsBalanceData['balance'] }}', balanceMsg: '{{ $smsBalanceData['message'] }}' }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">API কনফিগারেশন সেটিংস</h1>
            <p class="text-sm text-gray-500">BulkSMSBD, পেমেন্ট গেটওয়ে, ইমেইল এবং সোশ্যাল লগইন API কনফিগার করুন।</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.settings.index') }}" class="btn btn-outline border-gray-200 text-sm">
                সাধারণ সেটিংস
            </a>
        </div>
    </div>

    {{-- BulkSMSBD Real-Time Balance Card --}}
    <div class="rounded-3xl p-6 md:p-8 shadow-2xl border border-emerald-500/20 relative overflow-hidden text-white" style="background: linear-gradient(135deg, #0f172a 0%, #042f2e 50%, #064e3b 100%) !important; color: #ffffff !important;">
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full blur-2xl pointer-events-none" style="background: rgba(13, 148, 136, 0.25);"></div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/20" style="background: rgba(255, 255, 255, 0.15);">
                    💬
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-md" style="background: rgba(255, 255, 255, 0.2); color: #ccfbf1;">BulkSMSBD API Status</span>
                        <span class="w-2 h-2 rounded-full animate-ping" style="background: #34d399;"></span>
                    </div>
                    <h2 class="text-xl font-bold mt-1" style="color: #ffffff !important;">লাইভ বাল্ক এসএমএস বিডি অ্যাকাউন্ট ব্যালেন্স</h2>
                    <p class="text-xs mt-0.5" style="color: #e6fffa !important;" x-text="balanceMsg"></p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-2xl border border-white/20 w-full md:w-auto justify-between md:justify-start" style="background: rgba(255, 255, 255, 0.15);">
                <div>
                    <div class="text-xs font-bold uppercase" style="color: #ccfbf1;">বর্তমান ব্যালেন্স</div>
                    <div class="text-3xl font-black flex items-center gap-1" style="color: #ffffff !important;">
                        ৳ <span x-text="balance"></span>
                    </div>
                </div>
                <button type="button" 
                        @click="checkingBalance = true; fetch('{{ route('admin.settings.api.sms-balance') }}').then(res => res.json()).then(data => { balance = data.balance; balanceMsg = data.message; checkingBalance = false; }).catch(() => { checkingBalance = false; })"
                        class="btn px-4 py-2 text-xs font-bold shadow-md flex items-center gap-1.5 transition-all" style="background: #ffffff !important; color: #0f766e !important;">
                    <svg class="w-4 h-4" :class="{ 'animate-spin': checkingBalance }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>রিফ্রেশ</span>
                </button>
            </div>
        </div>
    </div>

    {{-- API Tabs Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-2 flex flex-wrap gap-2 overflow-x-auto">
        <button type="button" @click="activeTab = 'sms'" 
                :class="activeTab === 'sms' ? 'bg-primary-600 text-white font-bold shadow-sm' : 'text-gray-600 hover:bg-gray-50 font-medium'"
                class="px-5 py-3 rounded-xl text-sm transition-all flex items-center gap-2">
            <span>💬</span> BulkSMSBD (SMS API)
        </button>

        <button type="button" @click="activeTab = 'payment'" 
                :class="activeTab === 'payment' ? 'bg-primary-600 text-white font-bold shadow-sm' : 'text-gray-600 hover:bg-gray-50 font-medium'"
                class="px-5 py-3 rounded-xl text-sm transition-all flex items-center gap-2">
            <span>💳</span> পেমেন্ট গেটওয়ে API
        </button>

        <button type="button" @click="activeTab = 'email'" 
                :class="activeTab === 'email' ? 'bg-primary-600 text-white font-bold shadow-sm' : 'text-gray-600 hover:bg-gray-50 font-medium'"
                class="px-5 py-3 rounded-xl text-sm transition-all flex items-center gap-2">
            <span>📧</span> Email / SMTP API
        </button>

        <button type="button" @click="activeTab = 'social'" 
                :class="activeTab === 'social' ? 'bg-primary-600 text-white font-bold shadow-sm' : 'text-gray-600 hover:bg-gray-50 font-medium'"
                class="px-5 py-3 rounded-xl text-sm transition-all flex items-center gap-2">
            <span>🔑</span> Google / Social Login API
        </button>
    </div>

    {{-- Main Form Body --}}
    <form action="{{ route('admin.settings.api.update') }}" method="POST">
        @csrf

        {{-- TAB 1: BulkSMSBD Settings --}}
        <div x-show="activeTab === 'sms'" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 space-y-6">
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">BulkSMSBD API সেটিংস</h3>
                    <p class="text-xs text-gray-500">আপনার bulksmsbd.net অ্যাকাউন্ট থেকে পাওয়া API Key ও Sender ID দিন।</p>
                </div>
                <a href="https://bulksmsbd.net" target="_blank" class="text-xs font-bold text-primary-600 hover:underline">BulkSMSBD ওয়েবসাইট ↗</a>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">BulkSMSBD API Key</label>
                    <input type="text" name="bulksms_api_key" value="{{ $apiSettings['bulksms_api_key'] }}" class="input w-full" placeholder="যেমন: 458xxxxxxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sender ID / Masking ID</label>
                    <input type="text" name="bulksms_sender_id" value="{{ $apiSettings['bulksms_sender_id'] }}" class="input w-full" placeholder="যেমন: 8809617611169">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMS সার্ভিস স্ট্যাটাস</label>
                    <select name="sms_active" class="input w-full">
                        <option value="1" {{ $apiSettings['sms_active'] == '1' ? 'selected' : '' }}>চালু (Active)</option>
                        <option value="0" {{ $apiSettings['sms_active'] == '0' ? 'selected' : '' }}>বন্ধ (Disabled)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TAB 2: Payment Gateways --}}
        <div x-show="activeTab === 'payment'" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 space-y-8">
            
            {{-- bKash Section --}}
            <div class="space-y-4">
                <h3 class="text-base font-bold text-pink-600 border-b border-pink-100 pb-2 flex items-center gap-2">
                    <span>📱</span> bKash Payment API
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash App Key</label>
                        <input type="text" name="bkash_app_key" value="{{ $apiSettings['bkash_app_key'] }}" class="input w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash App Secret</label>
                        <input type="text" name="bkash_app_secret" value="{{ $apiSettings['bkash_app_secret'] }}" class="input w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash Merchant Username</label>
                        <input type="text" name="bkash_username" value="{{ $apiSettings['bkash_username'] }}" class="input w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">bKash Merchant Password</label>
                        <input type="password" name="bkash_password" value="{{ $apiSettings['bkash_password'] }}" class="input w-full text-sm">
                    </div>
                </div>
            </div>

            {{-- Nagad Section --}}
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-base font-bold text-orange-600 border-b border-orange-100 pb-2 flex items-center gap-2">
                    <span>📱</span> Nagad Payment API
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nagad Merchant ID</label>
                        <input type="text" name="nagad_merchant_id" value="{{ $apiSettings['nagad_merchant_id'] }}" class="input w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nagad Public Key</label>
                        <textarea name="nagad_public_key" rows="2" class="input w-full text-sm">{{ $apiSettings['nagad_public_key'] }}</textarea>
                    </div>
                </div>
            </div>

            {{-- SSLCommerz Section --}}
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h3 class="text-base font-bold text-blue-600 border-b border-blue-100 pb-2 flex items-center gap-2">
                    <span>💳</span> SSLCommerz / Aamarpay Gateway
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Store ID</label>
                        <input type="text" name="sslcommerz_store_id" value="{{ $apiSettings['sslcommerz_store_id'] }}" class="input w-full text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Store Password</label>
                        <input type="text" name="sslcommerz_store_passwd" value="{{ $apiSettings['sslcommerz_store_passwd'] }}" class="input w-full text-sm">
                    </div>
                </div>
            </div>

        </div>

        {{-- TAB 3: Mail / SMTP API --}}
        <div x-show="activeTab === 'email'" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2">SMTP Mail Gateway</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ $apiSettings['mail_host'] }}" class="input w-full" placeholder="smtp.mailtrap.io বা mail.yourdomain.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMTP Port</label>
                    <input type="text" name="mail_port" value="{{ $apiSettings['mail_port'] }}" class="input w-full" placeholder="587 বা 465">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMTP Username</label>
                    <input type="text" name="mail_username" value="{{ $apiSettings['mail_username'] }}" class="input w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">SMTP Password</label>
                    <input type="password" name="mail_password" value="{{ $apiSettings['mail_password'] }}" class="input w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Encryption</label>
                    <input type="text" name="mail_encryption" value="{{ $apiSettings['mail_encryption'] }}" class="input w-full" placeholder="tls / ssl">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sender (From) Email</label>
                    <input type="email" name="mail_from_address" value="{{ $apiSettings['mail_from_address'] }}" class="input w-full" placeholder="noreply@localemployments.com">
                </div>
            </div>
        </div>

        {{-- TAB 4: Google & Social Login API --}}
        <div x-show="activeTab === 'social'" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2">Google Login API</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Client ID</label>
                    <input type="text" name="google_client_id" value="{{ $apiSettings['google_client_id'] }}" class="input w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google Client Secret</label>
                    <input type="password" name="google_client_secret" value="{{ $apiSettings['google_client_secret'] }}" class="input w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Google লগইন স্ট্যাটাস</label>
                    <select name="google_login_active" class="input w-full">
                        <option value="1" {{ $apiSettings['google_login_active'] == '1' ? 'selected' : '' }}>চালু (Active)</option>
                        <option value="0" {{ $apiSettings['google_login_active'] == '0' ? 'selected' : '' }}>বন্ধ (Disabled)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn btn-primary bg-primary-600 hover:bg-primary-700 px-8 py-3.5 shadow-lg font-bold text-sm">
                API কনফিগারেশন সেভ করুন
            </button>
        </div>

    </form>

</div>
@endsection
