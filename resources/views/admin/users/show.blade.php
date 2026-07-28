@extends('layouts.admin')

@section('title', 'ইউজার বিস্তারিত: ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            সব ইউজার
        </a>
        
        <div class="flex gap-2">
            @if($user->id !== auth()->id())
                @if($user->status === 'active')
                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ইউজারকে সাসপেন্ড করতে চান?')">
                        @csrf
                        <button type="submit" class="btn btn-outline text-red-500 hover:bg-red-50 border-red-200 btn-sm">সাসপেন্ড করুন</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 border-green-600 btn-sm">অ্যাক্টিভেট করুন</button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Profile Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="relative inline-block mb-4">
                    <img src="{{ $user->avatar_url }}" alt="" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                    @if($user->status === 'active')
                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full" title="Active"></div>
                    @else
                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-red-500 border-2 border-white rounded-full" title="Suspended"></div>
                    @endif
                </div>
                
                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
                <div class="text-sm text-gray-500 mb-4">{{ $user->phone }}</div>
                
                <div class="flex justify-center gap-2 mb-6">
                    <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $user->role === 'provider' ? 'bg-indigo-100 text-indigo-700' : ($user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                        Role: {{ ucfirst($user->role) }}
                    </span>
                    @if($user->role === 'provider' && $user->providerProfile?->is_verified)
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified
                        </span>
                    @endif
                </div>
                
                <div class="border-t border-gray-100 pt-4 text-left space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">জয়েনিং ডেট:</span>
                        <span class="font-medium text-gray-900">{{ $user->created_at->format('d M, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">শেষ লগইন:</span>
                        <span class="font-medium text-gray-900">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'জানা নেই' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">ইমেইল:</span>
                        <span class="font-medium text-gray-900">{{ $user->email ?: 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">লোকেশন</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">জেলা:</span>
                        <span class="font-medium text-gray-900">{{ $user->district ? $user->district->bn_name : 'সেট করা নেই' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">এলাকা:</span>
                        <span class="font-medium text-gray-900">{{ $user->area ? $user->area->bn_name : 'সেট করা নেই' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">ঠিকানা:</span>
                        <span class="font-medium text-gray-900">{{ $user->address ?: 'সেট করা নেই' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Role Specific Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            @if($user->role === 'provider')
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-6">প্রোভাইডার প্রোফাইল</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="text-gray-500 text-xs mb-1">সম্পন্ন কাজ</div>
                            <div class="text-xl font-bold text-gray-900">{{ $user->providerProfile->total_jobs ?? 0 }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="text-gray-500 text-xs mb-1">রেটিং</div>
                            <div class="text-xl font-bold text-gray-900 flex items-center gap-1">
                                <span class="text-yellow-500 text-base">★</span> {{ number_format($user->providerProfile->rating_avg ?? 0, 1) }}
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="text-gray-500 text-xs mb-1">ভেরিফিকেশন</div>
                            <div class="font-bold text-sm mt-1">
                                @if($user->providerProfile?->is_verified)
                                    <span class="text-green-600">যাচাইকৃত</span>
                                @elseif($user->providerProfile?->verification_status === 'pending')
                                    <span class="text-yellow-600">পেন্ডিং</span>
                                @else
                                    <span class="text-gray-600">যাচাই করা নেই</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <div class="text-gray-500 text-xs mb-1">ফিচার্ড (Featured)</div>
                            <div class="font-bold text-sm mt-1">
                                @if($user->providerProfile?->is_featured && $user->providerProfile?->featured_until > now())
                                    <span class="text-primary-600">হ্যাঁ ({{ \Carbon\Carbon::parse($user->providerProfile->featured_until)->format('d M y') }} পর্যন্ত)</span>
                                @else
                                    <span class="text-gray-600">না</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-900 text-sm mb-3">অভিজ্ঞতা ও বায়ো</h4>
                    <p class="text-gray-600 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100 whitespace-pre-wrap">{{ $user->providerProfile->bio ?? 'কোনো বায়ো নেই' }}</p>
                    <div class="mt-2 text-sm text-gray-600">
                        <span class="font-semibold text-gray-700">অভিজ্ঞতা:</span> {{ $user->providerProfile->experience_years ?? 0 }} বছর
                    </div>

                    <h4 class="font-bold text-gray-900 text-sm mt-6 mb-3">দক্ষতাসমূহ (Skills)</h4>
                    @if($user->providerSkills && $user->providerSkills->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->providerSkills as $skill)
                                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-lg text-sm font-medium">
                                    {{ $skill->subcategory->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">কোনো দক্ষতা যোগ করা হয়নি।</p>
                    @endif
                </div>

                {{-- Action Links --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.verifications.show', $user->id) }}" class="btn btn-outline bg-white border-gray-200">ভেরিফিকেশন ডকুমেন্টস দেখুন</a>
                    <a href="{{ route('admin.bookings.index', ['provider_id' => $user->id]) }}" class="btn btn-outline bg-white border-gray-200">সব বুকিং দেখুন</a>
                </div>

            @elseif($user->role === 'seeker')
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-bold text-gray-900 mb-6">সিকার অ্যাক্টিভিটি</h3>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('admin.job-requests.index', ['seeker_id' => $user->id]) }}" class="flex-1 bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-blue-300 transition-colors group">
                            <div class="text-gray-500 text-sm mb-1 group-hover:text-blue-600">পোস্ট করা কাজ</div>
                            <div class="text-2xl font-bold text-gray-900">দেখুন &rarr;</div>
                        </a>
                        <a href="{{ route('admin.bookings.index', ['seeker_id' => $user->id]) }}" class="flex-1 bg-gray-50 p-5 rounded-xl border border-gray-100 hover:border-blue-300 transition-colors group">
                            <div class="text-gray-500 text-sm mb-1 group-hover:text-blue-600">বুকিং হিস্ট্রি</div>
                            <div class="text-2xl font-bold text-gray-900">দেখুন &rarr;</div>
                        </a>
                    </div>
                </div>

            @else
                <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6 text-blue-800">
                    <h3 class="font-bold mb-2">অ্যাডমিন ইউজার</h3>
                    <p class="text-sm">এই ইউজারটি একজন সিস্টেম অ্যাডমিনিস্ট্রেটর। সিস্টেমের সব সেটিংসে তার এক্সেস রয়েছে।</p>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
