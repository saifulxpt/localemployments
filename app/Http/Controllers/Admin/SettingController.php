<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $inputs = $request->except(['_token', '_method']);

        foreach ($inputs as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        AdminActivityLog::record('Updated platform settings');

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }
}
