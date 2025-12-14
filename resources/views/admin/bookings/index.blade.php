@extends('layouts.admin')

@section('title','Bookings')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Bookings</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Manage all appointments and bookings</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 sm:p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="xs:col-span-2 md:col-span-1">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ID or customer..." class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="hidden md:block">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                        <option value="customer" {{ request('sort') === 'customer' ? 'selected' : '' }}>Customer Name</option>
                    </select>
                </div>

                <!-- Apply Filters -->
                <div class="xs:col-span-2 md:col-span-1 flex items-end">
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-3 sm:px-4 text-sm rounded-lg transition">
                        <i class="fas fa-search mr-1 sm:mr-2"></i><span class="hidden xs:inline">Search</span>
                    </button>
                </div>
            </div>

            <!-- Date Range (Optional) -->
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.bookings.index') }}" class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-3 sm:px-4 text-sm rounded-lg transition">
                        <i class="fas fa-times mr-1"></i><span class="hidden xs:inline">Clear</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm">Total Results</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $bookings->total() }}</p>
                </div>
                <i class="fas fa-bookmark text-2xl sm:text-3xl text-orange-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Desktop Table (hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Booking ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Customer</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Staff</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Service</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Date & Time</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($bookings as $b)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900 font-medium">{{ substr($b->booking_id, 0, 8) }}...</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ optional($b->customer)->name ?? 'N/A' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ optional($b->staff)->name ?? 'N/A' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ optional($b->service)->name ?? 'N/A' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-600">{{ $b->date ?? 'N/A' }} {{ $b->time ?? '' }}</td>
                        <td class="px-4 sm:px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $b->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($b->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($b->status) }}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm space-x-1">
                            <a href="{{ route('admin.bookings.show', $b->booking_id) }}" class="inline-flex items-center space-x-1 px-2 py-1 rounded text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                                <i class="fas fa-eye"></i><span>View</span>
                            </a>
                            <form action="{{ route('admin.bookings.destroy', $b->booking_id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                        <td colspan="7" class="px-4 sm:px-6 py-12 text-center text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-4 opacity-50"></i>
                            <p>No bookings found</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards (shown on mobile, hidden on desktop) -->
    <div class="lg:hidden space-y-4">
        @forelse($bookings as $b)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 text-sm truncate">{{ optional($b->service)->name ?? 'Service' }}</h3>
                        <p class="text-xs text-gray-600 truncate">ID: {{ substr($b->booking_id, 0, 8) }}...</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium flex-shrink-0 {{ $b->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($b->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($b->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                    <div>
                        <p class="text-gray-600">Customer</p>
                        <p class="font-medium text-gray-900">{{ optional($b->customer)->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Staff</p>
                        <p class="font-medium text-gray-900">{{ optional($b->staff)->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Date</p>
                        <p class="font-medium text-gray-900">{{ $b->date ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Time</p>
                        <p class="font-medium text-gray-900">{{ $b->time ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.bookings.show', $b->booking_id) }}" class="flex-1 text-center px-2 py-2 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <form action="{{ route('admin.bookings.destroy', $b->booking_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?')">
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
                <p class="text-gray-600">No bookings found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6 text-sm">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
