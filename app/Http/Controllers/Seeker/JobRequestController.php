<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\ServiceCategory;
use App\Models\District;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobRequestController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function index()
    {
        $requests = JobRequest::where('seeker_id', Auth::id())
            ->with('subcategory.category', 'district', 'area')
            ->withCount('bids')
            ->latest()
            ->paginate(10);

        return view('seeker.job-requests.index', compact('requests'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        $districts  = District::active()->get();
        return view('seeker.job-requests.create', compact('categories', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subcategory_id'  => ['required', 'exists:service_subcategories,id'],
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string', 'min:20'],
            'district_id'     => ['required', 'exists:districts,id'],
            'area_id'         => ['required', 'exists:areas,id'],
            'address_detail'  => ['nullable', 'string', 'max:255'],
            'budget_min'      => ['nullable', 'numeric', 'min:0'],
            'budget_max'      => ['nullable', 'numeric', 'min:0'],
            'preferred_date'  => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time'  => ['nullable', 'string'],
            'flexibility'     => ['required', 'in:fixed,flexible,urgent'],
            'photos'          => ['nullable', 'array', 'max:5'],
            'photos.*'        => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            $photoPaths = $this->image->storeMultiple($request->file('photos'), 'job-photos');
        }

        $jobRequest = JobRequest::create(array_merge($data, [
            'seeker_id'  => Auth::id(),
            'photos'     => $photoPaths,
            'expires_at' => now()->addDays((int) setting('job_request_expiry_days', 7)),
        ]));

        return redirect()->route('seeker.job-requests.show', $jobRequest)
            ->with('success', 'কাজের অনুরোধ সফলভাবে পোস্ট করা হয়েছে!');
    }

    public function show(JobRequest $jobRequest)
    {
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);

        $jobRequest->load('subcategory.category', 'district', 'area',
            'bids.provider.providerProfile', 'bids.provider.district',
            'booking.provider');

        return view('seeker.job-requests.show', compact('jobRequest'));
    }

    public function edit(JobRequest $jobRequest)
    {
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        abort_if(!$jobRequest->isOpen(), 403, 'শুধুমাত্র open অনুরোধ সম্পাদনা করা যায়।');

        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        $districts  = District::active()->get();
        return view('seeker.job-requests.edit', compact('jobRequest', 'categories', 'districts'));
    }

    public function update(Request $request, JobRequest $jobRequest)
    {
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        abort_if(!$jobRequest->isOpen(), 403);

        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string', 'min:20'],
            'budget_min'     => ['nullable', 'numeric', 'min:0'],
            'budget_max'     => ['nullable', 'numeric', 'min:0'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'string'],
            'flexibility'    => ['required', 'in:fixed,flexible,urgent'],
        ]);

        $jobRequest->update($data);

        return redirect()->route('seeker.job-requests.show', $jobRequest)
            ->with('success', 'অনুরোধ আপডেট হয়েছে।');
    }

    public function destroy(JobRequest $jobRequest)
    {
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        $jobRequest->delete();
        return redirect()->route('seeker.job-requests.index')->with('success', 'অনুরোধ মুছে ফেলা হয়েছে।');
    }

    public function cancel(JobRequest $jobRequest)
    {
        abort_if($jobRequest->seeker_id !== Auth::id(), 403);
        abort_if(!$jobRequest->isOpen(), 403);

        $jobRequest->update(['status' => 'cancelled']);
        return back()->with('success', 'অনুরোধ বাতিল করা হয়েছে।');
    }
}
