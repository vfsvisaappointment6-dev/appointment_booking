<!-- Bookings Report -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Bookings -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <p class="text-blue-700 text-sm font-medium">Total Bookings</p>
        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalBookings ?? 0 }}</p>
    </div>

    <!-- Completed -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">Completed</p>
        <p class="text-3xl font-bold text-green-900 mt-2">{{ $bookingsByStatus['completed'] ?? 0 }}</p>
    </div>

    <!-- Pending -->
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-6">
        <p class="text-yellow-700 text-sm font-medium">Pending</p>
        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $bookingsByStatus['pending'] ?? 0 }}</p>
    </div>

    <!-- Cancelled -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-sm border border-red-200 p-6">
        <p class="text-red-700 text-sm font-medium">Cancelled</p>
        <p class="text-3xl font-bold text-red-900 mt-2">{{ $bookingsByStatus['cancelled'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Booking Status Distribution -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Booking Status Distribution</h3>
        <div class="space-y-4">
            @php
                $statuses = ['confirmed' => '#3B82F6', 'completed' => '#10B981', 'pending' => '#F59E0B', 'cancelled' => '#EF4444'];
                $total = $bookingsByStatus['confirmed'] + $bookingsByStatus['completed'] + $bookingsByStatus['pending'] + $bookingsByStatus['cancelled'];
            @endphp
            @foreach(['confirmed' => 'Confirmed', 'completed' => 'Completed', 'pending' => 'Pending', 'cancelled' => 'Cancelled'] as $status => $label)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">{{ $label }}</span>
                        <span class="font-semibold">{{ $bookingsByStatus[$status] ?? 0 }} ({{ $total > 0 ? round(($bookingsByStatus[$status] ?? 0 / $total) * 100) : 0 }}%)</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full" style="background: {{ $statuses[$status] }}; width: {{ $total > 0 ? (($bookingsByStatus[$status] ?? 0) / $total * 100) : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Performance Metrics</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Completion Rate</span>
                <span class="font-semibold text-green-600">{{ $completionRate ?? 0 }}%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Cancellation Rate</span>
                <span class="font-semibold text-red-600">{{ $cancellationRate ?? 0 }}%</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-gray-600">Pending Ratio</span>
                <span class="font-semibold">{{ $totalBookings > 0 ? round(($bookingsByStatus['pending'] ?? 0 / $totalBookings) * 100, 1) : 0 }}%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Confirmation Rate</span>
                <span class="font-semibold">{{ $totalBookings > 0 ? round(($bookingsByStatus['confirmed'] ?? 0 / $totalBookings) * 100, 1) : 0 }}%</span>
            </div>
        </div>
    </div>
</div>

<!-- Top Services -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Top Services</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Service</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Bookings</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">% of Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topServices ?? [] as $service)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $service->name }}</td>
                        <td class="py-3 px-4 text-right text-gray-900">{{ $service->bookings_count }}</td>
                        <td class="py-3 px-4 text-right">{{ $totalBookings > 0 ? round(($service->bookings_count / $totalBookings) * 100) : 0 }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">No services found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Recent Bookings</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Service</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Status</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings ?? [] as $booking)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">#{{ substr($booking->booking_id, 0, 8) }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $booking->customer->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $booking->service->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            <span class="text-xs px-2 py-1 rounded-full {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $booking->date->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">No bookings found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
