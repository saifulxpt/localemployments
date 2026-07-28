@extends('layouts.provider')

@section('title', 'টাকা উত্তোলন (Withdraw)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('provider.withdrawals.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">টাকা উত্তোলন</h1>
    </div>

    <div class="bg-primary-600 rounded-2xl p-6 shadow-sm border border-primary-700 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="text-primary-100 text-sm font-medium mb-1">উত্তোলনযোগ্য ব্যালেন্স</div>
            <div class="text-3xl font-extrabold">৳{{ number_format($available) }}</div>
        </div>
        <div class="bg-primary-700/50 rounded-xl p-3 text-sm border border-primary-500/50">
            ন্যূনতম উত্তোলন: <span class="font-bold">৳{{ number_format($minWithdraw) }}</span>
        </div>
    </div>

    <form action="{{ route('provider.withdrawals.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ method: '{{ old('method', 'bkash') }}' }">
        @csrf

        <div class="p-6 md:p-8 space-y-8">
            
            {{-- Amount --}}
            <div class="form-group mb-0">
                <label class="form-label text-lg">উত্তোলনের পরিমাণ (৳) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" class="input text-2xl font-bold h-16 text-primary-700" 
                       value="{{ old('amount') }}" 
                       min="{{ $minWithdraw }}" 
                       max="{{ $available }}" 
                       placeholder="{{ $minWithdraw }}" required>
                <div class="flex justify-between items-center mt-2">
                    @error('amount') 
                        <span class="text-xs text-red-500 font-medium">{{ $message }}</span>
                    @else
                        <span class="text-xs text-gray-500">আপনার বর্তমান ব্যালেন্সের বেশি উত্তোলন করতে পারবেন না।</span>
                    @enderror
                    <button type="button" class="text-xs font-semibold text-primary-600 hover:underline" onclick="document.querySelector('input[name=amount]').value = {{ $available }}">পুরোটা সিলেক্ট করুন</button>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Method Selection --}}
            <div>
                <label class="form-label text-lg mb-4">পেমেন্ট মেথড নির্বাচন করুন <span class="text-red-500">*</span></label>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="method === 'bkash' ? 'border-pink-500 bg-pink-50/30' : 'border-gray-100 hover:border-gray-200 bg-white'">
                        <input type="radio" name="method" value="bkash" class="sr-only" x-model="method">
                        <div class="text-center">
                            <span class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center font-black text-lg mx-auto mb-2 border border-pink-200">b</span>
                            <span class="font-bold text-gray-900">bKash</span>
                        </div>
                        <div x-show="method === 'bkash'" class="absolute top-2 right-2 text-pink-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </label>

                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="method === 'nagad' ? 'border-orange-500 bg-orange-50/30' : 'border-gray-100 hover:border-gray-200 bg-white'">
                        <input type="radio" name="method" value="nagad" class="sr-only" x-model="method">
                        <div class="text-center">
                            <span class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-black text-lg mx-auto mb-2 border border-orange-200">N</span>
                            <span class="font-bold text-gray-900">Nagad</span>
                        </div>
                        <div x-show="method === 'nagad'" class="absolute top-2 right-2 text-orange-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </label>

                    <label class="relative flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="method === 'bank' ? 'border-blue-500 bg-blue-50/30' : 'border-gray-100 hover:border-gray-200 bg-white'">
                        <input type="radio" name="method" value="bank" class="sr-only" x-model="method">
                        <div class="text-center">
                            <span class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2 border border-blue-200">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </span>
                            <span class="font-bold text-gray-900">Bank</span>
                        </div>
                        <div x-show="method === 'bank'" class="absolute top-2 right-2 text-blue-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                    </label>
                </div>
                @error('method') <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Account Details --}}
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">অ্যাকাউন্ট বিস্তারিত</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group mb-0">
                        <label class="form-label">অ্যাকাউন্টের নাম <span class="text-red-500">*</span></label>
                        <input type="text" name="account_name" class="input" value="{{ old('account_name', auth()->user()->name) }}" placeholder="যাঁর নামে অ্যাকাউন্ট" required>
                        @error('account_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group mb-0">
                        <label class="form-label" x-text="method === 'bank' ? 'অ্যাকাউন্ট নম্বর' : 'মোবাইল নম্বর'"></label>
                        <input type="text" name="account_number" class="input font-semibold" value="{{ old('account_number') }}" required>
                        @error('account_number') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Bank Only Fields --}}
                    <div x-show="method === 'bank'" class="form-group mb-0 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4" x-cloak>
                        <div>
                            <label class="form-label">ব্যাংকের নাম <span class="text-red-500">*</span></label>
                            <input type="text" name="bank_name" class="input" value="{{ old('bank_name') }}" :required="method === 'bank'">
                            @error('bank_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">ব্রাঞ্চের নাম</label>
                            <input type="text" name="branch_name" class="input" value="{{ old('branch_name') }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">রাউটিং নম্বর (Routing Number)</label>
                            <input type="text" name="routing_number" class="input" value="{{ old('routing_number') }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-gray-50 px-6 py-4 md:px-8 flex justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('provider.withdrawals.index') }}" class="btn btn-outline px-6">বাতিল</a>
            <button type="submit" class="btn btn-primary px-8">রিকোয়েস্ট সাবমিট করুন</button>
        </div>
    </form>

</div>
@endsection
