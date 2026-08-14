@extends('layouts.seeker')

@section('title', 'সমস্যা রিপোর্ট করুন (Dispute) - ' . $booking->booking_ref)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">বিরোধ / সমস্যা রিপোর্ট করুন</h1>
    </div>

    {{-- Info Card --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-red-900 text-sm">
        <h3 class="font-bold flex items-center gap-2 text-base mb-1">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            বুকিং রেফারেন্স: {{ $booking->booking_ref }}
        </h3>
        <p class="text-red-700 leading-relaxed text-xs">
            কাজের গুণগত মান, প্রোভাইডারের অনুপস্থিতি বা অতিরিক্ত ফি দাবি সংক্রান্ত কোনো সমস্যা হলে বিস্তারিত বিবরণ ও প্রমাণসহ রিপোর্ট করুন। আমাদের অ্যাডমিন টিম দ্রুত ব্যবস্থা নেবে।
        </p>
    </div>

    {{-- Dispute Form --}}
    <form action="{{ route('seeker.disputes.store', $booking->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf

        <div class="p-6 md:p-8 space-y-6">
            {{-- Reason --}}
            <div class="form-group mb-0">
                <label class="form-label">সমস্যার ধরন বা কারণ <span class="text-red-500">*</span></label>
                <select name="reason" class="input" required>
                    <option value="">নির্বাচন করুন</option>
                    <option value="প্রোভাইডার আসেননি (No Show)" @selected(old('reason') == 'প্রোভাইডার আসেননি (No Show)')>প্রোভাইডার আসেননি (No Show)</option>
                    <option value="কাজের মান খারাপ / অসম্পূর্ণ" @selected(old('reason') == 'কাজের মান খারাপ / অসম্পূর্ণ')>কাজের মান খারাপ / অসম্পূর্ণ</option>
                    <option value="অননুমোদিত অতিরিক্ত টাকা দাবি" @selected(old('reason') == 'অননুমোদিত অতিরিক্ত টাকা দাবি')>অননুমোদিত অতিরিক্ত টাকা দাবি</option>
                    <option value="খারাপ আচরণ / অপেশাদারিত্ব" @selected(old('reason') == 'খারাপ আচরণ / অপেশাদারিত্ব')>খারাপ আচরণ / অপেশাদারিত্ব</option>
                    <option value="অন্যান্য" @selected(old('reason') == 'অন্যান্য')>অন্যান্য</option>
                </select>
                @error('reason') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group mb-0">
                <label class="form-label">সমস্যার বিস্তারিত বিবরণ <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" class="input" placeholder="কী সমস্যা হয়েছে তা বিস্তারিতভাবে লিখুন (অন্তত ২০ অক্ষর)..." required minlength="20">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">অন্তত ২০টি অক্ষর হতে হবে।</p>
                @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Evidence Photos --}}
            <div class="form-group mb-0">
                <label class="form-label">প্রমাণের ছবি (ঐচ্ছিক, সর্বোচ্চ ৩টি)</label>
                <input type="file" name="evidence_photos[]" multiple accept="image/jpeg,image/png,image/jpg" class="input py-2.5">
                <p class="text-xs text-gray-400 mt-1">JPG, JPEG বা PNG ফরম্যাট, প্রতিটির সাইজ সর্বোচ্চ ২MB।</p>
                @error('evidence_photos') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                @error('evidence_photos.*') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('seeker.bookings.show', $booking->id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">
                ফিরে যান
            </a>
            <button type="submit" class="btn bg-red-600 text-white hover:bg-red-700 w-full sm:w-auto px-8 py-3.5 font-bold shadow-md hover:shadow-lg transition-all">
                রিপোর্ট জমা দিন
            </button>
        </div>
    </form>
</div>
@endsection
