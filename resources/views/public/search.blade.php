@extends('layouts.public')

@section('title', 'কর্মী খুঁজুন')
@section('meta_description', 'আপনার এলাকার সেরা ও যাচাইকৃত সার্ভিস প্রোভাইডারদের খুঁজুন।')

@section('content')

{{-- Header Banner --}}
<div class="bg-gray-900 text-white py-12 md:py-16">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4">কর্মী খুঁজুন</h1>
        <p class="text-gray-400 max-w-2xl text-lg">আপনার প্রয়োজনীয় সেবার জন্য সেরা কর্মীটি বেছে নিন। রেটিং, স্কিল এবং লোকেশন অনুযায়ী ফিল্টার করুন।</p>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Sidebar Filters --}}
        <div class="w-full lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-900">ফিল্টার করুন</h2>
                    @if(request()->anyFilled(['category', 'district', 'area', 'verified', 'min_rating', 'q']))
                        <a href="{{ route('search') }}" class="text-sm text-red-500 hover:underline">রিসেট</a>
                    @endif
                </div>

                <form action="{{ route('search') }}" method="GET" x-data="locationSelect('{{ url('ajax/areas') }}')"
                      x-init="if('{{ request('district') }}') { loadAreas('{{ request('district') }}'); setTimeout(() => { $refs.areaSelect.value = '{{ request('area') }}' }, 500); }">
                    
                    {{-- Retain Search Query if any --}}
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    {{-- Category --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ক্যাটাগরি</label>
                        <select name="category" class="input py-2.5 text-sm" onchange="this.form.submit()">
                            <option value="">সব ক্যাটাগরি</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- District --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">জেলা</label>
                        <select name="district" class="input py-2.5 text-sm" 
                                @change="loadAreas($event.target.value); $nextTick(() => { $refs.areaSelect.value = ''; $el.form.submit(); })">
                            <option value="">সব জেলা</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->id }}" @selected(request('district') == $d->id)>
                                    {{ $d->bn_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Area (AJAX) --}}
                    <div class="mb-5" x-show="areas.length > 0" x-cloak>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">এলাকা</label>
                        <select name="area" class="input py-2.5 text-sm" x-ref="areaSelect" onchange="this.form.submit()">
                            <option value="">সব এলাকা</option>
                            <template x-for="a in areas" :key="a.id">
                                <option :value="a.id" x-text="a.bn_name"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Min Rating --}}
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ন্যূনতম রেটিং</label>
                        <div class="space-y-2">
                            @foreach([4, 3, 2, 1] as $rating)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="min_rating" value="{{ $rating }}" 
                                           onchange="this.form.submit()"
                                           @checked(request('min_rating') == $rating)
                                           class="text-primary-600 focus:ring-primary-500 border-gray-300">
                                    <div class="flex items-center gap-1 text-sm text-gray-600 group-hover:text-gray-900">
                                        <svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ $rating }} ও এর উপরে
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Verified Only --}}
                    <div class="mb-5">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="verified" value="1" 
                                   onchange="this.form.submit()"
                                   @checked(request('verified') == '1')
                                   class="rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 transition-colors">শুধুমাত্র যাচাইকৃত কর্মী</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full btn btn-primary py-2.5 mt-2 lg:hidden">ফিল্টার প্রয়োগ করুন</button>
                </form>
            </div>
        </div>

        {{-- Results Area --}}
        <div class="w-full lg:w-3/4">
            
            {{-- Top Bar --}}
            <div class="flex flex-col sm:flex-row items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 gap-4">
                <p class="text-gray-600 font-medium">
                    <span class="text-gray-900 font-bold">{{ $providers->total() }}</span> জন কর্মী পাওয়া গেছে
                </p>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-sm text-gray-500 whitespace-nowrap">সর্ট করুন:</span>
                    <form action="{{ route('search') }}" method="GET" class="w-full sm:w-auto" id="sortForm">
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" class="input py-2 text-sm w-full" onchange="document.getElementById('sortForm').submit()">
                            <option value="rating" @selected(request('sort') == 'rating')>সর্বোচ্চ রেটিং</option>
                            <option value="jobs" @selected(request('sort') == 'jobs')>সবচেয়ে বেশি কাজ</option>
                            <option value="newest" @selected(request('sort') == 'newest')>নতুন যুক্ত</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Grid --}}
            @if($providers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($providers as $provider)
                        @include('components.public.provider-card', ['provider' => $provider])
                    @endforeach
                </div>
                
                <div class="mt-8">
                    {{ $providers->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">কোনো কর্মী পাওয়া যায়নি</h3>
                    <p class="text-gray-500 mb-6">আপনার ফিল্টার অনুযায়ী কোনো কর্মী খুঁজে পাওয়া যায়নি। অন্য ফিল্টার চেষ্টা করুন।</p>
                    <a href="{{ route('search') }}" class="btn btn-outline">ফিল্টার রিসেট করুন</a>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
