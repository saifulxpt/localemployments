<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function edit()
    {
        $user      = Auth::user()->load('district', 'area');
        $districts = District::active()->get();
        $areas     = $user->district_id ? \App\Models\Area::where('district_id', $user->district_id)->active()->get() : collect();
        return view('seeker.profile.edit', compact('user', 'districts', 'areas'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:150'],
            'email'       => ['nullable', 'email', 'max:191', 'unique:users,email,' . $user->id],
            'district_id' => ['nullable', 'exists:districts,id'],
            'area_id'     => ['nullable', 'exists:areas,id'],
            'address'     => ['nullable', 'string', 'max:500'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->image->storeAvatar($request->file('avatar'), $user->avatar);
        }

        $user->update($data);

        return back()->with('success', 'প্রোফাইল আপডেট হয়েছে।');
    }
}
