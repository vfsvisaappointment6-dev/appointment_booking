<!-- Services Report -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Service Performance</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Service</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Bookings</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Revenue</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Revenue/Booking</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse($servicePerformance ?? [] as $service)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $service['service']->name }}</td>
                        <td class="py-3 px-4 text-right text-gray-900">{{ $service['bookings'] }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-green-600">${{ number_format($service['revenue'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-gray-900">${{ number_format($service['revenue_per_booking'], 2) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="text-yellow-500">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($service['avgRating']))
                                        <i class="fas fa-star text-xs"></i>
                                    @elseif($i < ceil($service['avgRating']))
                                        <i class="fas fa-star-half-alt text-xs"></i>
                                    @else
                                        <i class="far fa-star text-xs"></i>
                                    @endif
                                @endfor
                            </span>
                            {{ $service['avgRating'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">No services found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Top Services -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($topServices ?? [] as $service)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <h3 class="font-semibold text-gray-900">{{ $service['service']->name }}</h3>
                <span class="text-2xl font-bold" style="color: #FF7F39;">{{ $service['bookings'] }}</span>
            </div>
            <div class="space-y-2 text-sm text-gray-600">
                <p>
                    <i class="fas fa-money-bill text-green-500 mr-2"></i>
                    <span class="font-semibold text-gray-900">${{ number_format($service['revenue'], 2) }}</span> generated
                </p>
                <p>
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    <span class="font-semibold text-gray-900">${{ number_format($service['revenue_per_booking'], 2) }}</span> per booking
                </p>
                <p>
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    <span class="font-semibold text-gray-900">{{ $service['avgRating'] }}/5</span> average rating
                </p>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-8 text-gray-500">
            No service data available
        </div>
    @endforelse
</div>
