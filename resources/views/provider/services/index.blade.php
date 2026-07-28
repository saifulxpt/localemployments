@extends('layouts.provider')

@section('title', 'আমার সরাসরি সেবাসমূহ')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">আমার সেবাসমূহ</h1>
            <p class="text-sm text-gray-500">আপনার নিজস্ব প্যাকেজ বা সেবা যোগ করুন যা কাস্টমাররা সরাসরি বুক করতে পারবে।</p>
        </div>
        <a href="{{ route('provider.services.create') }}" class="btn btn-primary whitespace-nowrap">
            + নতুন সেবা যোগ করুন
        </a>
    </div>

    @if($services->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($services as $service)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                    
                    @if($service->photos && count($service->photos) > 0)
                        <img src="{{ Storage::url($service->photos[0]) }}" alt="{{ $service->title }}" class="w-full h-40 object-cover bg-gray-50 border-b border-gray-100">
                    @else
                        <div class="w-full h-40 bg-gray-50 border-b border-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-bold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-md border border-primary-100">
                                {{ $service->subcategory->name }}
                            </span>
                            <span class="text-xs font-semibold {{ $service->is_active ? 'text-green-600 bg-green-50' : 'text-gray-500 bg-gray-100' }} px-2.5 py-1 rounded-md border {{ $service->is_active ? 'border-green-200' : 'border-gray-200' }}">
                                {{ $service->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-lg mb-1 line-clamp-1"><a href="{{ route('provider.services.show', $service->id) }}" class="hover:text-primary-600">{{ $service->title }}</a></h3>
                        
                        <div class="text-lg font-extrabold text-primary-700 mb-3">
                            ৳{{ number_format($service->price) }} 
                            <span class="text-xs font-normal text-gray-500">
                                {{ $service->price_type === 'hourly' ? '/ঘন্টা' : ($service->price_type === 'starting_from' ? 'থেকে শুরু' : '(ফিক্সড)') }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ $service->description }}</p>

                        <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4">
                            <a href="{{ route('provider.services.edit', $service->id) }}" class="text-sm font-semibold text-gray-600 hover:text-primary-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                এডিট
                            </a>
                            
                            <form action="{{ route('provider.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই সেবাটি মুছে ফেলতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-500 hover:text-red-700 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    মুছে ফেলুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো সেবা যোগ করা হয়নি</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">কাস্টমাররা যাতে সরাসরি আপনার সেবা বুক করতে পারে, সেজন্য নির্দিষ্ট মূল্য এবং বিবরণ দিয়ে আপনার নিজস্ব সেবা প্যাকেজ তৈরি করুন।</p>
            <a href="{{ route('provider.services.create') }}" class="btn btn-primary">প্রথম সেবা যোগ করুন</a>
        </div>
    @endif

</div>
@endsection
