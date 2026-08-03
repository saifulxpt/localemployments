@props(['provider'])

<div class="bg-white rounded-[1.5rem] hover:shadow-2xl transition-all duration-300 overflow-hidden group border border-gray-100/50 flex flex-col h-full transform hover:-translate-y-1 relative">
    {{-- Cover Background --}}
    <div class="h-20 bg-gradient-to-r from-primary-600 to-emerald-500 relative">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIi8+PC9zdmc+')] opacity-30"></div>
    </div>
    
    <div class="p-6 pt-0 flex flex-col flex-1 relative">
        {{-- Avatar overlapping cover --}}
        <div class="flex justify-between items-end mb-4 -mt-8 relative z-10">
            <div class="relative">
                <img class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white shadow-lg bg-white" src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}">
                @if($provider->providerProfile?->is_verified)
                    {{-- Verified badge overlay --}}
                    <span class="absolute -bottom-2 -right-2 bg-emerald-500 rounded-full p-1 border-2 border-white shadow-md text-white" title="যাচাইকৃত">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                @endif
            </div>
            
            {{-- Floating Rating Badge --}}
            <div class="bg-white px-3 py-1.5 rounded-full shadow-sm border border-gray-100 flex items-center gap-1.5 mb-2">
                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="font-bold text-gray-800">{{ number_format($provider->providerProfile?->rating_avg ?? 0, 1) }}</span>
                <span class="text-xs text-gray-400">({{ $provider->providerProfile?->total_reviews ?? 0 }})</span>
            </div>
        </div>

        {{-- Name & Location --}}
        <div class="mb-5">
            <h3 class="font-extrabold text-xl text-gray-900 group-hover:text-primary-600 transition-colors truncate">
                {{ $provider->name }}
            </h3>
            <div class="flex items-center text-sm text-gray-500 mt-1.5 gap-1">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="truncate">
                    {{ $provider->district?->bn_name ?? 'লোকেশন দেওয়া নেই' }} 
                    @if($provider->area)
                        · {{ $provider->area->bn_name }}
                    @endif
                </span>
            </div>
        </div>
        
        {{-- Stats Grid (Jobs & Experience) --}}
        <div class="grid grid-cols-2 gap-3 mb-6 bg-gray-50/80 p-3 rounded-2xl border border-gray-100">
            <div class="text-center p-2">
                <div class="text-xl font-black text-primary-700">{{ $provider->providerProfile?->total_jobs ?? 0 }}</div>
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-1">কাজ সম্পন্ন</div>
            </div>
            <div class="text-center p-2 border-l border-gray-200/60">
                <div class="text-xl font-black text-emerald-600">{{ $provider->providerProfile?->experience_years ?? 0 }} <span class="text-sm font-bold">বছর</span></div>
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-1">অভিজ্ঞতা</div>
            </div>
        </div>
        
        {{-- Skills tags --}}
        <div class="flex flex-wrap gap-2 mb-6 flex-1 items-start content-start">
            @if($provider->providerSkills && $provider->providerSkills->isNotEmpty())
                @foreach($provider->providerSkills->take(3) as $skill)
                    <span class="px-3 py-1.5 bg-white border shadow-sm border-gray-200 hover:border-primary-300 text-gray-700 hover:text-primary-700 hover:bg-primary-50 transition-colors text-[13px] font-bold rounded-full truncate max-w-[140px]">
                        {{ $skill->subcategory->name }}
                    </span>
                @endforeach
                @if($provider->providerSkills->count() > 3)
                    <span class="px-3 py-1.5 bg-gray-100 text-gray-500 text-[13px] font-bold rounded-full">
                        +{{ $provider->providerSkills->count() - 3 }}
                    </span>
                @endif
            @else
                <span class="text-sm text-gray-400 italic bg-gray-50 px-4 py-2 rounded-xl">স্কিল যোগ করা হয়নি</span>
            @endif
        </div>
        
        {{-- CTA --}}
        <a href="{{ route('providers.show', $provider->id) }}" class="block text-center py-3.5 bg-gray-900 text-white text-sm font-bold rounded-[1rem] group-hover:bg-primary-600 group-hover:shadow-lg group-hover:shadow-primary-500/30 transition-all mt-auto active:scale-95">
            প্রোফাইল দেখুন
        </a>
    </div>
</div>
