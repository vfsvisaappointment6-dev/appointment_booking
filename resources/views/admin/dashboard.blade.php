@extends('layouts.admin')

@section('content')
<div>
    <!-- Welcome Header with Gradient Background -->
    <div class="mb-6 sm:mb-8 bg-gradient-to-r from-orange-500 via-orange-400 to-red-500 rounded-lg shadow-lg p-4 sm:p-6 lg:p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 sm:w-40 h-32 sm:h-40 bg-white opacity-10 rounded-full -mr-16 sm:-mr-20 -mt-16 sm:-mt-20"></div>
        <div class="absolute bottom-0 left-0 w-24 sm:w-32 h-24 sm:h-32 bg-white opacity-5 rounded-full -ml-12 sm:-ml-16 -mb-12 sm:-mb-16"></div>

        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-2">
                        <i class="fas fa-crown text-xl sm:text-2xl"></i>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black">Admin Dashboard</h1>
                    </div>
                    <p class="text-orange-100 text-sm sm:text-base lg:text-lg font-medium">Welcome back! Here's your business performance snapshot</p>
                    <div class="mt-3 sm:mt-4 flex flex-col xs:flex-row items-start xs:items-center gap-3 xs:space-x-4">
                        <div class="flex items-center space-x-2 bg-white bg-opacity-20 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">
                            <i class="fas fa-calendar-alt text-xs sm:text-sm"></i>
                            <span class="font-semibold">{{ date('l, F j, Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-white bg-opacity-20 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm">
                            <i class="fas fa-clock text-xs sm:text-sm"></i>
                            <span class="font-semibold" id="current-time">{{ date('H:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block text-right flex-shrink-0">
                    <i class="fas fa-chart-line text-5xl lg:text-7xl opacity-20"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update time every minute
        setInterval(() => {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }, 60000);
    </script>

    <!-- Key Metrics Row 1 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-4 sm:p-6 hover:shadow-md transition group cursor-pointer">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                    <p class="text-green-700 text-xs sm:text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-900 mt-1 sm:mt-2">₵{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                    <p class="text-xs text-green-600 mt-1 sm:mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>
                        {{ $stats['revenue_growth'] ?? 0 }}% from last month
                    </p>
                </div>
                <div class="text-3xl sm:text-5xl text-green-300 opacity-30 group-hover:opacity-40 transition flex-shrink-0">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Confirmed Bookings -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-4 sm:p-6 hover:shadow-md transition group cursor-pointer">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                    <p class="text-blue-700 text-xs sm:text-sm font-medium">Confirmed Bookings</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-900 mt-1 sm:mt-2">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
                    <p class="text-xs text-blue-600 mt-1 sm:mt-2">
                        <i class="fas fa-arrow-up mr-1"></i>
                        {{ $stats['booking_growth'] ?? 0 }}% this month
                    </p>
                </div>
                <div class="text-3xl sm:text-5xl text-blue-300 opacity-30 group-hover:opacity-40 transition flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-4 sm:p-6 hover:shadow-md transition group cursor-pointer">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                    <p class="text-yellow-700 text-xs sm:text-sm font-medium">Pending Payments</p>
                    <p class="text-2xl sm:text-3xl font-bold text-yellow-900 mt-1 sm:mt-2">{{ $stats['pending_payments'] ?? 0 }}</p>
                    <p class="text-xs text-yellow-600 mt-1 sm:mt-2">
                        <i class="fas fa-clock mr-1"></i>
                        Awaiting processing
                    </p>
                </div>
                <div class="text-3xl sm:text-5xl text-yellow-300 opacity-30 group-hover:opacity-40 transition flex-shrink-0">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-sm border border-purple-200 p-4 sm:p-6 hover:shadow-md transition group cursor-pointer">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                    <p class="text-purple-700 text-xs sm:text-sm font-medium">Payment Success Rate</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-900 mt-1 sm:mt-2">{{ $stats['payment_success_rate'] ?? 0 }}%</p>
                    <p class="text-xs text-purple-600 mt-1 sm:mt-2">
                        <i class="fas fa-check mr-1"></i>
                        Excellent performance
                    </p>
                </div>
                <div class="text-3xl sm:text-5xl text-purple-300 opacity-30 group-hover:opacity-40 transition flex-shrink-0">
                    <i class="fas fa-heartbeat"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Row 2 -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- User Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm sm:text-base text-gray-900">Users Overview</h3>
                <i class="fas fa-users text-xl sm:text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="mb-4">
                <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_users'] ?? 0 }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $stats['new_users_this_month'] ?? 0 }} new this month</p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4 pt-3 sm:pt-4 border-t border-gray-200">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Customers</p>
                    <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $stats['customer_count'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Staff</p>
                    <p class="text-lg sm:text-xl font-bold text-gray-900">{{ $stats['staff_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Monthly Metrics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm sm:text-base text-gray-900">This Month</h3>
                <i class="fas fa-calendar text-xl sm:text-2xl text-orange-500 opacity-20"></i>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600">Bookings</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['bookings_this_month'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600">Revenue</p>
                    <p class="text-xl sm:text-2xl font-bold text-green-600">₵{{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Services & Reviews -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm sm:text-base text-gray-900">Quality Metrics</h3>
                <i class="fas fa-star text-xl sm:text-2xl text-yellow-500 opacity-20"></i>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Average Rating</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['average_rating'] ?? 0 }}</p>
                        <div class="text-yellow-500 text-sm">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-xs sm:text-sm"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-600">{{ $stats['total_reviews'] ?? 0 }} reviews</p>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Staff -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <h3 class="font-bold text-gray-900 mb-4">Top Staff Members</h3>
            <div class="space-y-3">
                @forelse($stats['top_staff'] ?? [] as $index => $staff)
                    <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white" style="background: #FF7F39;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $staff->name }}</p>
                                <p class="text-xs text-gray-600">{{ $staff->bookings_count }} bookings</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No staff data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <h3 class="font-bold text-gray-900 mb-4">Top Services</h3>
            <div class="space-y-3">
                @forelse($stats['top_services'] ?? [] as $index => $service)
                    <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white" style="background: #FF7F39;">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $service->name }}</p>
                                <p class="text-xs text-gray-600">{{ $service->bookings_count }} bookings</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No service data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Activity Feeds -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8 mb-6 sm:mb-8">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-orange-500 hover:text-orange-600 text-xs sm:text-sm font-medium whitespace-nowrap ml-2">
                    <i class="fas fa-arrow-right mr-1"></i><span class="hidden xs:inline">View All</span>
                </a>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse(\App\Models\Booking::with(['service', 'customer', 'staff'])->latest()->take(6)->get() as $booking)
                    <div class="px-3 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition cursor-pointer border-l-4 border-transparent hover:border-orange-500">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate">{{ $booking->service->name ?? 'Service' }}</p>
                                <p class="text-xs text-gray-600 mt-1 truncate">
                                    <i class="fas fa-user mr-1"></i>{{ $booking->customer->name ?? 'N/A' }}
                                </p>
                                <div class="flex items-center gap-1 sm:gap-2 mt-2 flex-wrap">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : ($booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $booking->date->format('M d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-3 sm:px-6 py-6 sm:py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-2xl sm:text-3xl opacity-20 mb-2"></i>
                        <p class="text-xs sm:text-sm">No recent bookings</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Recent Payments</h2>
                <a href="{{ route('admin.payments.index') }}" class="text-orange-500 hover:text-orange-600 text-xs sm:text-sm font-medium whitespace-nowrap ml-2">
                    <i class="fas fa-arrow-right mr-1"></i><span class="hidden xs:inline">View All</span>
                </a>
            </div>
            <div class="divide-y max-h-96 overflow-y-auto">
                @forelse(\App\Models\Payment::with(['booking'])->latest()->take(6)->get() as $payment)
                    <div class="px-3 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition cursor-pointer border-l-4 border-transparent hover:border-green-500">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm">₵{{ number_format($payment->amount, 2) }}</p>
                                <p class="text-xs text-gray-600 mt-1 truncate">
                                    <i class="fas fa-credit-card mr-1"></i>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Unknown')) }}
                                </p>
                                <div class="flex items-center gap-1 sm:gap-2 mt-2 flex-wrap">
                                    <span class="text-xs px-2 py-1 rounded-full {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : ($payment->status === 'refunded' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $payment->created_at->format('M d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-3 sm:px-6 py-6 sm:py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-2xl sm:text-3xl opacity-20 mb-2"></i>
                        <p class="text-xs sm:text-sm">No recent payments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Administrative Controls -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- User Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="font-bold text-sm sm:text-base text-gray-900 group-hover:text-orange-600 transition">User Management</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Manage system users</p>
                </div>
                <i class="fas fa-users-cog text-xl sm:text-2xl text-orange-500 opacity-20 group-hover:opacity-30 transition flex-shrink-0"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.users.index') }}" class="block px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-lg transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <i class="fas fa-list mr-2"></i><span class="hidden xs:inline">View All Users</span><span class="xs:hidden">Users</span>
                </a>
            </div>
        </div>

        <!-- Booking Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="font-bold text-sm sm:text-base text-gray-900 group-hover:text-orange-600 transition">Booking Management</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Oversee appointments</p>
                </div>
                <i class="fas fa-calendar-alt text-xl sm:text-2xl text-orange-500 opacity-20 group-hover:opacity-30 transition flex-shrink-0"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.bookings.index') }}" class="block px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-lg transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <i class="fas fa-list mr-2"></i><span class="hidden xs:inline">View All Bookings</span><span class="xs:hidden">Bookings</span>
                </a>
            </div>
        </div>

        <!-- Financial Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition group">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="font-bold text-sm sm:text-base text-gray-900 group-hover:text-orange-600 transition">Financial Control</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Payment tracking</p>
                </div>
                <i class="fas fa-money-check-alt text-xl sm:text-2xl text-orange-500 opacity-20 group-hover:opacity-30 transition flex-shrink-0"></i>
            </div>
            <div class="space-y-2">
                <a href="{{ route('admin.payments.index') }}" class="block px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-lg transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <i class="fas fa-receipt mr-2"></i><span class="hidden xs:inline">View Payments</span><span class="xs:hidden">Payments</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Reports & Analytics Link -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg shadow-sm border border-orange-200 p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col xs:flex-row items-start xs:items-center gap-3 xs:gap-4">
            <div class="flex items-center justify-center w-10 h-10 xs:w-12 xs:h-12 rounded-lg bg-white flex-shrink-0">
                <i class="fas fa-chart-bar text-xl xs:text-2xl" style="color: #FF7F39;"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-sm xs:text-base text-gray-900 mb-1">Advanced Analytics</h3>
                <p class="text-xs xs:text-sm text-gray-700 mb-2 xs:mb-3">Access detailed reports on revenue, bookings, customers, staff performance, and more.</p>
                <a href="{{ route('admin.reports') }}" class="text-orange-600 hover:text-orange-700 font-medium text-xs xs:text-sm inline-block">
                    <i class="fas fa-arrow-right mr-1"></i>View Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h3 class="font-bold text-sm sm:text-base text-gray-900 mb-4">Quick Summary</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
            <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['completed_bookings'] ?? 0 }}</p>
                <p class="text-xs text-gray-600 mt-1 sm:mt-2">Completed</p>
            </div>
            <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['pending_bookings'] ?? 0 }}</p>
                <p class="text-xs text-gray-600 mt-1 sm:mt-2">Pending</p>
            </div>
            <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $stats['services'] ?? 0 }}</p>
                <p class="text-xs text-gray-600 mt-1 sm:mt-2">Services</p>
            </div>
            <div class="text-center p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <p class="text-lg sm:text-2xl font-bold text-gray-900">₵{{ number_format($stats['avg_booking_value'] ?? 0, 0) }}</p>
                <p class="text-xs text-gray-600 mt-1 sm:mt-2">Avg Value</p>
            </div>
        </div>
    </div>
</div>

@endsection
