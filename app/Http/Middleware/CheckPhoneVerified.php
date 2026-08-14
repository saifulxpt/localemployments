<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPhoneVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && !$user->phone_verified) {
            $allowedRoutes = [
                'otp.show',
                'otp.verify',
                'otp.resend',
                'otp.change-phone',
                'otp.change-phone.store',
                'otp.cancel',
                'auth.google.phone',
                'auth.google.phone.store',
                'logout',
            ];

            if ($request->routeIs($allowedRoutes)) {
                return $next($request);
            }

            if (empty($user->phone)) {
                return redirect()->route('auth.google.phone')
                    ->with('info', 'অনুগ্রহ করে আপনার ফোন নম্বরটি যুক্ত করুন।');
            }

            return redirect()->route('otp.show')
                ->with('info', 'আপনার ফোন নম্বরটি যাচাই করুন।');
        }

        return $next($request);
    }
}
