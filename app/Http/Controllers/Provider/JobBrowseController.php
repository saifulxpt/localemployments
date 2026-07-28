<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Support\Facades\Auth;

class JobBrowseController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('providerSkills', 'district');

        $jobs = JobRequest::open()
            ->forProvider($user)
            ->with('subcategory.category', 'district', 'area', 'seeker')
            ->withCount('bids')
            ->latest()
            ->paginate(12);

        return view('provider.jobs.index', compact('jobs', 'user'));
    }

    public function show(JobRequest $jobRequest)
    {
        abort_if($jobRequest->status !== 'open', 404);

        $jobRequest->load('subcategory.category', 'district', 'area', 'seeker');

        $myBid = $jobRequest->bids()->where('provider_id', Auth::id())->first();

        return view('provider.jobs.show', compact('jobRequest', 'myBid'));
    }
}
