<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSeeker
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSeeker()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Access denied. Seeker account required.');
        }

        if (!auth()->user()->isActive()) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['phone' => 'Your account has been suspended.']);
        }

        return $next($request);
    }
}
