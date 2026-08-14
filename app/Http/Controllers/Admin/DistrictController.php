<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::withCount('areas')->orderBy('name')->paginate(30);
        return view('admin.locations.index', compact('districts'));
    }

    public function create()
    {
        return view('admin.locations.districts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100', 'unique:districts,name'],
            'bn_name' => ['required', 'string', 'max:100'],
        ]);
        District::create($data);
        AdminActivityLog::record("Created district: {$data['name']}");
        return redirect()->route('admin.districts.index')->with('success', 'জেলা যোগ হয়েছে।');
    }

    public function edit(District $district)
    {
        return view('admin.locations.districts.edit', compact('district'));
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100', 'unique:districts,name,' . $district->id],
            'bn_name'   => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);
        $district->update($data);
        return redirect()->route('admin.districts.index')->with('success', 'জেলা আপডেট হয়েছে।');
    }

    public function destroy(District $district)
    {
        $district->update(['is_active' => false]);
        return redirect()->route('admin.districts.index')->with('success', 'জেলা নিষ্ক্রিয় করা হয়েছে।');
    }
}
