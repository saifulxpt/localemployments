<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\FeaturedSubscription;
use Illuminate\Http\Request;

class FeaturedController extends Controller
{
    public function index()
    {
        $featured = FeaturedSubscription::with('provider')->latest()->paginate(20);
        $providers = User::where('role', 'provider')
            ->whereHas('providerProfile', fn($q) => $q->where('is_verified', true))
            ->with('providerProfile')
            ->get();
        return view('admin.featured.index', compact('featured', 'providers'));
    }

    public function grant(Request $request, User $provider)
    {
        $data = $request->validate([
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $days = (int) $data['duration_days'];

        FeaturedSubscription::create([
            'provider_id'   => $provider->id,
            'amount'        => 0,
            'duration_days' => $days,
            'starts_at'     => now(),
            'ends_at'       => now()->addDays($days),
            'status'        => 'active',
        ]);

        $provider->providerProfile()->update([
            'is_featured'    => true,
            'featured_until' => now()->addDays($days),
        ]);

        return back()->with('success', "প্রোভাইডারকে {$days} দিনের ফিচার্ড মর্যাদা দেওয়া হয়েছে।");
    }
}
