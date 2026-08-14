<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct(private OtpService $otp) {}

    public function show()
    {
        $districts = \App\Models\District::active()->get();
        return view('auth.register', compact('districts'));
    }

    public function store(Request $request)
    {
        $rawPhone = $request->phone;
        $normalizedPhone = normalize_bd_phone($rawPhone);
        $request->merge(['phone' => $normalizedPhone]);

        $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'phone'       => ['required', 'string', 'regex:/^01[3-9][0-9]{8}$/', 'unique:users,phone'],
            'email'       => ['nullable', 'email', 'max:191', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Password::min(8)],
            'district_id' => ['nullable', 'exists:districts,id'],
            'area_id'     => ['nullable', 'exists:areas,id'],
        ], [
            'phone.regex'  => 'ফোন নম্বরটি সঠিক ফরম্যাটে দিন (01XXXXXXXXX)',
            'phone.unique' => 'এই ফোন নম্বর দিয়ে আগেই একটি একাউন্ট আছে।',
            'email.unique' => 'এই ইমেইল দিয়ে আগেই একটি একাউন্ট আছে।',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'phone'       => $normalizedPhone,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'seeker',
            'district_id' => $request->district_id,
            'area_id'     => $request->area_id,
        ]);

        // Send OTP
        $this->otp->send($user);

        // Store user id in session for OTP verification
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.show')
            ->with('success', 'নিবন্ধন সফল! আপনার ফোনে OTP পাঠানো হয়েছে।');
    }
}
