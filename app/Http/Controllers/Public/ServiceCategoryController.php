<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;

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

        // Providers with skills in this category
        $providers = \App\Models\User::where('role', 'provider')
            ->whereHas('providerSkills.subcategory', fn($q) => $q->where('category_id', $category->id))
            ->with('providerProfile', 'district')
            ->paginate(12);

        return view('public.category-show', compact('category', 'subcategories', 'providers'));
    }
}
