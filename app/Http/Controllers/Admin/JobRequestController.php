<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;

class JobRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = JobRequest::with('seeker', 'subcategory.category', 'district')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $jobRequests = $query->paginate(20)->withQueryString();
        return view('admin.job-requests.index', compact('jobRequests'));
    }

    public function show(JobRequest $jobRequest)
    {
        $jobRequest->load('seeker', 'subcategory.category', 'district', 'area', 'bids.provider.providerProfile', 'booking');
        return view('admin.job-requests.show', compact('jobRequest'));
    }
}
