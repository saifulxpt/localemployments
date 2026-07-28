@extends('layouts.admin')

@section('title', 'নতুন ক্যাটাগরি তৈরি')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">নতুন ক্যাটাগরি যোগ করুন</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">ক্যাটাগরির নাম <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input w-full" value="{{ old('name') }}" placeholder="যেমন: ক্লিনিং সার্ভিস" required>
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">আইকন (Emoji/SVG/Class) <span class="text-red-500">*</span></label>
                    <input type="text" name="icon" class="input w-full" value="{{ old('icon') }}" placeholder="যেমন: 🧹 বা SVG কোড" required>
                    <p class="text-xs text-gray-500 mt-1">ইমোজি বা যেকোনো ফন্ট আইকন ক্লাস ব্যবহার করতে পারেন।</p>
                    @error('icon') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">বিবরণ (ঐচ্ছিক)</label>
                    <textarea name="description" rows="3" class="input w-full" placeholder="ক্যাটাগরি সম্পর্কে কিছু লিখুন...">{{ old('description') }}</textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">সর্ট অর্ডার (Sort Order)</label>
                    <input type="number" name="sort_order" class="input w-full sm:w-1/3" value="{{ old('sort_order', 0) }}" min="0">
                    <p class="text-xs text-gray-500 mt-1">ছোট সংখ্যা আগে দেখাবে।</p>
                    @error('sort_order') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4 justify-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline border-gray-200">বাতিল</a>
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700">ক্যাটাগরি সেভ করুন</button>
            </div>
        </form>
    </div>

</div>
@endsection
