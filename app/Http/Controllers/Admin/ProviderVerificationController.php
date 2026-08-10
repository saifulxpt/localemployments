<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminActivityLog;
use App\Services\NotificationService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class ProviderVerificationController extends Controller
{
    public function __construct(private NotificationService $notify, private SmsService $sms) {}

    public function index()
    {
        $pending = User::whereHas('providerProfile', fn($q) => $q->where('verification_status', 'pending'))
            ->with('providerProfile', 'verificationDoc', 'district')
            ->latest()
            ->paginate(15);

        return view('admin.verifications.index', compact('pending'));
    }

    public function show(User $user)
    {
        $user->load('providerProfile', 'verificationDoc', 'district', 'area');
        return view('admin.verifications.show', compact('user'));
    }

    public function approve(Request $request, User $user)
    {
        $user->providerProfile->update([
            'is_verified'          => true,
            'verified_at'          => now(),
            'verification_status'  => 'approved',
        ]);

        $user->update(['role' => 'provider']);

        $this->notify->send($user, 'যাচাইকরণ অনুমোদিত', 'আপনার সার্ভিস প্রোভাইডার আবেদন অনুমোদিত হয়েছে! এখন আপনি সরাসরি বিড ও কাজ করতে পারবেন।', 'system');
        $this->sms->send($user->phone, "LocalEmployments: আপনার সার্ভিস প্রোভাইডার আবেদন অনুমোদিত হয়েছে। অ্যাপে লগইন করুন।", 'verification');
        AdminActivityLog::record("Approved provider application for user #{$user->id}", $user);

        return back()->with('success', 'প্রোভাইডার যাচাইকরণ ও রোল অনুমোদন সফল হয়েছে।');
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['admin_note' => ['required', 'string', 'max:500']]);

        $user->providerProfile->update(['verification_status' => 'rejected']);
        $user->verificationDoc?->update(['admin_note' => $request->admin_note]);

        $this->notify->send($user, 'যাচাইকরণ প্রত্যাখ্যাত', "আপনার যাচাইকরণ গ্রহণ হয়নি। কারণ: {$request->admin_note}", 'system');
        AdminActivityLog::record("Rejected verification for provider #{$user->id}", $user);

        return back()->with('success', 'যাচাইকরণ প্রত্যাখ্যাত করা হয়েছে।');
    }
}
