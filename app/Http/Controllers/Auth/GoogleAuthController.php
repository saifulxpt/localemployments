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
            // User exists, log them in
            Auth::login($user);
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'provider') {
                return redirect()->route('provider.dashboard');
            }
            return redirect()->route('seeker.dashboard');
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
        if (!session()->has('google_signup_data')) {
            return redirect()->route('register');
        }

        $districts = \App\Models\District::active()->get();
        return view('auth.google-phone', compact('districts'));
    }

    /**
     * Process the phone number and create the user, then send OTP.
     */
    public function storePhone(Request $request, \App\Services\OtpService $otpService)
    {
        if (!session()->has('google_signup_data')) {
            return redirect()->route('register');
        }

        $request->validate([
            'phone'       => ['required', 'string', 'regex:/^01[3-9][0-9]{8}$/', 'unique:users,phone'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'area_id'     => ['nullable', 'exists:areas,id'],
        ], [
            'phone.regex'  => 'ফোন নম্বরটি সঠিক ফরম্যাটে দিন (01XXXXXXXXX)',
            'phone.unique' => 'এই ফোন নম্বর দিয়ে আগেই একটি একাউন্ট আছে।',
        ]);

        $googleData = session('google_signup_data');

        $user = User::create([
            'name'        => $googleData['name'],
            'email'       => $googleData['email'],
            'phone'       => $request->phone,
            'password'    => null, // Nullable as per migration
            'role'        => 'seeker',
            'google_id'   => $googleData['google_id'],
            'district_id' => $request->district_id,
            'area_id'     => $request->area_id,
            'status'      => 'active',
        ]);

        // Send OTP
        $otpService->send($user);

        // Store user id in session for OTP verification
        session(['otp_user_id' => $user->id]);
        
        // Remove google data from session
        session()->forget('google_signup_data');

        return redirect()->route('otp.show')
            ->with('success', 'অ্যাকাউন্ট তৈরি হয়েছে! আপনার ফোনে OTP পাঠানো হয়েছে।');
    }
}
