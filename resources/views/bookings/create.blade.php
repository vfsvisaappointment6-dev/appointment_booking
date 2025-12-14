@extends('layouts.app')

@section('title', 'Book an Appointment')

@section('content')
<!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold" style="color: #0A0A0A; font-family: 'Playfair Display', serif;">Book an Appointment</h1>
        <p class="text-gray-600 mt-2">Follow these simple steps to schedule your service</p>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
            @csrf

            <!-- Step 1: Service Selection (Pre-selected) -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Service</h2>
                <div class="p-4 bg-linear-to-br rounded-lg border-2" style="background: linear-gradient(135deg, #FFF5EE 0%, #FFE8DC 100%); border-color: #FF7F39;">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $service->name }}</p>
                            <p class="text-gray-600 mt-2">{{ $service->description }}</p>
                            <div class="mt-3 space-y-1 text-sm">
                                <p class="text-gray-700"><span class="font-semibold">Duration:</span> {{ $service->duration }} minutes</p>
                                <p class="text-gray-700"><span class="font-semibold">Price:</span> <span style="color: #FF7F39; font-size: 1.25rem; font-weight: bold;">₵{{ number_format($service->price, 2) }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="service_id" value="{{ $service->service_id }}">
            </div>

            <!-- Step 2: Staff Selection -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Choose Your Staff Member</h2>
                @if($staff->isEmpty())
                    <p class="text-gray-600">No staff members available at the moment.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($staff as $member)
                            @php
                                $isActive = $member->staffProfile && $member->staffProfile->status === 'active';
                                $status = $member->staffProfile->status ?? 'active';
                                $statusLabel = match($status) {
                                    'active' => 'Available',
                                    'inactive' => 'Offline',
                                    'on-leave' => 'On Leave',
                                    'busy' => 'Busy',
                                    default => 'Unknown'
                                };
                                $statusColor = match($status) {
                                    'active' => '#10B981',
                                    'inactive' => '#EF4444',
                                    'on-leave' => '#F59E0B',
                                    'busy' => '#F97316',
                                    default => '#6B7280'
                                };
                                // Color codes for rgba
                                $statusBg = match($status) {
                                    'active' => 'rgba(16, 185, 129, 0.1)',
                                    'inactive' => 'rgba(239, 68, 68, 0.1)',
                                    'on-leave' => 'rgba(245, 158, 11, 0.1)',
                                    'busy' => 'rgba(249, 115, 22, 0.1)',
                                    default => 'rgba(107, 114, 128, 0.1)'
                                };
                            @endphp
                            <label data-staff-label class="p-4 border-2 border-gray-200 rounded-lg transition cursor-pointer {{ !$isActive ? 'opacity-60' : 'hover:border-gray-300 hover:bg-gray-50' }}" style="border-color: #e5e7eb;" {{ !$isActive ? 'disabled' : '' }}>
                                <input type="radio" name="staff_id" value="{{ $member->user_id }}" class="hidden" {{ !$isActive ? 'disabled' : 'required' }}>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center relative" style="background: #FFF5EE;">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" style="color: #FF7F39;">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                            @if($isActive)
                                                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white" style="background: #10B981;"></div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $member->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $member->staffProfile->specialty ?? 'Professional' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">⭐ 4.5 (12 bookings)</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background-color: {{ $statusBg }}; color: {{ $statusColor }};">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                                @if(!$isActive)
                                    <p class="text-xs text-red-500 mt-2">This staff member is currently {{ strtolower($statusLabel) }} and cannot be booked.</p>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('staff_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <!-- Step 3: Date & Time Selection -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Select Date & Time</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
                        <input
                            type="date"
                            id="bookingDate"
                            name="date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                            style="focus:ring-color: #FF7F39; focus:border-color: #FF7F39;"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        @error('date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                        <div id="timeInputWrapper" class="relative">
                            <input
                                type="time"
                                id="bookingTime"
                                name="time"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition"
                                style="focus:ring-color: #FF7F39; focus:border-color: #FF7F39;"
                                required>
                            <p id="bookedWarning" class="text-orange-500 text-xs mt-1 hidden">This time slot is already booked. Please choose another time.</p>
                        </div>
                        @error('time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Service:</span>
                        <span class="font-semibold text-gray-900">{{ $service->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-semibold text-gray-900">{{ $service->duration }} minutes</span>
                    </div>
                    <div class="border-t border-gray-200 pt-4 flex justify-between">
                        <span class="font-semibold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold" style="color: #FF7F39;">₵{{ number_format($service->price, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('services.index') }}" class="px-6 py-3 border-2 rounded-lg font-medium transition" style="border-color: #e5e7eb; color: #6b7280;">
                    Back to Services
                </a>
                <button type="submit" class="px-6 py-3 text-white rounded-lg font-medium transition flex-1" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    Complete Booking
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    input:focus, select:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(255, 127, 57, 0.1);
    }

    input[type="radio"]:checked + div {
        border-color: #FF7F39 !important;
        background-color: #FFF5EE !important;
    }

    /* Fallback: add a class when checked using JS to style the label (works across browsers) */
    .staff-selected {
        border-color: #FF7F39 !important;
        background-color: #FFF5EE !important;
    }

    label[disabled] {
        cursor: not-allowed;
    }
</style>

<script>
    let bookedTimes = [];

    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('input[name="staff_id"]');
        const dateInput = document.getElementById('bookingDate');
        const timeInput = document.getElementById('bookingTime');
        const bookedWarning = document.getElementById('bookedWarning');

        // Function to fetch booked times for selected staff and date
        async function updateBookedTimes() {
            const staffId = document.querySelector('input[name="staff_id"]:checked')?.value;
            const date = dateInput.value;

            if (!staffId || !date) {
                bookedTimes = [];
                validateTimeSelection();
                return;
            }

            try {
                const response = await fetch(`/api/staff/${staffId}/booked-times/${date}`);
                const data = await response.json();
                bookedTimes = data.booked_times || [];
                validateTimeSelection();
            } catch (error) {
                console.error('Error fetching booked times:', error);
                bookedTimes = [];
            }
        }

        // Function to validate current time selection
        function validateTimeSelection() {
            const selectedTime = timeInput.value;
            if (selectedTime && bookedTimes.includes(selectedTime)) {
                timeInput.classList.add('border-orange-500');
                bookedWarning.classList.remove('hidden');
                // Don't prevent form submission, but warn the user
            } else {
                timeInput.classList.remove('border-orange-500');
                bookedWarning.classList.add('hidden');
            }
        }

        // Handle staff selection change
        radios.forEach(radio => {
            const label = radio.closest('label[data-staff-label]');
            if (!label) return;

            // initialize
            if (radio.checked) label.classList.add('staff-selected');

            radio.addEventListener('change', function () {
                // remove from all
                radios.forEach(r => {
                    const l = r.closest('label[data-staff-label]');
                    if (l) l.classList.remove('staff-selected');
                });
                if (radio.checked) label.classList.add('staff-selected');
                updateBookedTimes();
            });

            // also allow clicking the label to toggle selection visually
            label.addEventListener('click', function () {
                // Don't allow clicking disabled labels
                if (radio.disabled) return;
                setTimeout(() => {
                    radios.forEach(r => {
                        const l = r.closest('label[data-staff-label]');
                        if (l) l.classList.remove('staff-selected');
                    });
                    if (radio.checked) label.classList.add('staff-selected');
                }, 10);
            });
        });

        // Handle date change
        dateInput.addEventListener('change', updateBookedTimes);

        // Handle time change for validation
        timeInput.addEventListener('change', validateTimeSelection);
        timeInput.addEventListener('input', validateTimeSelection);
    });
</script>
@endsection
