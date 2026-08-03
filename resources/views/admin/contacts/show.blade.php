@extends('layouts.admin')

@section('title', 'ম্যাসেজ বিস্তারিত')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            সব ম্যাসেজ
        </a>
        
        <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই ম্যাসেজটি মুছে ফেলতে চান?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline text-red-500 hover:bg-red-50 border-red-200 btn-sm">ডিলিট করুন</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <div class="flex items-start justify-between border-b border-gray-100 pb-6 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $contactMessage->name }}</h2>
                <div class="text-gray-600 flex items-center gap-4 text-sm">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $contactMessage->phone }}
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500">
                        {{ $contactMessage->created_at->format('d M Y, h:i A') }}
                        ({{ $contactMessage->created_at->diffForHumans() }})
                    </span>
                </div>
            </div>
        </div>
        
        <div class="prose prose-gray max-w-none">
            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $contactMessage->message }}</p>
        </div>
    </div>

</div>
@endsection
