<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\JobRequest;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::active()->withCount(['activeSubcategories'])->get();
        return view('public.services', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = ServiceCategory::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $subcategories = $category->activeSubcategories()->get();

        // Open job requests in this category
        $jobs = JobRequest::whereHas('subcategory', fn($q) => $q->where('category_id', $category->id))
            ->where('status', 'open')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->with(['subcategory', 'district', 'area', 'seeker'])
            ->latest()
            ->paginate(12);

        return view('public.category-show', compact('category', 'subcategories', 'jobs'));
    }
}

