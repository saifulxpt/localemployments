@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500">Overview of platform status and metrics.</p>
    </div>

    {{-- Main Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <div class="bg-blue-600 rounded-2xl p-6 shadow-sm border border-blue-700 text-white flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-blue-100 text-sm font-medium">Total Users</div>
                <div class="bg-white/20 p-1.5 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold">{{ number_format($stats['total_users']) }}</div>
            <div class="text-xs text-blue-200 mt-2 flex gap-3">
                <span>{{ number_format($stats['total_providers']) }} Providers</span>
                <span>{{ number_format($stats['total_seekers']) }} Seekers</span>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-gray-500 text-sm font-medium">Completed Bookings</div>
                <div class="bg-green-50 p-1.5 rounded-lg border border-green-100">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['completed']) }}</div>
            <div class="text-xs text-gray-400 mt-2">Total Bookings: {{ number_format($stats['total_bookings']) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-gray-500 text-sm font-medium">Platform Income (Fee)</div>
                <div class="bg-primary-50 p-1.5 rounded-lg border border-primary-100">
                    <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">৳{{ number_format($stats['platform_income']) }}</div>
            <div class="text-xs text-gray-400 mt-2">Total Volume: ৳{{ number_format($stats['total_revenue']) }}</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex flex-col justify-between">
            <div class="flex justify-between items-start mb-2">
                <div class="text-gray-500 text-sm font-medium">Open Jobs</div>
                <div class="bg-indigo-50 p-1.5 rounded-lg border border-indigo-100">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-900">{{ number_format($stats['open_jobs']) }}</div>
            <a href="{{ route('admin.job-requests.index') }}" class="text-xs font-semibold text-blue-600 hover:underline mt-2">View All Jobs &rarr;</a>
        </div>
    </div>

    {{-- Attention Required Grid --}}
    <h2 class="text-lg font-bold text-gray-900 mt-8 mb-4">Pending Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 flex items-center justify-between {{ $stats['pending_withdrawals'] > 0 ? 'bg-red-50/30' : '' }}">
            <div>
                <h3 class="font-bold text-gray-900 text-xl">{{ $stats['pending_withdrawals'] }}</h3>
                <p class="text-sm text-gray-500">Pending Withdrawals</p>
            </div>
            <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="btn btn-outline border-gray-200 btn-sm">Review</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-orange-100 p-5 flex items-center justify-between {{ $stats['open_disputes'] > 0 ? 'bg-orange-50/30' : '' }}">
            <div>
                <h3 class="font-bold text-gray-900 text-xl">{{ $stats['open_disputes'] }}</h3>
                <p class="text-sm text-gray-500">Open Disputes</p>
            </div>
            <a href="{{ route('admin.disputes.index', ['status' => 'open']) }}" class="btn btn-outline border-gray-200 btn-sm">Review</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5 flex items-center justify-between {{ $stats['pending_verifications'] > 0 ? 'bg-blue-50/30' : '' }}">
            <div>
                <h3 class="font-bold text-gray-900 text-xl">{{ $stats['pending_verifications'] }}</h3>
                <p class="text-sm text-gray-500">Pending Verifications</p>
            </div>
            <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline border-gray-200 btn-sm">Review</a>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        
        {{-- Recent Bookings --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-900">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="font-semibold text-gray-900 hover:text-blue-600 line-clamp-1">
                                বুকিং #{{ $booking->id }} — 
                                {{ $booking->jobRequest ? $booking->jobRequest->title : ($booking->directService ? $booking->directService->title : 'অজানা কাজ') }}
                            </a>
                            <div class="shrink-0 text-xs font-semibold px-2 py-1 rounded {{ $booking->status === 'completed' ? 'bg-green-100 text-green-700' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ ucfirst($booking->status) }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center gap-1">
                                <span class="font-medium">{{ $booking->seeker->name }}</span>
                                <span>&rarr;</span>
                                <span class="font-medium text-gray-700">{{ $booking->provider->name }}</span>
                            </div>
                            <span class="font-bold text-gray-900">৳{{ number_format($booking->service_amount) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">কোনো বুকিং নেই।</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-900">New Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentUsers as $u)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <img src="{{ $u->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover bg-gray-100">
                            <div>
                                <a href="{{ route('admin.users.show', $u->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $u->name }}</a>
                                <div class="text-xs text-gray-500">{{ $u->phone }} • {{ $u->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $u->role === 'provider' ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">কোনো ইউজার নেই।</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
