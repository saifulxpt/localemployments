<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsVerifiedProvider
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isProvider()) {
            abort(403);
        }

        $profile = $user->providerProfile;

        if (!$profile || !$profile->is_verified) {
            return redirect()->route('provider.verification.show')
                ->with('warning', 'এই কাজটি করতে আপনার প্রোফাইল যাচাই করতে হবে।');
        }

        return $next($request);
    }
}
