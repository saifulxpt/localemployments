<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\JsonResponse;

class LocationAjaxController extends Controller
{
    public function byDistrict(int $district): JsonResponse
    {
        $areas = Area::where('district_id', $district)
            ->active()
            ->get(['id', 'name', 'bn_name']);

        return response()->json($areas);
    }
}
