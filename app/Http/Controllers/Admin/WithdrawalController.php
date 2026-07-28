<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\AdminActivityLog;
use App\Services\SmsService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(private SmsService $sms, private NotificationService $notify) {}

    public function index(Request $request)
    {
        $query = WithdrawalRequest::with('provider')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        $withdrawals = $query->paginate(20)->withQueryString();
        $pendingTotal = WithdrawalRequest::where('status', 'pending')->sum('amount');
        return view('admin.withdrawals.index', compact('withdrawals', 'pendingTotal'));
    }

    public function show(WithdrawalRequest $withdrawal)
    {
        $withdrawal->load('provider', 'processedBy');
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Request $request, WithdrawalRequest $withdrawal)
    {
        abort_if($withdrawal->status !== 'pending', 403);

        $withdrawal->update([
            'status'       => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_note'   => $request->admin_note,
        ]);

        $this->sms->sendWithdrawalProcessed($withdrawal);
        $this->notify->send($withdrawal->provider, 'উত্তোলন অনুমোদিত', "৳" . number_format($withdrawal->amount) . " উত্তোলন অনুমোদন হয়েছে।", 'payment');
        AdminActivityLog::record("Approved withdrawal #{$withdrawal->id}", $withdrawal);

        return back()->with('success', 'উত্তোলন অনুমোদিত হয়েছে।');
    }

    public function reject(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate(['admin_note' => ['required', 'string']]);

        $withdrawal->update([
            'status'       => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_note'   => $request->admin_note,
        ]);

        $this->notify->send($withdrawal->provider, 'উত্তোলন প্রত্যাখ্যাত', "আপনার উত্তোলন অনুরোধ গ্রহণ হয়নি। কারণ: {$request->admin_note}", 'payment');
        AdminActivityLog::record("Rejected withdrawal #{$withdrawal->id}", $withdrawal);

        return back()->with('success', 'উত্তোলন প্রত্যাখ্যাত হয়েছে।');
    }
}
