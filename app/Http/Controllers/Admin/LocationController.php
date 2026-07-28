<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;

class LocationController extends Controller
{
    public function index()
    {
        $districts = District::withCount('areas')->orderBy('name')->paginate(30);
        return view('admin.locations.index', compact('districts'));
    }
}
