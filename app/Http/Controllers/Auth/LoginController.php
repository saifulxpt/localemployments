<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(private OtpService $otp) {}

    public function show()
    {
        return view('auth.login');
    }

    public function attempt(Request $request)
    {
        $request->validate([
            'phone'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $phone = normalize_bd_phone($request->phone);
        $user = User::where('phone', $phone)->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['phone' => 'ফোন নম্বর বা পাসওয়ার্ড সঠিক নয়।'])->withInput();
        }

        if ($user->status === 'banned') {
            return back()->withErrors(['phone' => 'আপনার একাউন্ট বাতিল করা হয়েছে।']);
        }

        if ($user->status === 'suspended') {
            return back()->withErrors(['phone' => 'আপনার একাউন্ট সাময়িকভাবে স্থগিত।']);
        }

        // Check OTP verification
        if (!$user->phone_verified) {
            $this->otp->send($user);
            session(['otp_user_id' => $user->id]);
            return redirect()->route('otp.show')->with('info', 'আপনার ফোন নম্বর যাচাই করুন।');
        }

        Auth::login($user, true);

        $user->update(['last_login_at' => now()]);

        return $this->redirectAfterLogin($user);
    }

    private function redirectAfterLogin(User $user): \Illuminate\Http\RedirectResponse
    {
        return match ($user->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.dashboard'),
            default    => redirect()->route('seeker.dashboard'),
        };
    }
}
