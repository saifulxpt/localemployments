@extends('layouts.admin')

@section('title', 'Locations Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Locations (Districts & Areas)</h1>
            <p class="text-sm text-gray-500">List of serviceable districts and areas across the platform.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.districts.create') }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700">
                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add District
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">জেলাসমূহ</h3>
            <span class="text-sm text-gray-500">মোট {{ $districts->total() }} টি জেলা</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">District Name (English)</th>
                        <th class="px-6 py-4">District Name (Bangla)</th>
                        <th class="px-6 py-4 text-center">Areas Count</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($districts as $district)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $district->name }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                {{ $district->bn_name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.locations.areas.index', $district) }}" class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                    {{ $district->areas_count }} টি
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($district->is_active)
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
                                    <a href="{{ route('admin.districts.edit', $district) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="এডিট">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.districts.destroy', $district) }}" method="POST" class="inline-block" onsubmit="return confirm('আপনি কি নিশ্চিত? এটি নিষ্ক্রিয় করতে চান?')">
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
                                কোনো জেলা পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($districts->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $districts->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
