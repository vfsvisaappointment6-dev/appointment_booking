<!-- Revenue Report -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">Total Revenue</p>
        <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($totalRevenue ?? 0, 2) }}</p>
    </div>

    <!-- Average Transaction -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <p class="text-blue-700 text-sm font-medium">Avg. Transaction</p>
        <p class="text-3xl font-bold text-blue-900 mt-2">${{ number_format($averageTransaction ?? 0, 2) }}</p>
    </div>

    <!-- Successful Payments -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">Successful Payments</p>
        <p class="text-3xl font-bold text-green-900 mt-2">{{ $successfulPayments ?? 0 }}</p>
    </div>

    <!-- Failed Payments -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-sm border border-red-200 p-6">
        <p class="text-red-700 text-sm font-medium">Failed Payments</p>
        <p class="text-3xl font-bold text-red-900 mt-2">{{ $failedPayments ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue by Payment Method -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Revenue by Payment Method</h3>
        <div class="space-y-4">
            @foreach(['card' => 'Credit Card', 'bank_transfer' => 'Bank Transfer', 'cash' => 'Cash', 'check' => 'Check'] as $key => $label)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">{{ $label }}</span>
                        <span class="font-semibold text-gray-900">${{ number_format($revenueByMethod[$key] ?? 0, 2) }}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full" style="background: #FF7F39; width: {{ ($revenueByMethod[$key] ?? 0) > 0 ? (($revenueByMethod[$key] / max(array_values($revenueByMethod ?? []))) * 100) : 0 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Financial Summary</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Revenue</span>
                <span class="font-semibold">${{ number_format($totalRevenue ?? 0, 2) }}</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-gray-600">Refunded Amount</span>
                <span class="font-semibold text-red-600">-${{ number_format($refundedAmount ?? 0, 2) }}</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between text-lg">
                <span class="font-bold text-gray-900">Net Revenue</span>
                <span class="font-bold" style="color: #FF7F39;">${{ number_format(($totalRevenue ?? 0) - ($refundedAmount ?? 0), 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Top Bookings by Value -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Top Bookings by Value</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Booking ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Service</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Amount</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topBookings ?? [] as $booking)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">#{{ substr($booking->booking_id, 0, 8) }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $booking->customer->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-gray-700">{{ $booking->service->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-900">${{ number_format($booking->payment->amount ?? 0, 2) }}</td>
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
