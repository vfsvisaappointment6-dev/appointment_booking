@props(['appointment'])

<div class="bg-white rounded-lg border border-gray-200 p-6">
    <!-- Header -->
    <div class="flex items-start justify-between pb-4 border-b border-gray-200">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $appointment->service->name }}</h2>
            <p class="text-gray-600 mt-1">with <span class="font-medium">{{ $appointment->staffProfile->user->name }}</span></p>
        </div>
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
            @if($appointment->status === 'confirmed')
                bg-green-50 text-green-700
            @elseif($appointment->status === 'pending')
                bg-yellow-50 text-yellow-700
            @elseif($appointment->status === 'cancelled')
                bg-red-50 text-red-700
            @elseif($appointment->status === 'completed')
                bg-blue-50 text-blue-700
            @endif
        ">
            {{ ucfirst($appointment->status) }}
        </span>
    </div>

    <!-- Main Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Left Column -->
        <div class="space-y-6">
            <!-- Date & Time -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Date & Time</h3>
                <div class="bg-teal-50 rounded-lg p-4 border border-teal-200">
                    <p class="text-2xl font-bold text-teal-600">{{ $appointment->booking_date->format('M d, Y') }}</p>
                    <p class="text-lg text-teal-700 mt-2">{{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}</p>
                </div>
            </div>

            <!-- Location -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Location</h3>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-400 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->location ?? 'TBD' }}</p>
                        @if($appointment->notes)
                            <p class="text-sm text-gray-600 mt-1">{{ $appointment->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Service Details</h3>
                <ul class="space-y-2">
                    <li class="flex items-center justify-between">
                        <span class="text-gray-600">Duration</span>
                        <span class="font-medium">{{ $appointment->service->duration }} minutes</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span class="text-gray-600">Service Price</span>
                        <span class="font-medium">${{ number_format($appointment->service->base_price, 2) }}</span>
                    </li>
                    @if($appointment->addOns && count($appointment->addOns) > 0)
                        <li class="flex items-center justify-between border-t pt-2">
                            <span class="text-gray-600">Add-ons</span>
                            <span class="font-medium">${{ number_format($appointment->addOns->sum('price'), 2) }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Payment Summary -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Payment Summary</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">${{ number_format($appointment->service->base_price, 2) }}</span>
                    </div>

                    @if($appointment->promoCode)
                        <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600">Promo Code: {{ $appointment->promoCode->code }}</span>
                            <span class="font-medium text-green-600">-${{ number_format($appointment->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="border-t-2 border-gray-300 pt-3 flex items-center justify-between">
                        <span class="font-semibold text-gray-900">Total Amount</span>
                        <span class="text-2xl font-bold text-teal-600">${{ number_format($appointment->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            @if($appointment->payment)
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Payment Status</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($appointment->payment->status === 'completed')
                                    bg-green-50 text-green-700
                                @elseif($appointment->payment->status === 'pending')
                                    bg-yellow-50 text-yellow-700
                                @elseif($appointment->payment->status === 'failed')
                                    bg-red-50 text-red-700
                                @endif
                            ">
                                {{ ucfirst($appointment->payment->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Transaction ID</span>
                            <span class="font-mono text-sm font-medium">{{ $appointment->payment->transaction_id }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Payment Date</span>
                            <span class="text-sm">{{ $appointment->payment->paid_at?->format('M d, Y h:i A') ?? 'Pending' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Staff Rating -->
            @if($appointment->status === 'completed')
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Staff Member</h3>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <img src="https://ui-avatars.com/api/?name={{ $appointment->staffProfile->user->name }}&background=0F766E&color=fff"
                             alt="{{ $appointment->staffProfile->user->name }}"
                             class="w-12 h-12 rounded-full">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $appointment->staffProfile->user->name }}</p>
                            <div class="flex items-center gap-0.5 mt-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < floor($appointment->staffProfile->reviews->avg('rating') ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
