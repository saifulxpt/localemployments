<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\District;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(District $location)
    {
        $areas = $location->areas()->orderBy('name')->paginate(30);
        return view('admin.locations.areas.index', ['district' => $location, 'areas' => $areas]);
    }

    public function create(District $location)
    {
        return view('admin.locations.areas.create', ['district' => $location]);
    }

    public function store(Request $request, District $location)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'bn_name' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $location->areas()->create($data);
        return redirect()->route('admin.locations.areas.index', $location)->with('success', 'এলাকা যুক্ত করা হয়েছে।');
    }

    public function edit(Area $area)
    {
        $district = $area->district;
        return view('admin.locations.areas.edit', ['district' => $district, 'area' => $area]);
    }

    public function update(Request $request, Area $area)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'bn_name' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $area->update($data);
        return redirect()->route('admin.locations.areas.index', $area->district_id)->with('success', 'এলাকা আপডেট করা হয়েছে।');
    }

    public function destroy(Area $area)
    {
        $district_id = $area->district_id;
        $area->delete();
        return redirect()->route('admin.locations.areas.index', $district_id)->with('success', 'এলাকা মুছে ফেলা হয়েছে।');
    }
}
