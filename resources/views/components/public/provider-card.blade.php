@props(['provider'])

<div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300 overflow-hidden group border border-gray-100 flex flex-col h-full">
    {{-- Color accent bar at top --}}
    <div class="h-1.5 bg-gradient-to-r from-primary-600 to-accent-400"></div>
    
    <div class="p-5 flex flex-col flex-1">
        {{-- Avatar + Name row --}}
        <div class="flex items-start gap-3 mb-3">
            <div class="relative flex-shrink-0">
                <img class="w-14 h-14 rounded-xl object-cover ring-2 ring-gray-50" src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}">
                @if($provider->providerProfile?->is_verified)
                    {{-- Verified badge overlay --}}
                    <span class="absolute -bottom-1 -right-1 bg-accent-500 rounded-full p-0.5 border-2 border-white shadow-sm" title="যাচাইকৃত">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 group-hover:text-primary-700 transition-colors truncate">
                    {{ $provider->name }}
                </h3>
                <p class="text-xs text-gray-500 truncate mt-0.5">
                    {{ $provider->district?->bn_name ?? 'লোকেশন দেওয়া নেই' }} 
                    @if($provider->area)
                        · {{ $provider->area->bn_name }}
                    @endif
                </p>
            </div>
        </div>
        
        {{-- Rating + Jobs --}}
        <div class="flex items-center gap-3 text-sm mb-4 bg-gray-50 rounded-lg p-2">
            <div class="flex items-center gap-1 text-accent-600 font-bold">
                <svg class="w-4 h-4 fill-current text-accent-500" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ number_format($provider->providerProfile?->rating_avg ?? 0, 1) }}
                <span class="text-gray-400 font-normal">({{ $provider->providerProfile?->total_reviews ?? 0 }})</span>
            </div>
            <div class="w-px h-4 bg-gray-300"></div>
            <div class="text-gray-600 font-medium">
                {{ $provider->providerProfile?->total_jobs ?? 0 }} <span class="text-gray-400 font-normal text-xs">কাজ সম্পন্ন</span>
            </div>
        </div>
        
        {{-- Skills tags --}}
        <div class="flex flex-wrap gap-1.5 mb-4 flex-1">
            @if($provider->providerSkills && $provider->providerSkills->isNotEmpty())
                @foreach($provider->providerSkills->take(3) as $skill)
                    <span class="px-2 py-1 bg-primary-50 border border-primary-100 text-primary-800 text-xs font-medium rounded-lg truncate max-w-[120px]">
                        {{ $skill->subcategory->name }}
                    </span>
                @endforeach
                @if($provider->providerSkills->count() > 3)
                    <span class="px-2 py-1 bg-gray-100 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg">
                        +{{ $provider->providerSkills->count() - 3 }}
                    </span>
                @endif
            @else
                <span class="text-xs text-gray-400 italic">স্কিল যোগ করা হয়নি</span>
            @endif
        </div>
        
        {{-- CTA --}}
        <a href="{{ route('providers.show', $provider->id) }}" class="block text-center py-2.5 bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl group-hover:bg-primary-600 group-hover:text-white transition-colors mt-auto border border-gray-100 group-hover:border-primary-600">
            প্রোফাইল দেখুন
        </a>
    </div>
</div>
