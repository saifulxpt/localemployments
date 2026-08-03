<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function __construct(private OtpService $otp) {}

    public function show()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => ['required', 'string', 'size:6']]);

        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'সেশন মেয়াদ শেষ। আবার লগইন করুন।']);
        }

        if (!$this->otp->verify($user, $request->otp)) {
            return back()->withErrors(['otp' => 'OTP সঠিক নয় বা মেয়াদ শেষ হয়েছে।']);
        }

        // Log user in
        Auth::login($user, true);
        session()->forget('otp_user_id');

        $user->update(['last_login_at' => now()]);

        // Redirect based on role
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.profile.setup')->with('success', 'স্বাগতম! প্রথমে আপনার প্রোফাইল সেট করুন।'),
            default    => redirect()->route('seeker.dashboard')->with('success', 'ফোন নম্বর যাচাই হয়েছে। স্বাগতম!'),
        };
    }

    public function resend(Request $request)
    {
        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$this->otp->canResend($user)) {
            return back()->withErrors(['otp' => 'অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।']);
        }

        $this->otp->send($user);

        return back()->with('success', 'নতুন OTP পাঠানো হয়েছে।');
    }
}
