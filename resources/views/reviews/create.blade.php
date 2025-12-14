@extends('layouts.app')

@section('title', 'Write a Review')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Write a Review</h1>
        <p class="text-gray-600 mt-2">Share your honest feedback to help others</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf

            <!-- Select Service/Booking -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <span style="color: #FF7F39;">*</span> Which service would you like to review?
                </label>
                <select name="booking_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2"
                        style="focus:ring-color: #FF7F39;"
                        required>
                    <option value="">Select a completed service</option>
                    @php
                        $completedBookings = auth()->user()->bookings()
                            ->where('status', 'completed')
                            ->with('review', 'service', 'staff')
                            ->get()
                            ->filter(fn($booking) => !$booking->review);
                    @endphp
                    @forelse($completedBookings as $booking)
                        <option value="{{ $booking->booking_id }}">
                            {{ $booking->service->name ?? 'Service' }} - with {{ $booking->staff->name ?? 'Staff' }} ({{ $booking->date->format('M d, Y') }})
                        </option>
                    @empty
                        <option disabled>No completed services available for review</option>
                    @endforelse
                </select>
                @error('booking_id')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Star Rating -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-3">
                    <span style="color: #FF7F39;">*</span> How would you rate this service?
                </label>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="rating-star focus:outline-none transition hover:scale-110"
                                    data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }}); return false;">
                                <svg class="w-10 h-10 text-gray-300 cursor-pointer" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <span class="text-2xl font-bold" style="color: #FF7F39;" id="ratingValue">0</span>
                    <span class="text-gray-600">/5</span>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="0">
                @error('rating')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Feedback Stars -->
            <div class="mb-6 p-4 rounded-lg" style="background: #FFF5EE;">
                <div class="flex items-center gap-3 mb-3">
                    <div id="feedbackText" class="font-medium" style="color: #0A0A0A;">
                        <span id="feedbackLabel">Rate your experience</span>
                    </div>
                </div>
            </div>

            <!-- Comment -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    <span style="color: #FF7F39;">*</span> Your Review
                </label>
                <textarea name="comment"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 resize-none"
                          style="focus:ring-color: #FF7F39;"
                          rows="6"
                          placeholder="Share details about your experience. What did you like? What could be improved?"
                          required></textarea>
                <p class="text-sm text-gray-500 mt-2">Minimum 20 characters required</p>
                @error('comment')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Helpful Tips -->
            <div class="mb-8 p-4 rounded-lg border border-gray-200" style="background: #FAFAFA;">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tips for a helpful review
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex gap-2">
                        <span style="color: #FF7F39;">✓</span>
                        <span>Be honest and specific about your experience</span>
                    </li>
                    <li class="flex gap-2">
                        <span style="color: #FF7F39;">✓</span>
                        <span>Mention both positives and areas for improvement</span>
                    </li>
                    <li class="flex gap-2">
                        <span style="color: #FF7F39;">✓</span>
                        <span>Avoid personal attacks or inappropriate language</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 px-6 py-3 text-white rounded-lg font-medium transition"
                        style="background: #FF7F39;"
                        onmouseover="this.style.background='#EA6C2F'"
                        onmouseout="this.style.background='#FF7F39'">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Publish Review
                </button>
                <a href="{{ route('reviews.index') }}"
                   class="px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const feedbackMessages = {
        0: { text: 'Rate your experience', color: '#757575' },
        1: { text: 'Poor - Needs improvement', color: '#EF4444' },
        2: { text: 'Fair - Some issues', color: '#F97316' },
        3: { text: 'Good - Overall satisfied', color: '#EAB308' },
        4: { text: 'Very Good - Highly satisfied', color: '#84CC16' },
        5: { text: 'Excellent - Would recommend', color: '#22C55E' }
    };

    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        document.getElementById('ratingValue').textContent = rating;

        // Update stars
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            const svg = star.querySelector('svg');
            if (index < rating) {
                svg.classList.remove('text-gray-300');
                svg.classList.add('text-yellow-400');
            } else {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            }
        });

        // Update feedback message
        const feedback = feedbackMessages[rating];
        const feedbackLabel = document.getElementById('feedbackLabel');
        feedbackLabel.textContent = feedback.text;
        feedbackLabel.style.color = feedback.color;
    }

    // Hover effect on rating stars
    document.querySelectorAll('.rating-star').forEach((star) => {
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            document.querySelectorAll('.rating-star').forEach((s, index) => {
                const svg = s.querySelector('svg');
                if (index < rating) {
                    svg.classList.remove('text-gray-300');
                    svg.classList.add('text-yellow-300');
                } else {
                    svg.classList.remove('text-yellow-300');
                    svg.classList.add('text-gray-300');
                }
            });
        });
    });

    document.getElementById('ratingStars').addEventListener('mouseout', function() {
        const currentRating = parseInt(document.getElementById('ratingInput').value);
        setRating(currentRating);
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const rating = document.getElementById('ratingInput').value;
        const comment = document.querySelector('textarea[name="comment"]').value;

        if (rating === '0') {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Rating Required',
                text: 'Please select a rating before submitting',
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
            return false;
        }

        if (comment.trim().length < 20) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Comment Too Short',
                text: 'Please write at least 20 characters for your review',
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
            return false;
        }
    });
</script>

<style>
    textarea:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
    }

    select:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
    }
</style>
@endsection
