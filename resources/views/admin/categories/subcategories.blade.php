@extends('layouts.admin')

@section('title', $category->name . ' - সাব-ক্যাটাগরি')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition-colors">ক্যাটাগরি ম্যানেজমেন্ট</a>
                <span class="text-gray-400">/</span>
                <span class="text-sm font-semibold text-gray-900">{{ $category->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">সাব-ক্যাটাগরি তালিকা</h1>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.categories.subcategories.create', $category) }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                নতুন সাব-ক্যাটাগরি
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50/50 border-b border-gray-200 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                {!! category_icon($category->icon, 'w-6 h-6') !!}
            </div>
            <div>
                <h3 class="font-bold text-gray-900">{{ $category->name }}</h3>
                <p class="text-xs text-gray-500">মোট {{ $subcategories->total() }} টি সাব-ক্যাটাগরি</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">সাব-ক্যাটাগরির নাম</th>
                        <th class="px-6 py-4 text-center">সর্ট অর্ডার</th>
                        <th class="px-6 py-4 text-center">স্ট্যাটাস</th>
                        <th class="px-6 py-4 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subcategories as $subcategory)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $subcategory->name }}</div>
                                @if($subcategory->description)
                                    <div class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $subcategory->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-mono text-gray-500 bg-gray-50 px-2 py-1 rounded inline-block">{{ $subcategory->sort_order }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($subcategory->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="এডিট">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" class="inline-block" onsubmit="return confirm('আপনি কি নিশ্চিত? এটি মুছে ফেললে এর সাথে যুক্ত কাজগুলোর সমস্যা হতে পারে।')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="ডিলিট">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                কোনো সাব-ক্যাটাগরি পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($subcategories->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $subcategories->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
