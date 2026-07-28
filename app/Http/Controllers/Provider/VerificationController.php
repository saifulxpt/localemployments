<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderVerificationDoc;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function show()
    {
        $user = Auth::user()->load('providerProfile', 'verificationDoc');
        return view('provider.verification.show', compact('user'));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();

        if ($user->providerProfile?->verification_status === 'approved') {
            return back()->with('info', 'আপনি ইতোমধ্যে যাচাইকৃত।');
        }

        $request->validate([
            'nid_front'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'nid_back'     => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'selfie_with_nid' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
        ]);

        $doc = $user->verificationDoc ?? new ProviderVerificationDoc(['provider_id' => $user->id]);
        $doc->fill([
            'nid_front'      => $this->image->storeDocument($request->file('nid_front'), 'nid-docs'),
            'nid_back'       => $this->image->storeDocument($request->file('nid_back'), 'nid-docs'),
            'selfie_with_nid'=> $this->image->storeDocument($request->file('selfie_with_nid'), 'nid-docs'),
        ])->save();

        $user->providerProfile()->update(['verification_status' => 'pending']);

        return back()->with('success', 'যাচাইকরণ নথি জমা হয়েছে। ১-৩ কার্যদিবসের মধ্যে পর্যালোচনা করা হবে।');
    }
}
