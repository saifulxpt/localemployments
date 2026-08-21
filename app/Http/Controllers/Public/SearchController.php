<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Redirect public worker search directly to public jobs board.
     */
    public function index(Request $request)
    {
        return redirect()->route('jobs.index', $request->query());
    }
}

