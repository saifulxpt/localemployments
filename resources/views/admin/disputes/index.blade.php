@extends('layouts.admin')

@section('title', 'ডিসপুট (Disputes)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">বুকিং ডিসপুট</h1>
            <p class="text-sm text-gray-500">বুকিং সংক্রান্ত কোনো সমস্যা বা অভিযোগের তালিকা।</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.disputes.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="input" onchange="this.form.submit()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="open" @selected(request('status') === 'open')>Open (বিচারাধীন)</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Resolved (মীমাংসিত)</option>
                </select>
            </div>
            
            @if(request('status'))
                <a href="{{ route('admin.disputes.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="রিসেট">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Disputes List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ডিসপুট আইডি / বুকিং</th>
                        <th class="px-6 py-4">অভিযোগকারী</th>
                        <th class="px-6 py-4">কারণ (Reason)</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4">তারিখ</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($disputes as $dispute)
                        <tr class="hover:bg-gray-50 transition-colors {{ $dispute->status === 'open' ? 'bg-orange-50/20' : '' }}">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="font-bold text-gray-900 hover:text-blue-600">
                                    #DSP-{{ str_pad($dispute->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                                <div class="text-xs text-gray-500 mt-1">
                                    <a href="{{ route('admin.bookings.show', $dispute->booking_id) }}" class="hover:text-blue-600">বুকিং #{{ $dispute->booking_id }}</a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $dispute->raisedBy->id) }}" class="font-medium text-gray-900 hover:text-blue-600 block">
                                    {{ $dispute->raisedBy->name }}
                                </a>
                                <div class="text-[10px] text-gray-400 mt-0.5 uppercase font-semibold">
                                    {{ $dispute->raisedBy->role }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-2">{{ $dispute->reason }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($dispute->status === 'open')
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-orange-100 text-orange-800 border border-orange-200">Open</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-semibold bg-green-100 text-green-800 border border-green-200">Resolved</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $dispute->created_at->format('d M, y') }}</div>
                                <div class="text-xs text-gray-400">{{ $dispute->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.disputes.show', $dispute->id) }}" class="btn btn-outline border-gray-200 btn-sm hover:border-blue-300 hover:text-blue-600">বিস্তারিত</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো ডিসপুট নেই।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($disputes->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $disputes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
