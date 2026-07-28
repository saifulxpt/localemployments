@extends('layouts.admin')

@section('title', 'কাজের রিকোয়েস্ট (Job Requests)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">কাজের রিকোয়েস্ট</h1>
            <p class="text-sm text-gray-500">ইউজারদের পোস্ট করা সকল জব রিকোয়েস্ট।</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.job-requests.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="input" onchange="this.form.submit()">
                    <option value="">সকল স্ট্যাটাস</option>
                    <option value="open" @selected(request('status') === 'open')>Open</option>
                    <option value="assigned" @selected(request('status') === 'assigned')>Assigned</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            
            @if(request('status'))
                <a href="{{ route('admin.job-requests.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="রিসেট">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Job Requests List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">কাজের বিবরণ</th>
                        <th class="px-6 py-4">সিকার (পোস্টদাতা)</th>
                        <th class="px-6 py-4">বাজেট</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4">তারিখ</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jobRequests as $job)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 line-clamp-1 max-w-[250px] mb-1">
                                    <a href="{{ route('admin.job-requests.show', $job->id) }}" class="hover:text-blue-600">{{ $job->title }}</a>
                                </div>
                                <div class="text-xs text-blue-700 bg-blue-50 border border-blue-100 inline-block px-2 py-0.5 rounded font-semibold">
                                    {{ $job->subcategory->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $job->seeker->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                    <div>
                                        <a href="{{ route('admin.users.show', $job->seeker->id) }}" class="font-medium text-gray-900 hover:text-blue-600">{{ $job->seeker->name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900">{{ $job->budget_range ?: 'আলোচনা সাপেক্ষে' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $job->status === 'open' ? 'bg-green-50 text-green-700 border-green-200' : ($job->status === 'assigned' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-700 border-gray-200') }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $job->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $job->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.job-requests.show', $job->id) }}" class="btn btn-outline border-gray-200 btn-sm hover:border-blue-300 hover:text-blue-600">বিস্তারিত</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো কাজের রিকোয়েস্ট পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($jobRequests->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $jobRequests->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
