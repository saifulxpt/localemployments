<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\DirectService;
use App\Models\ServiceCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectServiceController extends Controller
{
    public function __construct(private ImageService $image) {}

    public function index()
    {
        $services = DirectService::where('provider_id', Auth::id())
            ->with('subcategory.category')
            ->latest()->paginate(10);
        return view('provider.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        $districts  = \App\Models\District::active()->get();
        return view('provider.services.create', compact('categories', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subcategory_id'    => ['required', 'exists:service_subcategories,id'],
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string', 'min:20'],
            'price'             => ['required', 'numeric', 'min:50'],
            'price_type'        => ['required', 'in:fixed,hourly,starting_from'],
            'estimated_duration'=> ['nullable', 'string', 'max:100'],
            'service_areas'     => ['required', 'array', 'min:1'],
            'service_areas.*'   => ['exists:districts,id'],
            'photos'            => ['nullable', 'array', 'max:5'],
            'photos.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['provider_id'] = Auth::id();
        $data['photos']      = $request->hasFile('photos')
            ? $this->image->storeMultiple($request->file('photos'), 'service-photos')
            : [];

        DirectService::create($data);

        return redirect()->route('provider.services.index')->with('success', 'সেবা সফলভাবে যোগ করা হয়েছে!');
    }

    public function show(DirectService $service)
    {
        abort_if($service->provider_id !== Auth::id(), 403);
        $service->load('subcategory.category', 'bookings');
        return view('provider.services.show', compact('service'));
    }

    public function edit(DirectService $service)
    {
        abort_if($service->provider_id !== Auth::id(), 403);
        $categories = ServiceCategory::active()->with('activeSubcategories')->get();
        $districts  = \App\Models\District::active()->get();
        return view('provider.services.edit', compact('service', 'categories', 'districts'));
    }

    public function update(Request $request, DirectService $service)
    {
        abort_if($service->provider_id !== Auth::id(), 403);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string', 'min:20'],
            'price'             => ['required', 'numeric', 'min:50'],
            'price_type'        => ['required', 'in:fixed,hourly,starting_from'],
            'estimated_duration'=> ['nullable', 'string', 'max:100'],
            'service_areas'     => ['required', 'array', 'min:1'],
            'service_areas.*'   => ['exists:districts,id'],
            'is_active'         => ['boolean'],
        ]);

        $service->update($data);

        return redirect()->route('provider.services.index')->with('success', 'সেবা আপডেট হয়েছে।');
    }

    public function destroy(DirectService $service)
    {
        abort_if($service->provider_id !== Auth::id(), 403);
        $service->delete();
        return redirect()->route('provider.services.index')->with('success', 'সেবা মুছে ফেলা হয়েছে।');
    }
}
