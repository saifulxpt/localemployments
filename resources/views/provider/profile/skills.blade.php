@extends('layouts.provider')

@section('title', 'দক্ষতাসমূহ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Tabs/Links --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 flex overflow-x-auto gap-2">
        <a href="{{ route('provider.profile.edit') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('provider.profile.edit') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">সাধারণ তথ্য</a>
        <a href="{{ route('provider.skills.manage') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('provider.skills.manage') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">দক্ষতাসমূহ</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2">আপনার স্কিল ও সেবাসমূহ নির্বাচন করুন</h2>
            <p class="text-gray-500 text-sm">আপনি যে কাজগুলো করতে পারেন সেগুলো নির্বাচন করুন। এর মাধ্যমে কাস্টমাররা আপনাকে সহজে খুঁজে পাবে। (সর্বোচ্চ ১০টি)</p>
        </div>

        @php
            $selectedSkills = $user->providerSkills->pluck('subcategory_id')->toArray();
        @endphp

        <form action="{{ route('provider.skills.update') }}" method="POST" x-data="{
            selected: {{ json_encode($selectedSkills) }},
            max: 10,
            toggle(id) {
                const index = this.selected.indexOf(id);
                if (index > -1) {
                    this.selected.splice(index, 1);
                } else {
                    if (this.selected.length < this.max) {
                        this.selected.push(id);
                    } else {
                        alert('আপনি সর্বোচ্চ ১০টি স্কিল নির্বাচন করতে পারবেন।');
                    }
                }
            }
        }">
            @csrf

            <div class="mb-4 flex items-center justify-between text-sm">
                <span class="font-semibold text-gray-700">নির্বাচিত স্কিল:</span>
                <span class="bg-primary-100 text-primary-700 font-bold px-3 py-1 rounded-full" x-text="selected.length + ' / ' + max"></span>
            </div>

            <div class="space-y-8 mb-8">
                @foreach($categories as $category)
                    @if($category->activeSubcategories->count() > 0)
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2 pb-2 border-b border-gray-100">
                                <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-xl">{{ $category->icon ? '⚡' : '🛠️' }}</span>
                                {{ $category->name }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($category->activeSubcategories as $sub)
                                    <label class="relative flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all hover:bg-gray-50"
                                           :class="selected.includes({{ $sub->id }}) ? 'border-primary-500 bg-primary-50/30' : 'border-gray-200'">
                                        
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="subcategory_ids[]" value="{{ $sub->id }}"
                                                   class="hidden"
                                                   :checked="selected.includes({{ $sub->id }})"
                                                   @change="toggle({{ $sub->id }})">
                                            
                                            <div class="w-5 h-5 rounded border flex items-center justify-center transition-colors"
                                                 :class="selected.includes({{ $sub->id }}) ? 'bg-primary-600 border-primary-600' : 'border-gray-300 bg-white'">
                                                <svg x-show="selected.includes({{ $sub->id }})" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <span class="block text-sm font-medium transition-colors"
                                                  :class="selected.includes({{ $sub->id }}) ? 'text-primary-900' : 'text-gray-700'">
                                                {{ $sub->name }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            @error('subcategory_ids')
                <div class="text-red-500 text-sm mb-4 bg-red-50 p-3 rounded-lg">{{ $message }}</div>
            @enderror

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="btn btn-primary px-8" :disabled="selected.length === 0">স্কিল সেভ করুন</button>
            </div>
        </form>

    </div>
</div>
@endsection
