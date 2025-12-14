@extends('layouts.admin')

@section('content')
<div>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Admin Control Center</h1>
        <p class="text-gray-600 mt-2">Complete system oversight and management dashboard</p>
    </div>

    <!-- Primary KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                    <p class="text-xs text-green-600 mt-1">From all payments</p>
                </div>
                <i class="fas fa-money-bill-wave text-4xl text-green-300 opacity-50"></i>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-sm font-medium">Pending Payments</p>
                    <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $stats['pending_payments'] ?? 0 }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Awaiting completion</p>
                </div>
                <i class="fas fa-hourglass-half text-4xl text-yellow-300 opacity-50"></i>
            </div>
        </div>

        <!-- Confirmed Bookings -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 text-sm font-medium">Confirmed Bookings</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
                    <p class="text-xs text-blue-600 mt-1">Active appointments</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-blue-300 opacity-50"></i>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-sm border border-purple-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-700 text-sm font-medium">System Health</p>
                    <p class="text-3xl font-bold text-purple-900 mt-2">Optimal</p>
                    <p class="text-xs text-purple-600 mt-1">All systems operational</p>
                </div>
                <i class="fas fa-heartbeat text-4xl text-purple-300 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Secondary KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Total Users</h3>
                <i class="fas fa-users text-2xl text-orange-500 opacity-20"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
            <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-600">Customers</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['customer_count'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Staff</p>
                    <p class="text-lg font-bold text-gray-900">{{ $stats['staff_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Bookings Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Today's Activity</h3>
                <i class="fas fa-calendar-day text-2xl text-orange-500 opacity-20"></i>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['bookings_today'] ?? 0 }}</p>
            <p class="text-sm text-gray-600 mt-2">Bookings scheduled</p>
            <button class="mt-4 w-full px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                View Today's Schedule
            </button>
        </div>

        <!-- Performance Score -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Performance</h3>
                <i class="fas fa-tachometer-alt text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="relative pt-1">
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-500 rounded-full" style="width: 87%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-2">87% System Efficiency</p>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">View All</a>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse(\App\Models\Booking::latest()->take(5)->get() as $booking)
                    <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $booking->service->name ?? 'Service' }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $booking->customer->name ?? 'N/A' }} → {{ $booking->staff->name ?? 'N/A' }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $booking->date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl opacity-20 mb-2"></i>
                        <p>No recent bookings</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Recent Payments</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">View All</a>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse(\App\Models\Payment::latest()->take(5)->get() as $payment)
                    <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ ucfirst($payment->payment_method ?? 'Unknown') }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl opacity-20 mb-2"></i>
                        <p>No recent payments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Administrative Controls -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- User Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900">User Management</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage all system users</p>
                </div>
                <i class="fas fa-users-cog text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                    <i class="fas fa-list mr-2"></i>View All Users
                </a>
                <button class="w-full px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-user-plus mr-2"></i>Create User
                </button>
            </div>
        </div>

        <!-- Booking Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900">Booking Management</h3>
                    <p class="text-sm text-gray-600 mt-1">Oversee all appointments</p>
                </div>
                <i class="fas fa-calendar-alt text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                    <i class="fas fa-list mr-2"></i>View All Bookings
                </a>
                <button class="w-full px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-calendar-check mr-2"></i>Reschedule
                </button>
            </div>
        </div>

        <!-- Financial Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900">Financial Control</h3>
                    <p class="text-sm text-gray-600 mt-1">Payment and revenue tracking</p>
                </div>
                <i class="fas fa-money-check-alt text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-sm font-medium text-orange-600 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                    <i class="fas fa-receipt mr-2"></i>View Payments
                </a>
                <button class="w-full px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-file-export mr-2"></i>Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- System Alerts & Notifications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">System Alerts</h2>
        <div class="space-y-3">
            <div class="flex items-start p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div class="flex-1">
                    <p class="font-medium text-blue-900">System Update Available</p>
                    <p class="text-sm text-blue-700 mt-1">A new system version is available. Review and install at your convenience.</p>
                </div>
            </div>
            <div class="flex items-start p-4 bg-green-50 border border-green-200 rounded-lg">
                <i class="fas fa-check-circle text-green-600 mt-1 mr-3"></i>
                <div class="flex-1">
                    <p class="font-medium text-green-900">All Systems Normal</p>
                    <p class="text-sm text-green-700 mt-1">Database, API, and all services are operating normally.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition text-center">
            <i class="fas fa-users text-2xl text-orange-500 mb-2"></i>
            <p class="text-sm font-medium text-gray-900">Users</p>
        </a>
        <a href="{{ route('admin.bookings.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition text-center">
            <i class="fas fa-calendar text-2xl text-orange-500 mb-2"></i>
            <p class="text-sm font-medium text-gray-900">Bookings</p>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition text-center">
            <i class="fas fa-money-bill text-2xl text-orange-500 mb-2"></i>
            <p class="text-sm font-medium text-gray-900">Payments</p>
        </a>
        <a href="{{ route('admin.reports') }}" class="p-4 bg-white rounded-lg border border-gray-200 hover:border-orange-500 hover:bg-orange-50 transition text-center">
            <i class="fas fa-chart-bar text-2xl text-orange-500 mb-2"></i>
            <p class="text-sm font-medium text-gray-900">Reports</p>
        </a>
    </div>

    <!-- Help & Documentation -->
    <div class="bg-linear-to-r from-orange-50 to-orange-100 rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-start space-x-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-white">
                <i class="fas fa-graduation-cap text-xl" style="color: #FF7F39;"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 mb-1">Admin Resources</h3>
                <p class="text-sm text-gray-700 mb-4">Need help managing the system? Check our documentation or contact support.</p>
                <div class="flex gap-3">
                    <button class="text-sm font-medium text-orange-600 hover:text-orange-700">View Documentation</button>
                    <span class="text-gray-400">•</span>
                    <button class="text-sm font-medium text-orange-600 hover:text-orange-700">Contact Support</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
