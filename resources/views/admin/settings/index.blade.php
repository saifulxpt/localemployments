@extends('layouts.admin')

@section('title', 'সিস্টেম সেটিংস')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">সিস্টেম সেটিংস</h1>
            <p class="text-sm text-gray-500">প্লাটফর্মের বিভিন্ন ফি, রেট এবং সাধারণ কনফিগারেশন।</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            @forelse($settings as $group => $groupSettings)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
                        <h2 class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $group) }} Settings</h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        @foreach($groupSettings as $setting)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-4 items-center">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-semibold text-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>
                                    @if($setting->description)
                                        <p class="text-xs text-gray-500 mt-1">{{ $setting->description }}</p>
                                    @endif
                                </div>
                                <div class="md:col-span-2">
                                    @if($setting->type === 'boolean')
                                        <div class="flex items-center gap-4 mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="{{ $setting->key }}" value="1" class="text-blue-600 focus:ring-blue-500" {{ $setting->value == '1' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Yes (চালু)</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="{{ $setting->key }}" value="0" class="text-blue-600 focus:ring-blue-500" {{ $setting->value == '0' ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">No (বন্ধ)</span>
                                            </label>
                                        </div>
                                    @elseif($setting->type === 'textarea')
                                        <textarea name="{{ $setting->key }}" rows="3" class="input w-full text-sm">{{ $setting->value }}</textarea>
                                    @elseif($setting->type === 'file')
                                        <div class="flex items-center gap-4">
                                            @if($setting->value)
                                                <div class="w-16 h-16 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                                                    <img src="{{ asset($setting->value) }}" class="w-full h-full object-cover">
                                                </div>
                                            @endif
                                            <input type="file" name="{{ $setting->key }}" class="input w-full md:w-2/3 p-1.5" accept="image/*">
                                        </div>
                                    @else
                                        <input type="{{ $setting->type === 'integer' || $setting->type === 'float' ? 'number' : 'text' }}" 
                                               step="{{ $setting->type === 'float' ? '0.01' : '1' }}"
                                               name="{{ $setting->key }}" 
                                               class="input w-full md:w-2/3" 
                                               value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            
                            @if($loop->last && $group === 'general')
                                <div class="border-t border-gray-100 pt-6 mt-6">
                                    <h3 class="text-sm font-bold text-gray-900 mb-4">সোশ্যাল মিডিয়া</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label block text-sm font-semibold text-gray-700 mb-1">Facebook URL</label>
                                            <input type="url" name="social_facebook" value="{{ setting('social_facebook') }}" class="input w-full" placeholder="https://facebook.com/yourpage">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label class="form-label block text-sm font-semibold text-gray-700 mb-1">YouTube URL</label>
                                            <input type="url" name="social_youtube" value="{{ setting('social_youtube') }}" class="input w-full" placeholder="https://youtube.com/@yourchannel">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!$loop->last)
                                <hr class="border-gray-100">
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center text-gray-500">
                    কোনো সেটিংস পাওয়া যায়নি। ডেটাবেস সিডার রান করুন।
                </div>
            @endforelse
        </div>

        @if($settings->count() > 0)
            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 px-8 py-3 shadow-lg shadow-blue-200">
                    সেটিংস সেভ করুন
                </button>
            </div>
        @endif
    </form>

</div>
@endsection
