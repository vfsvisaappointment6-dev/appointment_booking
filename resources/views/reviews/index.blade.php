@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">My Reviews</h1>
                <p class="text-gray-600 mt-2">Share your experience and help others discover great services</p>
            </div>
            <a href="{{ route('reviews.create') }}"
               class="px-6 py-3 text-white rounded-lg font-medium transition flex items-center gap-2"
               style="background: #FF7F39;"
               onmouseover="this.style.background='#EA6C2F'"
               onmouseout="this.style.background='#FF7F39'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Write a Review
            </a>
        </div>
    </div>

    <!-- Reviews Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse(auth()->user()->reviews()->with(['booking.service', 'staff'])->latest()->get() as $review)
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $review->booking->service->name ?? 'Service' }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            with <span class="font-medium">{{ $review->staff->name ?? 'Staff' }}</span>
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $review->created_at->format('M d, Y') }}
                    </div>
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-0.5">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $review->rating }}.0</span>
                </div>

                <!-- Comment -->
                <p class="text-gray-700 leading-relaxed mb-4">
                    {{ $review->comment }}
                </p>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <button class="px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded transition"
                            onclick="confirmDelete('{{ $review->review_id }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                    <a href="{{ route('reviews.edit', $review->review_id) }}"
                       class="px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2">
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No reviews yet</h3>
                    <p class="text-gray-600 mb-6">You haven't written any reviews. Share your experience with services you've used!</p>
                    <a href="{{ route('reviews.create') }}"
                       class="inline-block px-6 py-3 text-white rounded-lg font-medium transition"
                       style="background: #FF7F39;"
                       onmouseover="this.style.background='#EA6C2F'"
                       onmouseout="this.style.background='#FF7F39'">
                        Write Your First Review
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    function confirmDelete(reviewId) {
        Swal.fire({
            title: 'Delete Review?',
            text: 'Are you sure you want to delete this review? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF7F39',
            cancelButtonColor: '#0A0A0A',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            background: '#FFFFFF',
            color: '#0A0A0A',
            iconColor: '#FF7F39',
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit a hidden form to delete the review
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/reviews/${reviewId}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
