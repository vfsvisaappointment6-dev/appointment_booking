<!-- Staff Report -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Total Staff -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <p class="text-blue-700 text-sm font-medium">Total Staff Members</p>
        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalStaff ?? 0 }}</p>
    </div>

    <!-- Average Rating -->
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-6">
        <p class="text-yellow-700 text-sm font-medium">Average Rating</p>
        <div class="flex items-end gap-2">
            <p class="text-3xl font-bold text-yellow-900">{{ $avgRating ?? 0 }}</p>
            <div class="text-yellow-500 mb-1">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star text-sm"></i>
                @endfor
            </div>
        </div>
    </div>
</div>

<!-- Staff Performance Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Staff Performance</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Staff Member</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Bookings</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Completed</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-600">Revenue</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Rating</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffPerformance ?? [] as $performance)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $performance['staff']->name }}</td>
                        <td class="py-3 px-4 text-right text-gray-900">{{ $performance['bookingsCount'] }}</td>
                        <td class="py-3 px-4 text-right text-gray-900">{{ $performance['completedBookings'] }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-green-600">${{ number_format($performance['revenue'], 2) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="text-yellow-500">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($performance['rating']))
                                        <i class="fas fa-star text-xs"></i>
                                    @elseif($i < ceil($performance['rating']))
                                        <i class="fas fa-star-half-alt text-xs"></i>
                                    @else
                                        <i class="far fa-star text-xs"></i>
                                    @endif
                                @endfor
                            </span>
                            <span class="ml-2 text-gray-600">{{ $performance['rating'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">No staff found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Top Performers -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Top Performers by Bookings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Top Performers (Bookings)</h3>
        <div class="space-y-3">
            @forelse($topPerformers->take(3) ?? [] as $index => $performance)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold" style="color: #FF7F39;">{{ $index + 1 }}</span>
                                <span class="ml-3 font-semibold text-gray-900">{{ $performance['staff']->name }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $performance['bookingsCount'] }} bookings completed</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
            @endforelse
        </div>
    </div>

    <!-- Top Performers by Revenue -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Top Earners</h3>
        <div class="space-y-3">
            @forelse($topPerformers->sortByDesc('revenue')->take(3) ?? [] as $index => $performance)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold" style="color: #FF7F39;">{{ $index + 1 }}</span>
                                <span class="ml-3 font-semibold text-gray-900">{{ $performance['staff']->name }}</span>
                            </div>
                            <p class="text-sm text-green-600 mt-1 font-semibold">${{ number_format($performance['revenue'], 2) }} generated</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
            @endforelse
        </div>
    </div>
</div>
