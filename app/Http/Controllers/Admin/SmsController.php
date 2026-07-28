<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __construct(private SmsService $sms) {}

    public function show()
    {
        return view('admin.sms.send');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'target'  => ['required', 'in:all,seekers,providers,specific'],
            'phone'   => ['nullable', 'required_if:target,specific', 'string'],
            'message' => ['required', 'string', 'max:160'],
        ]);

        $phones = match ($data['target']) {
            'all'       => User::where('phone_verified', true)->pluck('phone'),
            'seekers'   => User::where('role', 'seeker')->where('phone_verified', true)->pluck('phone'),
            'providers' => User::where('role', 'provider')->where('phone_verified', true)->pluck('phone'),
            'specific'  => collect([$data['phone']]),
        };

        $sentCount = 0;
        foreach ($phones as $phone) {
            if ($this->sms->send($phone, $data['message'], 'bulk')) {
                $sentCount++;
            }
        }

        return back()->with('success', "{$sentCount}টি SMS পাঠানো হয়েছে।");
    }

    public function logs(Request $request)
    {
        $logs = SmsLog::latest()->paginate(30);
        return view('admin.sms.logs', compact('logs'));
    }
}
