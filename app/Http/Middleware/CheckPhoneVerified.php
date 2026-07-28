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
            return redirect()->route('otp.show')
                ->with('info', 'আপনার ফোন নম্বর যাচাই করুন।');
        }

        return $next($request);
    }
}
