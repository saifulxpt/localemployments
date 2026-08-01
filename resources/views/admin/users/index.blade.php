@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-sm text-gray-500">List of all platform users and details.</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Search (Name or Phone)</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" class="input pl-9" placeholder="Search...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Role</label>
                <select name="role" class="input">
                    <option value="">All Roles</option>
                    <option value="seeker" @selected(request('role') === 'seeker')>Seeker</option>
                    <option value="provider" @selected(request('role') === 'provider')>Provider</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                </select>
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" class="input">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="suspended" @selected(request('status') === 'suspended')>Suspended</option>
                </select>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="btn bg-blue-600 text-white hover:bg-blue-700 w-full sm:w-auto px-6">Filter</button>
                @if(request()->anyFilled(['q', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline border-gray-200 hover:bg-gray-50 px-4" title="Reset">
                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Users List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Joined Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                    <div>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="font-bold text-gray-900 hover:text-blue-600">{{ $user->name }}</a>
                                        <div class="text-xs text-gray-500">আইডি: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $user->phone }}</div>
                                @if($user->email)
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $user->role === 'provider' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : ($user->role === 'admin' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-700 border border-gray-200') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900">{{ $user->created_at->format('d M, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $user->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline border-gray-200 btn-sm hover:border-blue-300 hover:text-blue-600">Details</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="text-gray-500">কোনো ইউজার পাওয়া যায়নি।</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
