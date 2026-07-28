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
        $pending = User::where('role', 'provider')
            ->whereHas('providerProfile', fn($q) => $q->where('verification_status', 'pending'))
            ->with('providerProfile', 'verificationDoc', 'district')
            ->latest()
            ->paginate(15);

        return view('admin.verifications.index', compact('pending'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'provider', 404);
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

        $this->notify->send($user, 'যাচাইকরণ অনুমোদিত', 'আপনার প্রোফাইল যাচাই হয়েছে! এখন সেবা দিন।', 'system');
        $this->sms->send($user->phone, "LocalEmployments: আপনার প্রোফাইল যাচাই অনুমোদিত হয়েছে। এখন বিড করুন।", 'verification');
        AdminActivityLog::record("Approved verification for provider #{$user->id}", $user);

        return back()->with('success', 'প্রোভাইডার যাচাইকরণ অনুমোদন হয়েছে।');
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
