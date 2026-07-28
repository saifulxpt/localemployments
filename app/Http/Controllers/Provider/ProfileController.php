<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\ServiceCategory;
use App\Models\ProviderProfile;
use App\Models\ProviderSkill;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function setup()
    {
        $user = Auth::user();
        if ($user->providerProfile) {
            return redirect()->route('provider.dashboard');
        }

        $districts  = District::active()->get();
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        return view('provider.profile.setup', compact('districts', 'categories'));
    }

    public function edit()
    {
        $user       = Auth::user()->load('providerProfile', 'district', 'area', 'providerSkills.subcategory.category');
        $districts  = District::active()->get();
        $areas      = $user->district_id ? \App\Models\Area::where('district_id', $user->district_id)->active()->get() : collect();
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        return view('provider.profile.edit', compact('user', 'districts', 'areas', 'categories'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $userdata = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['nullable', 'email', 'max:191', 'unique:users,email,' . $user->id],
            'district_id' => ['nullable', 'exists:districts,id'],
            'area_id'     => ['nullable', 'exists:areas,id'],
            'address'     => ['nullable', 'string', 'max:500'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $profiledata = $request->validate([
            'bio'              => ['nullable', 'string', 'max:1000'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'hourly_rate_min'  => ['nullable', 'numeric', 'min:0'],
            'hourly_rate_max'  => ['nullable', 'numeric', 'min:0'],
            'portfolio_photos' => ['nullable', 'array', 'max:8'],
            'portfolio_photos.*'=> ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $userdata['avatar'] = $this->image->storeAvatar($request->file('avatar'), $user->avatar);
        }

        $user->update($userdata);

        $newPhotos = [];
        if ($request->hasFile('portfolio_photos')) {
            $newPhotos = $this->image->storeMultiple($request->file('portfolio_photos'), 'portfolio');
        }

        $profile = $user->providerProfile ?? new ProviderProfile(['user_id' => $user->id]);
        $existing = $profile->portfolio_photos ?? [];
        $profiledata['portfolio_photos'] = array_merge($existing, $newPhotos);
        $profile->fill($profiledata)->save();

        return back()->with('success', 'প্রোফাইল আপডেট হয়েছে।');
    }

    public function skills()
    {
        $user       = Auth::user()->load('providerSkills.subcategory.category');
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        return view('provider.profile.skills', compact('user', 'categories'));
    }

    public function updateSkills(Request $request)
    {
        $request->validate([
            'subcategory_ids'   => ['required', 'array', 'min:1', 'max:10'],
            'subcategory_ids.*' => ['exists:service_subcategories,id'],
        ]);

        $user = Auth::user();
        // Replace all skills
        ProviderSkill::where('provider_id', $user->id)->delete();
        foreach ($request->subcategory_ids as $id) {
            ProviderSkill::create(['provider_id' => $user->id, 'subcategory_id' => $id]);
        }

        return back()->with('success', 'দক্ষতা আপডেট হয়েছে।');
    }
}
