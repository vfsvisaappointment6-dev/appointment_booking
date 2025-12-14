@extends('layouts.app')

@section('title', 'Reschedule Booking')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <a href="{{ route('bookings.show', $booking->booking_id) }}" class="inline-flex items-center gap-2 font-medium mb-6 transition hover:opacity-75" style="color: #FF7F39;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Back to Booking
    </a>

    <div class="bg-white rounded-lg border border-gray-200 p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2" style="font-family: 'Playfair Display', serif;">Reschedule Booking</h1>
        <p class="text-gray-600 mb-6">Change the date and time for your appointment</p>

        <!-- Current Booking Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
            <p class="text-sm text-gray-600">Service: <span class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</span></p>
            <p class="text-sm text-gray-600 mt-2">Staff: <span class="font-semibold text-gray-900">{{ $booking->staff->name ?? 'Staff' }}</span></p>
            <p class="text-sm text-gray-600 mt-2">Current: <span class="font-semibold text-gray-900">{{ $booking->date->format('M d, Y') }} at {{ $booking->time->format('h:i A') }}</span></p>
        </div>

        <form action="{{ route('bookings.reschedule.submit', $booking->booking_id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Date</label>
                    <input
                        type="date"
                        id="rescheduleDate"
                        name="date"
                        value="{{ old('date', $booking->date->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        style="focus:border-color: #FF7F39;">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Time</label>
                    <div id="timeInputWrapper" class="relative">
                        <input
                            type="time"
                            id="rescheduleTime"
                            name="time"
                            value="{{ old('time', $booking->time->format('H:i')) }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                            style="focus:border-color: #FF7F39;">
                        <p id="bookedWarning" class="text-orange-500 text-xs mt-1 hidden">This time slot is already booked. Please choose another time.</p>
                    </div>
                    @error('time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition flex-1" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    Confirm Reschedule
                </button>
                <a href="{{ route('bookings.show', $booking->booking_id) }}" class="px-6 py-3 border-2 rounded-lg font-medium text-center transition" style="border-color: #e5e7eb; color: #6b7280;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    let bookedTimes = [];

    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('rescheduleDate');
        const timeInput = document.getElementById('rescheduleTime');
        const bookedWarning = document.getElementById('bookedWarning');
        const staffId = '{{ $booking->staff_id }}';

        // Function to fetch booked times
        async function updateBookedTimes() {
            const date = dateInput.value;

            if (!date) {
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

        // Function to validate time selection
        function validateTimeSelection() {
            const selectedTime = timeInput.value;
            // Allow the current time to be selected without warning
            if (selectedTime && bookedTimes.includes(selectedTime) && selectedTime !== '{{ $booking->time->format('H:i') }}') {
                timeInput.classList.add('border-orange-500');
                bookedWarning.classList.remove('hidden');
            } else {
                timeInput.classList.remove('border-orange-500');
                bookedWarning.classList.add('hidden');
            }
        }

        dateInput.addEventListener('change', updateBookedTimes);
        timeInput.addEventListener('change', validateTimeSelection);
        timeInput.addEventListener('input', validateTimeSelection);
    });
</script>

<style>
    input:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(255, 127, 57, 0.1);
    }
</style>
@endsection
