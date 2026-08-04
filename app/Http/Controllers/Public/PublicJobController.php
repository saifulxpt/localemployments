<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\District;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class PublicJobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobRequest::with(['subcategory.category', 'district', 'area', 'seeker'])
            ->where('status', 'open')
            ->where('expires_at', '>', now());

        if ($request->filled('category')) {
            $query->whereHas('subcategory', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();
        
        $categories = ServiceCategory::active()->get();
        $districts = District::active()->get();

        return view('public.jobs.index', compact('jobs', 'categories', 'districts'));
    }
}
