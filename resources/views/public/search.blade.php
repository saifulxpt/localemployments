@extends('layouts.public')

@section('title', 'কর্মী খুঁজুন')
@section('meta_description', 'আপনার এলাকার সেরা ও যাচাইকৃত সার্ভিস প্রোভাইডারদের খুঁজুন।')

@section('content')

{{-- ─────────────────────────────────────────── --}}
{{-- Header --}}
{{-- ─────────────────────────────────────────── --}}
<div class="bg-gradient-to-br from-primary-50 via-white to-primary-100 pt-16 pb-24 md:pt-20 md:pb-32 relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNlNTViNzgiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-50"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 text-gray-900 tracking-tight">আপনার কাঙ্ক্ষিত <span class="text-primary-600">কর্মী খুঁজুন</span></h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg md:text-xl">স্কিল, রেটিং এবং আপনার লোকেশন অনুযায়ী সেরা প্রোভাইডার বেছে নিন।</p>
    </div>
</div>

{{-- ─────────────────────────────────────────── --}}
{{-- Main Content --}}
{{-- ─────────────────────────────────────────── --}}
<div class="container mx-auto px-4 -mt-12 md:-mt-16 relative z-20 pb-12" x-data="{ mobileFiltersOpen: false }">
    <div class="flex flex-col lg:flex-row gap-6 md:gap-8">
        
        {{-- Mobile Filter Toggle Button --}}
        <div class="lg:hidden flex items-center justify-between bg-white p-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-2">
            <div class="text-sm font-bold text-gray-900"><span class="text-primary-600">{{ $providers->total() }}</span> জন কর্মী পাওয়া গেছে</div>
            <button @click="mobileFiltersOpen = true" class="flex items-center gap-2 bg-primary-50 text-primary-700 px-4 py-2 rounded-xl text-sm font-bold active:scale-95 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                ফিল্টার
            </button>
        </div>

        {{-- ─────────────────────────────────────────── --}}
        {{-- Sidebar Filters --}}
        {{-- ─────────────────────────────────────────── --}}
        <div class="w-full lg:w-1/3 xl:w-1/4 fixed inset-0 z-50 lg:static lg:block lg:z-auto"
             x-show="mobileFiltersOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300 lg:transition-none"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300 lg:transition-none"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
             
            {{-- Backdrop (Mobile) --}}
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm lg:hidden" @click="mobileFiltersOpen = false"></div>

            {{-- Filter Panel --}}
            <div class="absolute inset-y-0 right-0 w-full max-w-sm bg-white shadow-2xl lg:shadow-[0_8px_30px_rgb(0,0,0,0.04)] lg:rounded-3xl lg:border lg:border-gray-100 lg:static transform lg:transform-none transition-transform duration-300 ease-in-out flex flex-col h-full lg:h-auto overflow-hidden"
                 x-show="mobileFiltersOpen"
                 x-transition:enter="translate-x-full lg:translate-x-0"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="translate-x-full lg:translate-x-0"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                {{-- Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-white">
                    <h2 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        ফিল্টার
                    </h2>
                    <div class="flex items-center gap-3">
                        @if(request()->anyFilled(['category', 'district', 'area', 'verified', 'min_rating', 'q']))
                            <a href="{{ route('search') }}" class="text-sm font-bold text-red-500 hover:text-red-700 transition-colors bg-red-50 px-3 py-1.5 rounded-lg">রিসেট</a>
                        @endif
                        <button @click="mobileFiltersOpen = false" class="lg:hidden p-2 text-gray-400 hover:text-gray-600 bg-gray-50 rounded-full">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Form Body --}}
                <div class="flex-1 overflow-y-auto p-6 bg-white custom-scrollbar">
                    <form action="{{ route('search') }}" method="GET" id="filterForm"
                          x-data="{
                              districtId: '{{ request('district') }}',
                              areas: [],
                              loadAreas(id) {
                                  if(!id) { this.areas = []; return; }
                                  fetch('/ajax/areas/' + id)
                                      .then(r => r.json())
                                      .then(data => { this.areas = data; });
                              }
                          }"
                          x-init="if(districtId) { loadAreas(districtId); setTimeout(() => { $refs.areaSelect.value = '{{ request('area') }}' }, 300); }">
                        
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        {{-- Category Custom Dropdown --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">ক্যাটাগরি</label>
                            <div class="relative" x-data="{ open: false, selected: '{{ request('category') }}' }">
                                <select name="category" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-primary-500 font-medium transition-colors cursor-pointer" onchange="this.form.submit()">
                                    <option value="">সব ক্যাটাগরি</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- District Custom Dropdown --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">জেলা</label>
                            <div class="relative">
                                <select name="district" x-model="districtId" @change="loadAreas(districtId); $nextTick(() => { $refs.areaSelect.value = ''; $el.form.submit(); })"
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-primary-500 font-medium transition-colors cursor-pointer">
                                    <option value="">সব জেলা</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}">{{ $d->bn_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Area Custom Dropdown --}}
                        <div class="mb-6" x-show="areas.length > 0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;">
                            <label class="block text-sm font-bold text-gray-700 mb-2">উপজেলা/থানা</label>
                            <div class="relative">
                                <select name="area" x-ref="areaSelect" onchange="this.form.submit()"
                                        class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3.5 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-primary-500 font-medium transition-colors cursor-pointer">
                                    <option value="">সব এলাকা</option>
                                    <template x-for="a in areas" :key="a.id">
                                        <option :value="a.id" x-text="a.bn_name"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Min Rating (Premium Radio Buttons) --}}
                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-3">ন্যূনতম রেটিং</label>
                            <div class="flex flex-col gap-2">
                                @foreach([4, 3, 2, 1] as $rating)
                                    <label class="relative flex items-center p-3 rounded-xl border cursor-pointer transition-all hover:bg-gray-50"
                                           :class="'{{ request('min_rating') }}' == '{{ $rating }}' ? 'border-primary-500 bg-primary-50' : 'border-gray-200'">
                                        <input type="radio" name="min_rating" value="{{ $rating }}" 
                                               onchange="this.form.submit()"
                                               @checked(request('min_rating') == $rating)
                                               class="hidden">
                                        <div class="flex items-center gap-1.5 w-full">
                                            <div class="flex items-center text-accent-500">
                                                @for($i=0; $i<$rating; $i++)
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                @endfor
                                            </div>
                                            <span class="text-sm font-semibold text-gray-700 ml-1">ও এর উপরে</span>
                                            
                                            {{-- Custom Radio Checkmark --}}
                                            <div class="ml-auto w-5 h-5 rounded-full border flex items-center justify-center transition-colors"
                                                 :class="'{{ request('min_rating') }}' == '{{ $rating }}' ? 'border-primary-500 bg-primary-500' : 'border-gray-300'">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="'{{ request('min_rating') }}' == '{{ $rating }}'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Verified Only Toggle Switch --}}
                        <div class="mb-6">
                            <label class="flex items-center justify-between cursor-pointer p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-800">যাচাইকৃত কর্মী</span>
                                </div>
                                <div class="relative">
                                    <input type="checkbox" name="verified" value="1" class="sr-only" onchange="this.form.submit()" @checked(request('verified') == '1')>
                                    <div class="block w-10 h-6 rounded-full transition-colors {{ request('verified') == '1' ? 'bg-primary-500' : 'bg-gray-200' }}"></div>
                                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform {{ request('verified') == '1' ? 'transform translate-x-4' : '' }}"></div>
                                </div>
                            </label>
                        </div>
                    </form>
                </div>
                
                {{-- Footer (Mobile Only) --}}
                <div class="p-4 border-t border-gray-100 lg:hidden">
                    <button @click="document.getElementById('filterForm').submit()" class="w-full bg-primary-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-primary-700 transition-colors active:scale-95">
                        ফলাফল দেখুন
                    </button>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────── --}}
        {{-- Results Area --}}
        {{-- ─────────────────────────────────────────── --}}
        <div class="w-full lg:w-2/3 xl:w-3/4">
            
            {{-- Top Bar (Desktop) --}}
            <div class="hidden lg:flex items-center justify-between bg-white p-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-6 border border-gray-50">
                <p class="text-gray-500 font-medium">
                    <span class="text-gray-900 font-bold text-lg">{{ $providers->total() }}</span> জন কর্মী পাওয়া গেছে
                </p>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">সর্ট করুন:</span>
                    <div class="relative">
                        <select name="sort" form="filterForm" class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-2 pl-4 pr-8 rounded-lg font-bold text-sm focus:outline-none focus:border-primary-500 transition-colors cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                            <option value="rating" @selected(request('sort') == 'rating')>সর্বোচ্চ রেটিং</option>
                            <option value="jobs" @selected(request('sort') == 'jobs')>সবচেয়ে বেশি কাজ</option>
                            <option value="newest" @selected(request('sort') == 'newest')>নতুন যুক্ত</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid --}}
            @if($providers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($providers as $provider)
                        @include('components.public.provider-card', ['provider' => $provider])
                    @endforeach
                </div>
                
                <div class="mt-10">
                    {{ $providers->links() }}
                </div>
            @else
                {{-- Premium Empty State --}}
                <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="absolute inset-0 bg-primary-50 rounded-full animate-ping opacity-75"></div>
                        <div class="relative w-full h-full bg-primary-100 rounded-full flex items-center justify-center text-primary-500">
                            <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">কোনো কর্মী পাওয়া যায়নি</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-8 text-lg">আপনার ফিল্টারের সাথে মিলে যায় এমন কোনো প্রোভাইডার এই মুহূর্তে নেই।</p>
                    <a href="{{ route('search') }}" class="inline-flex items-center justify-center bg-gray-900 text-white font-bold py-3 px-8 rounded-xl hover:bg-gray-800 transition-colors shadow-lg active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        ফিল্টার রিসেট করুন
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Add styles for custom scrollbar --}}
@push('head')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
</style>
@endpush

@endsection
