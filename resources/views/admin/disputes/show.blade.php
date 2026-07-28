@extends('layouts.admin')

@section('title', 'ডিসপুট বিস্তারিত #DSP-' . str_pad($dispute->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.disputes.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            ডিসপুট লিস্ট
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">ডিসপুট #DSP-{{ str_pad($dispute->id, 4, '0', STR_PAD_LEFT) }}</h1>
                        <p class="text-sm text-gray-500 mt-1">তৈরি হয়েছে: {{ $dispute->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                    @if($dispute->status === 'open')
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">Open (বিচারাধীন)</span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Resolved (মীমাংসিত)</span>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">অভিযোগের কারণ</h3>
                    <div class="bg-red-50 text-red-900 p-4 rounded-xl border border-red-100 text-sm whitespace-pre-wrap leading-relaxed">{{ $dispute->reason }}</div>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 mb-6 flex flex-col sm:flex-row gap-6 justify-between items-center">
                    <div class="text-center w-full">
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">সিকার (ক্লায়েন্ট)</div>
                        <img src="{{ $dispute->booking->seeker->avatar_url }}" class="w-12 h-12 rounded-full mx-auto mb-2 border-2 {{ $dispute->raised_by === $dispute->booking->seeker_id ? 'border-red-500' : 'border-gray-200' }}">
                        <a href="{{ route('admin.users.show', $dispute->booking->seeker->id) }}" class="font-bold text-gray-900 hover:text-blue-600 text-sm block">{{ $dispute->booking->seeker->name }}</a>
                        @if($dispute->raised_by === $dispute->booking->seeker_id)
                            <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded mt-1 inline-block font-semibold">অভিযোগকারী</span>
                        @endif
                    </div>
                    
                    <div class="hidden sm:block text-gray-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>

                    <div class="text-center w-full">
                        <div class="text-xs font-bold text-gray-500 uppercase mb-2">প্রোভাইডার</div>
                        <img src="{{ $dispute->booking->provider->avatar_url }}" class="w-12 h-12 rounded-full mx-auto mb-2 border-2 {{ $dispute->raised_by === $dispute->booking->provider_id ? 'border-red-500' : 'border-gray-200' }}">
                        <a href="{{ route('admin.users.show', $dispute->booking->provider->id) }}" class="font-bold text-gray-900 hover:text-blue-600 text-sm block">{{ $dispute->booking->provider->name }}</a>
                        @if($dispute->raised_by === $dispute->booking->provider_id)
                            <span class="text-[10px] bg-red-100 text-red-700 px-2 py-0.5 rounded mt-1 inline-block font-semibold">অভিযোগকারী</span>
                        @endif
                    </div>
                </div>

                @if($dispute->status === 'open')
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-4 text-lg">মীমাংসা করুন (Resolve)</h3>
                        <p class="text-sm text-gray-600 mb-4">উভয় পক্ষের সাথে কথা বলে সমস্যার সমাধান করুন এবং নিচের বক্সে সমাধানের বিস্তারিত লিখে 'Resolve' বাটনে ক্লিক করুন।</p>
                        
                        <form action="{{ route('admin.disputes.resolve', $dispute->id) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-semibold text-gray-700 mb-2">সমাধানের বিবরণ (উভয় পক্ষ এটি দেখতে পারবে)</label>
                            <textarea name="resolution" rows="4" class="input mb-4" placeholder="সমস্যার সমাধান কিভাবে হলো বিস্তারিত লিখুন..." required></textarea>
                            
                            <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 border-green-600 w-full" onclick="return confirm('আপনি কি নিশ্চিত যে এই ডিসপুটটি মীমাংসা করা হয়েছে?')">
                                Mark as Resolved (মীমাংসা নিশ্চিত করুন)
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="font-bold text-green-800 mb-3 text-lg flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            মীমাংসার বিবরণ (Resolution)
                        </h3>
                        <div class="bg-green-50 p-5 rounded-xl border border-green-200">
                            <p class="text-sm text-green-900 whitespace-pre-wrap leading-relaxed">{{ $dispute->resolution }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-green-200 flex justify-between items-center text-xs text-green-800">
                                <div><span class="font-semibold">মীমাংসা করেছেন:</span> {{ $dispute->resolvedBy->name ?? 'Admin' }}</div>
                                <div>{{ $dispute->resolved_at ? \Carbon\Carbon::parse($dispute->resolved_at)->format('d M, Y h:i A') : '' }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">বুকিং তথ্য</h3>
                
                <div class="mb-4">
                    <span class="block text-xs text-gray-500 mb-1">বুকিং আইডি</span>
                    <a href="{{ route('admin.bookings.show', $dispute->booking_id) }}" class="font-bold text-blue-600 hover:underline text-lg">#{{ $dispute->booking_id }}</a>
                </div>
                
                <div class="mb-4">
                    <span class="block text-xs text-gray-500 mb-1">বুকিং স্ট্যাটাস</span>
                    <span class="font-bold text-gray-900">{{ ucfirst($dispute->booking->status) }}</span>
                </div>
                
                <div class="mb-4">
                    <span class="block text-xs text-gray-500 mb-1">অ্যামাউন্ট</span>
                    <span class="font-bold text-gray-900">৳{{ number_format($dispute->booking->service_amount) }}</span>
                </div>
                
                <a href="{{ route('admin.bookings.show', $dispute->booking_id) }}" class="btn btn-outline border-gray-200 w-full mt-2 btn-sm">সম্পূর্ণ বুকিং দেখুন</a>
            </div>

            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5">
                <h3 class="font-bold text-blue-900 text-sm mb-2">পরামর্শ</h3>
                <ul class="text-xs text-blue-800 space-y-2 list-disc pl-4">
                    <li>সমস্যা সমাধানের জন্য প্রথমে বুকিংয়ের মেসেজগুলো চেক করুন।</li>
                    <li>প্রয়োজনে ইউজার ও প্রোভাইডারকে ফোন করে বিস্তারিত জানুন।</li>
                    <li>টাকা রিফান্ড করার প্রয়োজন হলে বুকিং স্ট্যাটাস পরিবর্তন করুন।</li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection
