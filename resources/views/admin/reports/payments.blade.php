<!-- Payments Report -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Successful Payments -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">Successful</p>
        <p class="text-3xl font-bold text-green-900 mt-2">{{ $successfulPayments ?? 0 }}</p>
    </div>

    <!-- Failed Payments -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg shadow-sm border border-red-200 p-6">
        <p class="text-red-700 text-sm font-medium">Failed</p>
        <p class="text-3xl font-bold text-red-900 mt-2">{{ $failedPayments ?? 0 }}</p>
    </div>

    <!-- Refunded Payments -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow-sm border border-orange-200 p-6">
        <p class="text-orange-700 text-sm font-medium">Refunded</p>
        <p class="text-3xl font-bold text-orange-900 mt-2">{{ $refundedPayments ?? 0 }}</p>
    </div>

    <!-- Pending Payments -->
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-6">
        <p class="text-yellow-700 text-sm font-medium">Pending</p>
        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $pendingPayments ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Financial Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Financial Summary</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Revenue</span>
                <span class="font-semibold text-green-600">${{ number_format($totalRevenue ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Failed Transactions</span>
                <span class="font-semibold text-red-600">-${{ number_format($failedAmount ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Refunded Amount</span>
                <span class="font-semibold text-orange-600">-${{ number_format($refundedAmount ?? 0, 2) }}</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between text-lg">
                <span class="font-bold text-gray-900">Net Revenue</span>
                <span class="font-bold" style="color: #FF7F39;">
                    ${{ number_format(($totalRevenue ?? 0) - ($refundedAmount ?? 0), 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Payment Metrics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Payment Metrics</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Success Rate</span>
                <span class="font-semibold">{{ $successRate ?? 0 }}%</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Average Payment</span>
                <span class="font-semibold">${{ number_format($averagePayment ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Transactions</span>
                <span class="font-semibold">{{ $totalPayments ?? 0 }}</span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="text-gray-600">Failure Rate</span>
                <span class="font-semibold text-red-600">{{ 100 - ($successRate ?? 0) }}%</span>
            </div>
        </div>
    </div>
</div>

<!-- Payments by Method -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Payments by Method</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $methodLabels = ['card' => 'Credit Card', 'bank_transfer' => 'Bank Transfer', 'cash' => 'Cash', 'check' => 'Check'];
            $total = $paymentsByMethod instanceof \Illuminate\Support\Collection
                ? $paymentsByMethod->sum()
                : (is_array($paymentsByMethod) ? array_sum($paymentsByMethod) : 0);
            $total = $total ?: 1;
        @endphp
        @forelse($methodLabels as $method => $label)
            @php
                $count = $paymentsByMethod instanceof \Illuminate\Support\Collection
                    ? ($paymentsByMethod->get($method) ?? 0)
                    : ($paymentsByMethod[$method] ?? 0);
            @endphp
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-2">{{ $label }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full" style="background: #FF7F39; width: {{ ($count / $total * 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ round(($count / $total * 100), 1) }}% of payments</p>
            </div>
        @empty
            <p class="col-span-4 text-center text-gray-500 py-4">No payment methods recorded</p>
        @endforelse
    </div>
</div>

<!-- Payment Status Distribution -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Payment Status Distribution</h3>
    <div class="space-y-4">
        @php
            $statuses = [
                'success' => ['label' => 'Successful', 'count' => $successfulPayments ?? 0, 'color' => '#10B981'],
                'failed' => ['label' => 'Failed', 'count' => $failedPayments ?? 0, 'color' => '#EF4444'],
                'refunded' => ['label' => 'Refunded', 'count' => $refundedPayments ?? 0, 'color' => '#F59E0B'],
                'pending' => ['label' => 'Pending', 'count' => $pendingPayments ?? 0, 'color' => '#3B82F6'],
            ];
            $totalStatus = array_sum(array_column($statuses, 'count')) ?: 1;
        @endphp
        @foreach($statuses as $status => $data)
            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600">{{ $data['label'] }}</span>
                    <span class="font-semibold">{{ $data['count'] }} ({{ round(($data['count'] / $totalStatus) * 100) }}%)</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full" style="background: {{ $data['color'] }}; width: {{ ($data['count'] / $totalStatus * 100) }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
