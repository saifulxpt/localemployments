<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProviderPublicController extends Controller
{
    public function show(User $user)
    {
        abort_if($user->role !== 'provider', 404);

        $user->load([
            'providerProfile',
            'district',
            'area',
            'providerSkills.subcategory.category',
            'directServices' => fn($q) => $q->active()->latest(),
            'reviewsReceived' => fn($q) => $q->visible()->with('reviewer')->latest()->take(10),
        ]);

        return view('public.provider-profile', compact('user'));
    }
}
