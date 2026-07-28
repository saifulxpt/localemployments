@extends('layouts.public')

@section('title', 'যোগাযোগ')
@section('meta_description', 'আমাদের সাথে যোগাযোগ করুন।')

@section('content')
<div class="bg-gray-50 py-16">
    <div class="container mx-auto px-4 max-w-5xl">
        
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">যোগাযোগ করুন</h1>
            <p class="text-xl text-gray-600">যেকোনো প্রশ্ন, অভিযোগ বা মতামতের জন্য আমাদের জানান।</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            
            {{-- Contact Info --}}
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">ফোন</h3>
                    <p class="text-gray-600">{{ setting('contact_phone', '01XXXXXXXXX') }}</p>
                </div>
                
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">ইমেইল</h3>
                    <p class="text-gray-600">{{ setting('contact_email', 'support@localemployments.com') }}</p>
                </div>
                
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">অফিস</h3>
                    <p class="text-gray-600">ঢাকা, বাংলাদেশ</p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">বার্তা পাঠান</h2>
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group mb-0">
                                <label for="name" class="form-label">নাম</label>
                                <input type="text" id="name" name="name" class="input" required value="{{ old('name') }}">
                                @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="phone" class="form-label">ফোন নম্বর</label>
                                <input type="tel" id="phone" name="phone" class="input" required value="{{ old('phone') }}">
                                @error('phone')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">বার্তা</label>
                            <textarea id="message" name="message" rows="5" class="input" required>{{ old('message') }}</textarea>
                            @error('message')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-full py-3">পাঠিয়ে দিন</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
