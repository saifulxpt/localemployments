<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.users.show', compact('user'));
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
