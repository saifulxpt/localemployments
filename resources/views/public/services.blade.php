@extends('layouts.public')

@section('title', 'All Services')
@section('meta_description', 'Explore all service categories and subcategories.')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">All Services & Categories</h1>
            <p class="text-gray-500 text-lg">আপনার যা প্রয়োজন, সবই পাবেন এখানে। ক্যাটাগরি বেছে নিন এবং সরাসরি দক্ষ কর্মীদের খুঁজে বের করুন।</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $cat)
                <a href="{{ route('services.show', $cat->slug) }}" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-200 hover:-translate-y-1 transition-all group">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 group-hover:bg-primary-100 transition-colors">
                            {!! category_icon($cat->icon, 'w-8 h-8') !!}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 group-hover:text-primary-700 transition-colors">{{ $cat->name }}</h2>
                            <p class="text-sm text-gray-400 mt-1">{{ $cat->active_subcategories_count }} Subcategories</p>
                        </div>
                    </div>
                    @if($cat->description)
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $cat->description }}</p>
                    @endif
                    <div class="text-primary-600 font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                        Explore Category <span>→</span>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</div>
@endsection
