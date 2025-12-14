@extends('layouts.admin')

@section('title','Users')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Users</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Manage customers, staff, and admin accounts</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 sm:p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="xs:col-span-2 md:col-span-1">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="hidden md:block">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="created_desc" {{ request('sort') === 'created_desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="created_asc" {{ request('sort') === 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="email_asc" {{ request('sort') === 'email_asc' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                <!-- Apply Filters -->
                <div class="xs:col-span-2 md:col-span-1 flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-3 sm:px-4 text-sm rounded-lg transition whitespace-nowrap">
                        <i class="fas fa-search mr-1 sm:mr-2"></i><span class="hidden xs:inline">Search</span>
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-3 sm:px-4 text-sm rounded-lg transition whitespace-nowrap">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm">Total Users</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                </div>
                <i class="fas fa-users text-2xl sm:text-3xl text-orange-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Desktop Table (hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Name</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Email</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Phone</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Role</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Joined</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $u)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900 font-medium">{{ $u->name }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $u->email }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $u->phone ?? '-' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $u->role === 'admin' ? 'bg-red-100 text-red-800' : ($u->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $u->created_at?->format('M d, Y') ?? '-' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm space-x-1">
                            <a href="{{ route('admin.users.show', $u->user_id) }}" class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                                <i class="fas fa-eye"></i><span>View</span>
                            </a>
                            <a href="{{ route('admin.users.edit', $u->user_id) }}" class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                                <i class="fas fa-edit"></i><span>Edit</span>
                            </a>
                            <form action="{{ route('admin.users.destroy', $u->user_id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs text-red-600 hover:text-red-900 hover:bg-red-50 transition">
                                    <i class="fas fa-trash"></i><span>Delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 sm:px-6 py-12 text-center text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4 opacity-50"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards (shown on mobile, hidden on desktop) -->
    <div class="lg:hidden space-y-4">
        @forelse($users as $u)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $u->name }}</h3>
                        <p class="text-xs text-gray-600 truncate">{{ $u->email }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $u->role === 'admin' ? 'bg-red-100 text-red-800' : ($u->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($u->role) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                    <div>
                        <p class="text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $u->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Joined</p>
                        <p class="font-medium text-gray-900">{{ $u->created_at?->format('M d, Y') ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.show', $u->user_id) }}" class="flex-1 text-center px-2 py-2 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('admin.users.edit', $u->user_id) }}" class="flex-1 text-center px-2 py-2 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $u->user_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-2 py-2 text-xs font-medium text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600">No users found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6 text-sm">
        {{ $users->links() }}
    </div>
</div>
@endsection
