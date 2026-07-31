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
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                if ($request->hasFile($key) && $request->file($key)->isValid()) {
                    // Upload file and store path
                    $path = $request->file($key)->store('settings', 'public');
                    $setting->update(['value' => '/storage/' . $path]);
                } elseif ($setting->type !== 'file') {
                    // Update normal values, ignore empty file fields
                    $setting->update(['value' => $value]);
                }
            }
        }

        AdminActivityLog::record('Updated platform settings');

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }
}
