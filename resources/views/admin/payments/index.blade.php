@extends('layouts.admin')

@section('title','Payments')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Payments</h1>
        <p class="text-gray-600 mt-1">Manage and track all payment transactions</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Payment or Transaction ID..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Method Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Method</label>
                    <select name="method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="">All Methods</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $method)) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Apply Filters -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Clear
                    </a>
                </div>
            </div>

            <!-- Amount & Date Range (Optional) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Min Amount</label>
                    <input type="number" name="amount_from" value="{{ request('amount_from') }}" placeholder="$0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Amount</label>
                    <input type="number" name="amount_to" value="{{ request('amount_to') }}" placeholder="$10000" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                </div>
            </div>

            <!-- Sort -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <option value="created_desc" {{ request('sort') === 'created_desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="created_asc" {{ request('sort') === 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                        <option value="amount_desc" {{ request('sort') === 'amount_desc' ? 'selected' : '' }}>Highest Amount</option>
                        <option value="amount_asc" {{ request('sort') === 'amount_asc' ? 'selected' : '' }}>Lowest Amount</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Results</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $payments->total() }}</p>
                </div>
                <i class="fas fa-money-bill text-3xl text-orange-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Payment ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payments as $p)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ substr($p->payment_id, 0, 8) }}...</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ substr($p->booking_id, 0, 8) }}...</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($p->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $p->payment_method ?? 'N/A')) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $p->status === 'success' ? 'bg-green-100 text-green-800' : ($p->status === 'failed' ? 'bg-red-100 text-red-800' : ($p->status === 'refunded' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $p->created_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <a href="{{ route('admin.payments.show', $p->payment_id) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                            <i class="fas fa-eye"></i><span>View</span>
                        </a>
                        <form action="{{ route('admin.payments.destroy', $p->payment_id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center space-x-1 px-3 py-1 rounded text-red-600 hover:text-red-900 hover:bg-red-50 transition">
                                <i class="fas fa-trash"></i><span>Delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-600">
                        <i class="fas fa-inbox text-4xl mb-4 opacity-50"></i>
                        <p>No payments found</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection
