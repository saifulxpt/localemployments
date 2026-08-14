@extends('layouts.seeker')

@section('title', 'কাজের অনুরোধ সম্পাদনা - ' . $jobRequest->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('seeker.job-requests.show', $jobRequest->id) }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">কাজের অনুরোধ সম্পাদনা করুন</h1>
    </div>

    <form action="{{ route('seeker.job-requests.update', $jobRequest->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 md:p-8 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">কাজের তথ্য</h2>

            {{-- Title --}}
            <div class="form-group mb-6">
                <label class="form-label">কাজের শিরোনাম <span class="text-red-500">*</span></label>
                <input type="text" name="title" class="input" value="{{ old('title', $jobRequest->title) }}" required>
                @error('title') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group mb-0">
                <label class="form-label">কাজের বিস্তারিত বিবরণ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="input" required minlength="20">{{ old('description', $jobRequest->description) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">অন্তত ২০টি অক্ষর হতে হবে।</p>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900 mb-6">বাজেট ও সময়সূচী</h2>

            {{-- Budget --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group mb-0">
                    <label class="form-label">নূন্যতম বাজেট (৳)</label>
                    <input type="number" name="budget_min" class="input" value="{{ old('budget_min', $jobRequest->budget_min) }}" min="0" placeholder="ঐচ্ছিক">
                    @error('budget_min') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">সর্বোচ্চ বাজেট (৳)</label>
                    <input type="number" name="budget_max" class="input" value="{{ old('budget_max', $jobRequest->budget_max) }}" min="0" placeholder="ঐচ্ছিক">
                    @error('budget_max') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Preferred Date & Time --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group mb-0">
                    <label class="form-label">কাজের তারিখ</label>
                    <input type="date" name="preferred_date" class="input" value="{{ old('preferred_date', $jobRequest->preferred_date?->format('Y-m-d')) }}">
                    @error('preferred_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">পছন্দের সময়</label>
                    <input type="text" name="preferred_time" class="input" value="{{ old('preferred_time', $jobRequest->preferred_time) }}" placeholder="যেমন: সকাল ১০টা">
                    @error('preferred_time') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Flexibility --}}
            <div class="form-group mb-0">
                <label class="form-label">জরুরিতা / নমনীয়তা <span class="text-red-500">*</span></label>
                <select name="flexibility" class="input" required>
                    <option value="fixed" @selected(old('flexibility', $jobRequest->flexibility) == 'fixed')>নির্দিষ্ট সময়েই প্রয়োজন (Fixed)</option>
                    <option value="flexible" @selected(old('flexibility', $jobRequest->flexibility) == 'flexible')>যেকোনো সুবিধাজনক সময়ে (Flexible)</option>
                    <option value="urgent" @selected(old('flexibility', $jobRequest->flexibility) == 'urgent')>খুব দ্রুত / জরুরি (Urgent)</option>
                </select>
                @error('flexibility') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-6 md:p-8 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('seeker.job-requests.show', $jobRequest->id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                বাতিল করুন
            </a>
            <button type="submit" class="btn btn-primary w-full sm:w-auto px-8 py-3.5 text-base font-bold shadow-md hover:shadow-lg transition-all">
                আপডেট সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
