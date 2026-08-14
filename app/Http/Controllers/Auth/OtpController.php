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

    /**
     * Resolve target user from Auth or Session.
     */
    private function resolveUser(Request $request): ?User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if ($userId = $request->session()->get('otp_user_id')) {
            return User::find($userId);
        }

        return null;
    }

    public function show(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login')->with('info', 'অনুগ্রহ করে লগইন বা রেজিস্ট্রেশন করুন।');
        }

        // If phone already verified, redirect to dashboard
        if ($user->phone_verified) {
            return $this->redirectByRole($user);
        }

        // If no phone number, redirect to phone form
        if (empty($user->phone)) {
            return redirect()->route('auth.google.phone');
        }

        return view('auth.verify-otp', compact('user'));
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => ['required', 'string', 'size:6']], [
            'otp.required' => '৬-সংখ্যার OTP কোডটি লিখুন।',
            'otp.size'     => 'OTP কোডটি অবশ্যই ৬-সংখ্যার হতে হবে।',
        ]);

        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'সেশন মেয়াদ শেষ। আবার লগইন করুন।']);
        }

        if (!$this->otp->verify($user, $request->otp)) {
            return back()->withErrors(['otp' => 'OTP সঠিক নয় বা মেয়াদ শেষ হয়েছে।']);
        }

        // Log user in
        if (!Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user, true);
        }

        session()->forget('otp_user_id');
        $user->update(['last_login_at' => now()]);

        return $this->redirectByRole($user)->with('success', 'ফোন নম্বর যাচাই সফল হয়েছে। স্বাগতম!');
    }

    public function resend(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login')->withErrors(['otp' => 'ব্যবহারকারী পাওয়া যায়নি।']);
        }

        if (!$this->otp->canResend($user)) {
            return back()->withErrors(['otp' => 'অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।']);
        }

        $this->otp->send($user);

        return back()->with('success', 'নতুন OTP কোড পাঠানো হয়েছে।');
    }

    /**
     * Show form to change phone number.
     */
    public function showChangePhone(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->phone_verified) {
            return $this->redirectByRole($user);
        }

        return view('auth.change-phone', compact('user'));
    }

    /**
     * Update phone number and send fresh OTP.
     */
    public function updatePhone(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return redirect()->route('login');
        }

        $rawPhone = $request->phone;
        $normalizedPhone = normalize_bd_phone($rawPhone);
        $request->merge(['phone' => $normalizedPhone]);

        $request->validate([
            'phone' => ['required', 'string', 'regex:/^01[3-9][0-9]{8}$/', 'unique:users,phone,' . $user->id],
        ], [
            'phone.regex'  => 'ফোন নম্বরটি সঠিক ফরম্যাটে দিন (01XXXXXXXXX)',
            'phone.unique' => 'এই ফোন নম্বর দিয়ে আগেই একটি একাউন্ট আছে।',
        ]);

        $user->update([
            'phone'          => $normalizedPhone,
            'phone_verified' => false,
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        $this->otp->send($user);

        return redirect()->route('otp.show')
            ->with('success', 'ফোন নম্বর সফলভাবে পরিবর্তন হয়েছে। নতুন OTP পাঠানো হয়েছে।');
    }

    /**
     * Cancel verification and logout.
     */
    public function cancel(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        $request->session()->forget('otp_user_id');
        $request->session()->forget('google_signup_data');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'যাচাই প্রক্রিয়া বাতিল করা হয়েছে।');
    }

    private function redirectByRole(User $user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.profile.setup')->with('success', 'স্বাগতম! প্রথমে আপনার প্রোফাইল সেট করুন।'),
            default    => redirect()->route('seeker.dashboard')->with('success', 'ফোন নম্বর যাচাই হয়েছে। স্বাগতম!'),
        };
    }
}
