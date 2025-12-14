<!-- Customers Report -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Customers -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-sm border border-purple-200 p-6">
        <p class="text-purple-700 text-sm font-medium">Total Customers</p>
        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $totalCustomers ?? 0 }}</p>
    </div>

    <!-- New Customers -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <p class="text-blue-700 text-sm font-medium">New Customers (Period)</p>
        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $newCustomers ?? 0 }}</p>
    </div>

    <!-- Active Customers -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">Active Customers</p>
        <p class="text-3xl font-bold text-green-900 mt-2">{{ $activeCustomers ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Customer Stats -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Customer Statistics</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Average Bookings per Customer</span>
                <span class="font-semibold">{{ number_format($avgBookingsPerCustomer ?? 0, 1) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Activation Rate</span>
                <span class="font-semibold">{{ $totalCustomers > 0 ? round(($activeCustomers / $totalCustomers) * 100) : 0 }}%</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-gray-600">New Customer Ratio</span>
                <span class="font-semibold">{{ $totalCustomers > 0 ? round(($newCustomers / $totalCustomers) * 100, 1) : 0 }}%</span>
            </div>
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Quick Insights</h3>
        <div class="space-y-3 text-sm">
            <p class="text-gray-600">
                <i class="fas fa-user-plus text-orange-500 mr-2"></i>
                {{ $newCustomers }} new customers in this period
            </p>
            <p class="text-gray-600">
                <i class="fas fa-chart-line text-green-500 mr-2"></i>
                {{ $activeCustomers }} customers have active bookings
            </p>
            <p class="text-gray-600">
                <i class="fas fa-users text-blue-500 mr-2"></i>
                {{ $totalCustomers - $activeCustomers }} inactive customers
            </p>
        </div>
    </div>
</div>

<!-- Top Customers by Bookings -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Top Customers by Bookings</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Email</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Bookings</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topCustomers ?? [] as $customer)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $customer->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $customer->email }}</td>
                        <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ $customer->bookings_count }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">No customers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Top Spenders -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Top Spenders</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Customer</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Email</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Total Spent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topSpenders ?? [] as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $item['customer']->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $item['customer']->email }}</td>
                        <td class="py-3 px-4 text-right font-semibold" style="color: #FF7F39;">${{ number_format($item['totalSpent'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">No spending data found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
