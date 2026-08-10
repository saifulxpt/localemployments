<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Notification;
use App\Models\ProviderProfile;
use App\Models\ProviderSkill;
use App\Models\ProviderVerificationDoc;
use App\Models\ServiceCategory;
use App\Services\ImageService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BecomeProviderController extends Controller
{
    public function __construct(
        private ImageService $image,
        private SmsService $sms,
        private \App\Services\NotificationService $notify
    ) {}

    public function show()
    {
        $user = Auth::user()->load('providerProfile', 'verificationDoc');

        if ($user->providerProfile?->verification_status === 'approved') {
            return redirect()->route('provider.dashboard')
                ->with('info', 'আপনি ইতোমধ্যে একজন অনুমোদিত প্রোভাইডার!');
        }

        if ($user->providerProfile?->verification_status === 'pending') {
            return view('seeker.become-provider-status', compact('user'));
        }

        $districts  = District::active()->get();
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();

        return view('seeker.become-provider', compact('user', 'districts', 'categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->providerProfile?->verification_status === 'approved') {
            return redirect()->route('provider.dashboard');
        }

        $request->validate([
            'subcategories'          => ['required', 'array', 'min:1'],
            'subcategories.*'        => ['exists:service_subcategories,id'],
            'bio'                    => ['required', 'string', 'max:1000'],
            'experience_years'       => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate_min'        => ['required', 'numeric', 'min:0'],
            'hourly_rate_max'        => ['nullable', 'numeric', 'gte:hourly_rate_min'],
            'nid_number'             => ['required', 'string', 'max:50'],
            'dob'                    => ['required', 'date'],
            'full_name'              => ['required', 'string', 'max:255'],
            'father_name'            => ['nullable', 'string', 'max:255'],
            'mother_name'            => ['nullable', 'string', 'max:255'],
            'current_address'        => ['required', 'string', 'max:500'],
            'permanent_address'      => ['required', 'string', 'max:500'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relation' => ['required', 'string', 'max:100'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'nid_front'              => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'nid_back'               => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'selfie_with_nid'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        // Create or update Provider Profile
        $profile = ProviderProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio'                 => $request->bio,
                'experience_years'    => $request->experience_years,
                'hourly_rate_min'     => $request->hourly_rate_min,
                'hourly_rate_max'     => $request->hourly_rate_max,
                'verification_status' => 'pending',
                'is_verified'         => false,
            ]
        );

        // Sync Provider Skills
        ProviderSkill::where('provider_id', $user->id)->delete();
        foreach ($request->subcategories as $subId) {
            ProviderSkill::create([
                'provider_id'    => $user->id,
                'subcategory_id' => $subId,
            ]);
        }

        // Upload verification documents
        $nidFrontPath = $this->image->storeDocument($request->file('nid_front'), 'verification');
        $nidBackPath  = $this->image->storeDocument($request->file('nid_back'), 'verification');
        $selfiePath   = $this->image->storeDocument($request->file('selfie_with_nid'), 'verification');

        ProviderVerificationDoc::updateOrCreate(
            ['provider_id' => $user->id],
            [
                'nid_number'                 => $request->nid_number,
                'dob'                        => $request->dob,
                'full_name'                  => $request->full_name,
                'father_name'                => $request->father_name,
                'mother_name'                => $request->mother_name,
                'current_address'            => $request->current_address,
                'permanent_address'          => $request->permanent_address,
                'emergency_contact_name'     => $request->emergency_contact_name,
                'emergency_contact_relation' => $request->emergency_contact_relation,
                'emergency_contact_phone'    => $request->emergency_contact_phone,
                'nid_front'                  => $nidFrontPath,
                'nid_back'                   => $nidBackPath,
                'selfie_with_nid'            => $selfiePath,
            ]
        );

        // Notify User
        $this->notify->send(
            $user,
            'প্রোভাইডার আবেদন জমা হয়েছে',
            'আপনার সার্ভিস প্রোভাইডার আবেদনটি সফলভাবে জমা হয়েছে। অ্যাডমিন প্যানেল থেকে রিভিউ সম্পন্ন হলে আপনাকে জানানো হবে।',
            'verification'
        );

        return redirect()->route('seeker.become-provider.status')
            ->with('success', 'আপনার প্রোভাইডার আবেদন সফলভাবে জমা হয়েছে। অতি শীঘ্রই আপনার তথ্যসমূহ যাচাই করে অ্যাডমিন অনুমোদন দেবে।');
    }

    public function status()
    {
        $user = Auth::user()->load('providerProfile', 'verificationDoc');
        return view('seeker.become-provider-status', compact('user'));
    }
}
