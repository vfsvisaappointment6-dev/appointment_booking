@props(['booking'])

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    <!-- Status Badge -->
    <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between">
        <div>
            <h3 class="font-semibold text-gray-900">{{ $booking->service->name }}</h3>
            <p class="text-sm text-gray-600 mt-1">with {{ $booking->staff->name ?? 'Staff Member' }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            @if($booking->status === 'confirmed')
                style="background: #FFF5EE; color: #FF7F39;"
            @elseif($booking->status === 'pending')
                bg-yellow-50 text-yellow-700
            @elseif($booking->status === 'cancelled')
                bg-red-50 text-red-700
            @elseif($booking->status === 'completed')
                style="background: #FFF5EE; color: #FF7F39;"
            @endif
        ">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <!-- Details -->
    <div class="px-6 py-4 space-y-3">
        <!-- Date & Time -->
        <div class="flex items-center space-x-3 text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div>
                <p class="text-sm font-medium">{{ $booking->date->format('D, M d, Y') }}</p>
                <p class="text-sm text-gray-600">{{ $booking->time->format('h:i A') }}</p>
            </div>
        </div>

        <!-- Service Duration -->
        <div class="flex items-center space-x-3 text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm">{{ $booking->service->duration }} minutes</span>
        </div>

        <!-- Location -->
        <div class="flex items-center space-x-3 text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm">{{ $booking->location ?? 'Location TBD' }}</span>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Amount</p>
                <p class="font-semibold text-gray-900">₵{{ number_format($booking->service->price, 2) }}</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($booking->payment_status === 'paid')
                    style="background: #FFF5EE; color: #FF7F39;"
                @elseif($booking->payment_status === 'unpaid')
                    bg-yellow-50 text-yellow-700
                @else
                    bg-gray-200 text-gray-700
                @endif
            ">
                {{ ucfirst($booking->payment_status) }}
            </span>
        </div>
    </div>

    <!-- Actions -->
    <div class="px-6 py-4 border-t border-gray-100 flex gap-2">
        <a href="{{ route('bookings.show', $booking) }}" class="flex-1 text-white py-2 px-4 rounded-lg font-medium text-sm transition" style="background: #FF7F39; text-decoration: none;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            View Details
        </a>
        @if($booking->status === 'confirmed')
            <a href="{{ route('bookings.reschedule', $booking) }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-lg font-medium text-sm transition" style="text-decoration: none;">
                Reschedule
            </a>
        @endif
    </div>
</div>
