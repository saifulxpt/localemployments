<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'provider')
            ->with('providerProfile', 'district', 'area', 'providerSkills.subcategory');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('providerSkills.subcategory', fn($q) =>
                $q->where('category_id', $request->category)
            );
        }

        // Filter by subcategory
        if ($request->filled('subcategory')) {
            $query->whereHas('providerSkills', fn($q) =>
                $q->where('subcategory_id', $request->subcategory)
            );
        }

        // Filter by district
        if ($request->filled('district')) {
            $query->where('district_id', $request->district);
        }

        // Filter by area
        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        // Filter verified only
        if ($request->boolean('verified')) {
            $query->whereHas('providerProfile', fn($q) => $q->where('is_verified', true));
        }

        // Filter by min rating
        if ($request->filled('min_rating')) {
            $query->whereHas('providerProfile', fn($q) =>
                $q->where('rating_avg', '>=', $request->min_rating)
            );
        }

        // Sort
        match ($request->get('sort', 'rating')) {
            'rating'  => $query->whereHas('providerProfile')->orderByDesc(
                \App\Models\ProviderProfile::select('rating_avg')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1)
            ),
            'jobs'    => $query->whereHas('providerProfile')->orderByDesc(
                \App\Models\ProviderProfile::select('total_jobs')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1)
            ),
            'newest'  => $query->latest(),
            default   => $query->inRandomOrder(),
        };

        $providers   = $query->paginate(12)->withQueryString();
        $categories  = ServiceCategory::active()->get();
        $districts   = District::active()->get();
        $areas       = $request->filled('district')
            ? \App\Models\Area::where('district_id', $request->district)->active()->get()
            : collect();

        return view('public.search', compact('providers', 'categories', 'districts', 'areas'));
    }
}
