@extends('layouts.admin')

@section('title', 'নতুন জেলা তৈরি')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.locations.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">নতুন জেলা যোগ করুন</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8">
        <form action="{{ route('admin.districts.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">জেলার নাম (English) <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input w-full" value="{{ old('name') }}" placeholder="e.g. Dhaka" required>
                    @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">জেলার নাম (বাংলা) <span class="text-red-500">*</span></label>
                    <input type="text" name="bn_name" class="input w-full" value="{{ old('bn_name') }}" placeholder="যেমন: ঢাকা" required>
                    @error('bn_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex gap-4 justify-end">
                <a href="{{ route('admin.locations.index') }}" class="btn btn-outline border-gray-200">বাতিল</a>
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700">সেভ করুন</button>
            </div>
        </form>
    </div>

</div>
@endsection
