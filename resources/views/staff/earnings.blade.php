@extends('layouts.app')

@section('title', 'Earnings')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Earnings & Reports</h1>
        <p class="text-gray-600 mt-2" style="color: #757575;">Track your income and view detailed earnings reports</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
            $totalEarnings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                ->where('status', 'completed')
                ->where('payment_status', 'paid')
                ->count() * 50;

            $thisMonthEarnings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                ->where('status', 'completed')
                ->where('payment_status', 'paid')
                ->whereMonth('date', now()->month)
                ->count() * 50;

            $pendingEarnings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                ->where('status', 'completed')
                ->where('payment_status', 'unpaid')
                ->count() * 50;

            $totalBookings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)->count();
        @endphp

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Earnings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₵{{ number_format($totalEarnings) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: #FFF5EE;">
                    <i class="fas fa-money-bill-wave w-6 h-6 text-2xl" style="color: #FF7F39;"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₵{{ number_format($thisMonthEarnings) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-50">
                    <i class="fas fa-calendar-alt w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">₵{{ number_format($pendingEarnings) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-yellow-50">
                    <i class="fas fa-hourglass-half w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Bookings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalBookings }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-50">
                    <i class="fas fa-clipboard-list w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Earnings Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Monthly Earnings</h2>
            <div class="relative">
                <canvas id="earningsChart" height="100"></canvas>
            </div>
        </div>

        <!-- Top Services -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Top Services</h2>
            <div class="space-y-3">
                @php
                    $topServices = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                        ->with('service')
                        ->get()
                        ->groupBy('service_id')
                        ->map(fn($group) => ['service' => $group->first()->service, 'count' => $group->count()])
                        ->sortByDesc('count')
                        ->take(5);
                @endphp

                @forelse($topServices as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-900">{{ $item['service']->name }}</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium" style="background: #FFF5EE; color: #FF7F39;">
                            {{ $item['count'] }} bookings
                        </span>
                    </div>
                @empty
                    <p class="text-gray-600 text-center py-4">No bookings yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="mt-6 bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Recent Transactions</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Booking ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Customer</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Service</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Date</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-900">Amount</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-900">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $recentBookings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                            ->with(['customer', 'service'])
                            ->where('status', 'completed')
                            ->latest('date')
                            ->limit(10)
                            ->get();
                    @endphp

                    @forelse($recentBookings as $booking)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ substr($booking->booking_id, 0, 8) }}...</td>
                            <td class="py-3 px-4">{{ $booking->customer->name }}</td>
                            <td class="py-3 px-4">{{ $booking->service->name }}</td>
                            <td class="py-3 px-4">{{ $booking->date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-right font-semibold">₵50</td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium"
                                      style="background: {{ $booking->payment_status === 'paid' ? '#d1fae5' : '#fef3c7' }}; color: {{ $booking->payment_status === 'paid' ? '#065f46' : '#78350f' }};">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-4 text-center text-gray-600">
                                No completed bookings yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $currentYear = now()->year;
            $monthlyData = collect();
            for ($m = 1; $m <= 12; $m++) {
                $start = now()->year($currentYear)->month($m)->startOfMonth();
                $end = now()->year($currentYear)->month($m)->endOfMonth();
                $amount = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                    ->where('status', 'completed')
                    ->where('payment_status', 'paid')
                    ->whereBetween('date', [$start, $end])
                    ->count() * 50;
                $monthlyData->put($m, $amount);
            }
        @endphp

        const data = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [
                {
                    label: 'Monthly Earnings (₵)',
                    data: [{{ $monthlyData->implode(',') }}],
                    borderColor: '#FF7F39',
                    backgroundColor: 'rgba(255, 127, 57, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#FF7F39',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 14, weight: 600 },
                            color: '#374151',
                            padding: 15,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 600 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return '₵' + Number(context.parsed.y).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₵' + value;
                            },
                            font: { size: 12 },
                            color: '#9CA3AF',
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 12 },
                            color: '#9CA3AF',
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        };

        const ctx = document.getElementById('earningsChart').getContext('2d');
        new Chart(ctx, config);
    });
</script>
@endsection
