<!-- Overview Report -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Bookings -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-700 text-sm font-medium">Total Bookings</p>
                <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalBookings ?? 0 }}</p>
            </div>
            <i class="fas fa-calendar text-4xl text-blue-300 opacity-50"></i>
        </div>
    </div>

    <!-- Completed Bookings -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-700 text-sm font-medium">Completed</p>
                <p class="text-3xl font-bold text-green-900 mt-2">{{ $completedBookings ?? 0 }}</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-300 opacity-50"></i>
        </div>
    </div>

    <!-- Cancelled Bookings -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-sm border border-red-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-700 text-sm font-medium">Cancelled</p>
                <p class="text-3xl font-bold text-red-900 mt-2">{{ $cancelledBookings ?? 0 }}</p>
            </div>
            <i class="fas fa-times-circle text-4xl text-red-300 opacity-50"></i>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-700 text-sm font-medium">Total Revenue</p>
                <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</p>
            </div>
            <i class="fas fa-money-bill-wave text-4xl text-green-300 opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Payments -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium">Total Payments</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalPayments ?? 0 }}</p>
    </div>

    <!-- New Customers -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium">New Customers</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $newCustomers ?? 0 }}</p>
    </div>

    <!-- New Staff -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p class="text-gray-600 text-sm font-medium">New Staff</p>
        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $newStaff ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Average Rating -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Customer Satisfaction</h3>
        <div class="flex items-center">
            <div class="text-5xl font-bold text-yellow-500">{{ $avgRating ?? 0 }}</div>
            <div class="ml-4">
                <div class="text-yellow-500">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star"></i>
                    @endfor
                </div>
                <p class="text-gray-600 text-sm mt-2">{{ $totalReviews ?? 0 }} reviews</p>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Key Metrics</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Completion Rate:</span>
                <span class="font-semibold text-gray-900">
                    {{ $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0 }}%
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Cancellation Rate:</span>
                <span class="font-semibold text-gray-900">
                    {{ $totalBookings > 0 ? round(($cancelledBookings / $totalBookings) * 100, 1) : 0 }}%
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Avg. Booking Value:</span>
                <span class="font-semibold text-gray-900">
                    ${{ $totalBookings > 0 ? number_format($totalRevenue / $totalBookings, 2) : '0.00' }}
                </span>
            </div>
        </div>
    </div>
</div>
