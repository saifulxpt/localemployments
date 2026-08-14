<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google লগইন বাতিল করা হয়েছে বা কোনো সমস্যা হয়েছে।');
        }

        // Check if user already exists with this google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Check if a user with this email already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Link google_id to existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            }
        }

        if ($user) {
            // If user has no phone number, prompt to add phone
            if (empty($user->phone)) {
                session([
                    'google_signup_data' => [
                        'user_id'   => $user->id,
                        'google_id' => $googleUser->getId(),
                        'name'      => $user->name ?? $googleUser->getName(),
                        'email'     => $user->email ?? $googleUser->getEmail(),
                        'avatar'    => $user->avatar ?? $googleUser->getAvatar(),
                    ]
                ]);
                return redirect()->route('auth.google.phone');
            }

            // If user's phone is not verified yet, ensure OTP is active and redirect to verify-otp
            if (!$user->phone_verified) {
                Auth::login($user, true);
                session(['otp_user_id' => $user->id]);

                $otpService = app(\App\Services\OtpService::class);
                if (!$user->otp || ($user->otp_expires_at && now()->isAfter($user->otp_expires_at))) {
                    $otpService->send($user);
                }

                return redirect()->route('otp.show')
                    ->with('info', 'আপনার ফোন নম্বরটি যাচাই করতে কোডটি দিন।');
            }

            // User is verified, log them in
            Auth::login($user, true);
            $user->update(['last_login_at' => now()]);
            
            // Redirect based on role
            return match ($user->role) {
                'admin'    => redirect()->route('admin.dashboard'),
                'provider' => redirect()->route('provider.dashboard'),
                default    => redirect()->route('seeker.dashboard'),
            };
        }

        // User does not exist, redirect to "Complete Registration" page
        session([
            'google_signup_data' => [
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'avatar'    => $googleUser->getAvatar(),
            ]
        ]);

        return redirect()->route('auth.google.phone');
    }

    /**
     * Show the "Add Phone" form for Google signup.
     */
    public function showPhoneForm()
    {
        $user = auth()->user();
        if (!$user && !session()->has('google_signup_data')) {
            return redirect()->route('register');
        }

        $districts = \App\Models\District::active()->get();
        return view('auth.google-phone', compact('districts', 'user'));
    }

    /**
     * Process the phone number and create the user, then send OTP.
     */
    public function storePhone(Request $request, \App\Services\OtpService $otpService)
    {
        $authUser = auth()->user();
        $googleData = session('google_signup_data');

        if (!$authUser && !$googleData) {
            return redirect()->route('register');
        }

        $rawPhone = $request->phone;
        $normalizedPhone = normalize_bd_phone($rawPhone);
        $request->merge(['phone' => $normalizedPhone]);

        $userId = $authUser ? $authUser->id : ($googleData['user_id'] ?? null);

        $request->validate([
            'phone'       => ['required', 'string', 'regex:/^01[3-9][0-9]{8}$/', 'unique:users,phone' . ($userId ? ",{$userId}" : '')],
            'district_id' => ['nullable', 'exists:districts,id'],
            'area_id'     => ['nullable', 'exists:areas,id'],
        ], [
            'phone.regex'  => 'ফোন নম্বরটি সঠিক ফরম্যাটে দিন (01XXXXXXXXX)',
            'phone.unique' => 'এই ফোন নম্বর দিয়ে আগেই একটি একাউন্ট আছে।',
        ]);

        if ($authUser) {
            $authUser->update([
                'phone'          => $normalizedPhone,
                'district_id'    => $request->district_id ?? $authUser->district_id,
                'area_id'        => $request->area_id ?? $authUser->area_id,
                'phone_verified' => false,
            ]);
            $user = $authUser;
        } elseif (!empty($googleData['user_id'])) {
            $user = User::findOrFail($googleData['user_id']);
            $user->update([
                'phone'          => $normalizedPhone,
                'district_id'    => $request->district_id ?? $user->district_id,
                'area_id'        => $request->area_id ?? $user->area_id,
                'phone_verified' => false,
            ]);
            Auth::login($user, true);
        } else {
            $user = User::create([
                'name'           => $googleData['name'],
                'email'          => $googleData['email'],
                'phone'          => $normalizedPhone,
                'password'       => null,
                'role'           => 'seeker',
                'google_id'      => $googleData['google_id'],
                'district_id'    => $request->district_id,
                'area_id'        => $request->area_id,
                'status'         => 'active',
                'phone_verified' => false,
            ]);
            Auth::login($user, true);
        }

        // Send OTP
        $otpService->send($user);

        // Store user id in session for OTP verification
        session(['otp_user_id' => $user->id]);
        
        // Remove google data from session
        session()->forget('google_signup_data');

        return redirect()->route('otp.show')
            ->with('success', 'আপনার ফোনে (' . $normalizedPhone . ') OTP পাঠানো হয়েছে।');
    }
}
