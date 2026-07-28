<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\WithdrawalRequest;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function __construct(private SmsService $sms) {}

    public function index()
    {
        $user        = Auth::user();
        $withdrawals = WithdrawalRequest::where('provider_id', $user->id)->latest()->paginate(10);
        $available   = $this->getAvailableBalance($user->id);
        $minWithdraw = (int) setting('min_withdrawal', 200);

        return view('provider.withdrawals.index', compact('withdrawals', 'available', 'minWithdraw'));
    }

    public function create()
    {
        $user      = Auth::user();
        $available = $this->getAvailableBalance($user->id);
        $minWithdraw = (int) setting('min_withdrawal', 200);

        if ($available < $minWithdraw) {
            return redirect()->route('provider.withdrawals.index')
                ->with('error', "উত্তোলন করতে কমপক্ষে ৳{$minWithdraw} ব্যালেন্স থাকতে হবে।");
        }

        return view('provider.withdrawals.create', compact('available', 'minWithdraw'));
    }

    public function store(Request $request)
    {
        $user      = Auth::user();
        $available = $this->getAvailableBalance($user->id);
        $minWithdraw = (int) setting('min_withdrawal', 200);

        $data = $request->validate([
            'amount'         => ['required', 'numeric', "min:{$minWithdraw}", "max:{$available}"],
            'method'         => ['required', 'in:bkash,nagad,bank'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name'   => ['required', 'string', 'max:150'],
            'bank_name'      => ['nullable', 'required_if:method,bank', 'string', 'max:150'],
            'branch_name'    => ['nullable', 'string', 'max:150'],
            'routing_number' => ['nullable', 'string', 'max:50'],
        ]);

        WithdrawalRequest::create(array_merge($data, ['provider_id' => $user->id]));

        return redirect()->route('provider.withdrawals.index')
            ->with('success', 'উত্তোলন অনুরোধ পাঠানো হয়েছে। ১-৩ কার্যদিবসের মধ্যে প্রক্রিয়া করা হবে।');
    }

    private function getAvailableBalance(int $providerId): float
    {
        $earned    = Booking::where('provider_id', $providerId)->where('status', 'completed')->sum('provider_earning');
        $withdrawn = WithdrawalRequest::where('provider_id', $providerId)->where('status', 'approved')->sum('amount');
        $pending   = WithdrawalRequest::where('provider_id', $providerId)->whereIn('status', ['pending', 'processing'])->sum('amount');
        return max(0, $earned - $withdrawn - $pending);
    }
}
