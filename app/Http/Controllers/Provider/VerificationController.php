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
            'nid_number' => ['required', 'string', 'max:50'],
            'dob' => ['required', 'date'],
            'full_name' => ['required', 'string', 'max:255'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'current_address' => ['required', 'string'],
            'permanent_address' => ['required', 'string'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relation' => ['required', 'string', 'max:100'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'nid_front'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'nid_back'     => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'selfie_with_nid' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'certificates.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $otherDocs = [];
        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $file) {
                // Since PDF might not be handled by ImageService optimally for resizing, we can just store it
                // using Laravel Storage for PDFs, and ImageService for images. 
                // But wait, ImageService->storeDocument() usually saves as webp for images.
                // We'll just use raw Storage for certificates to keep pdfs intact.
                $path = $file->store('provider-certificates', 'public');
                $otherDocs[] = $path;
            }
        }

        $doc = $user->verificationDoc ?? new ProviderVerificationDoc(['provider_id' => $user->id]);
        $doc->fill([
            'nid_number' => $request->nid_number,
            'dob' => $request->dob,
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'current_address' => $request->current_address,
            'permanent_address' => $request->permanent_address,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'nid_front'      => $this->image->storeDocument($request->file('nid_front'), 'nid-docs'),
            'nid_back'       => $this->image->storeDocument($request->file('nid_back'), 'nid-docs'),
            'selfie_with_nid'=> $this->image->storeDocument($request->file('selfie_with_nid'), 'nid-docs'),
            'other_docs'     => count($otherDocs) > 0 ? $otherDocs : null,
        ])->save();

        $user->providerProfile()->update(['verification_status' => 'pending']);

        return back()->with('success', 'যাচাইকরণ নথি জমা হয়েছে। ১-৩ কার্যদিবসের মধ্যে পর্যালোচনা করা হবে।');
    }
}
