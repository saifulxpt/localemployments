@extends('layouts.admin')

@section('title', 'ফিচার্ড প্রোভাইডার ম্যানেজমেন্ট')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">ফিচার্ড প্রোভাইডার</h1>
            <p class="text-sm text-gray-500">স্পন্সরড/ফিচার্ড প্রোভাইডারদের সাবস্ক্রিপশন লিস্ট।</p>
        </div>
    </div>

    {{-- Manual Grant Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="font-bold text-gray-900 mb-4 text-lg">ম্যানুয়ালি ফিচার্ড মর্যাদা দিন (ফ্রি)</h2>
        <form x-data="{ providerId: '', duration: '7' }" :action="'/admin/featured/' + providerId + '/grant'" method="POST" class="flex flex-col sm:flex-row gap-4">
            @csrf
            
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-700 mb-1">প্রোভাইডার নির্বাচন করুন</label>
                <select x-model="providerId" class="input w-full" required>
                    <option value="">-- প্রোভাইডার নির্বাচন করুন --</option>
                    @foreach($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->name }} ({{ $provider->phone }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">মেয়াদ (দিন)</label>
                <input type="number" name="duration_days" x-model="duration" class="input w-full" min="1" max="365" required>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 w-full sm:w-auto" :disabled="!providerId">ফিচার্ড করুন</button>
            </div>
        </form>
    </div>

    {{-- Subscriptions List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">আইডি</th>
                        <th class="px-6 py-4">প্রোভাইডার</th>
                        <th class="px-6 py-4 text-center">মেয়াদ</th>
                        <th class="px-6 py-4">শুরু - শেষ</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($featured as $sub)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">#{{ $sub->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $sub->provider_id) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $sub->provider->name ?? 'Unknown' }}
                                </a>
                                @if($sub->amount == 0)
                                    <span class="ml-2 inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">Manual / Free</span>
                                @else
                                    <span class="ml-2 inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-green-100 text-green-700 border border-green-200">Paid: ৳{{ $sub->amount }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900">
                                {{ $sub->duration_days }} দিন
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs">
                                    <div class="text-green-600 mb-0.5">শুরু: {{ $sub->starts_at->format('d M, Y h:i A') }}</div>
                                    <div class="text-red-600">শেষ: {{ $sub->ends_at->format('d M, Y h:i A') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->ends_at > now() && $sub->status === 'active')
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">Active</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Expired</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                কোনো ফিচার্ড সাবস্ক্রিপশন পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($featured->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $featured->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
