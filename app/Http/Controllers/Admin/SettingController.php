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
            $setting = Setting::firstOrCreate(
                ['key' => $key],
                ['group' => 'general', 'type' => 'string', 'display_name' => ucwords(str_replace('_', ' ', $key))]
            );
            
            if ($request->hasFile($key) && $request->file($key)->isValid()) {
                // Upload file directly to public/uploads/settings for cPanel & shared hosting compatibility
                $file = $request->file($key);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $setting->update(['value' => 'uploads/settings/' . $filename]);
            } elseif ($setting->type !== 'file') {
                // Update normal values, ignore empty file fields
                $setting->update(['value' => $value]);
            }
        }

        AdminActivityLog::record('Updated platform settings');

        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }
}
