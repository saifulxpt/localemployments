<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\SmsLog;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('district')->latest();

        if ($request->filled('role'))   $query->where('role', $request->role);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('q'))      $query->where(fn($q) => $q->where('name', 'like', "%{$request->q}%")->orWhere('phone', 'like', "%{$request->q}%"));

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('providerProfile', 'district', 'area', 'providerSkills.subcategory');
        
        $activities = AdminActivityLog::where('model_type', User::class)->where('model_id', $user->id)->latest()->get();
        $smsLogs = SmsLog::where('phone', $user->phone)->latest()->get();
        
        return view('admin.users.show', compact('user', 'activities', 'smsLogs'));
    }

    public function verifyOtp(User $user)
    {
        $user->update(['phone_verified_at' => now()]);
        AdminActivityLog::record("Manually verified phone number for user #{$user->id} ({$user->phone})", $user);
        return back()->with('success', 'ফোন নম্বর ম্যানুয়ালি ভেরিফাই করা হয়েছে।');
    }

    public function verifyProfile(User $user)
    {
        if ($user->providerProfile) {
            $user->providerProfile->update([
                'is_verified' => true,
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]);
            AdminActivityLog::record("Manually verified provider profile for user #{$user->id}", $user);
            return back()->with('success', 'প্রোভাইডার প্রোফাইল ম্যানুয়ালি ভেরিফাই করা হয়েছে।');
        }
        return back()->with('error', 'এই ইউজারের কোনো প্রোভাইডার প্রোফাইল নেই।');
    }

    public function suspend(User $user)
    {
        $user->update(['status' => 'suspended']);
        AdminActivityLog::record("Suspended user #{$user->id} ({$user->phone})", $user, ['status' => 'active'], ['status' => 'suspended']);
        return back()->with('success', 'ব্যবহারকারী স্থগিত করা হয়েছে।');
    }

    public function activate(User $user)
    {
        $user->update(['status' => 'active']);
        AdminActivityLog::record("Activated user #{$user->id} ({$user->phone})", $user, ['status' => $user->status], ['status' => 'active']);
        return back()->with('success', 'ব্যবহারকারী সক্রিয় করা হয়েছে।');
    }
}
