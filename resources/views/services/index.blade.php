@extends('layouts.app')

@section('title', 'Browse Services')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Page Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Browse Services</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-2">Discover our premium salon services and book your appointment</p>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 sm:mb-8 bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <div>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search services..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 transition"
                    style="focus:ring-color: #FF7F39;">
            </div>
            <div>
                <select id="categoryFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 transition" style="focus:ring-color: #FF7F39;">
                    <option value="">All Categories</option>
                    <option value="Hair">Hair Services</option>
                    <option value="Facial">Facial & Skincare</option>
                    <option value="Body">Body Treatment</option>
                    <option value="Nail">Nail Services</option>
                    <option value="Eyebrow">Eyebrow & Eyelash</option>
                    <option value="Makeup">Makeup</option>
                    <option value="Waxing">Waxing</option>
                </select>
            </div>
            <div>
                <select id="sortFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 transition" style="focus:ring-color: #FF7F39;">
                    <option value="popular">Sort by: Popular</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="name">Name: A to Z</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="servicesGrid">
        @forelse(\App\Models\Service::where('status', 'active')->get() as $service)
            <div class="service-card bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition"
                 data-service-name="{{ $service->name }}"
                 data-service-price="{{ $service->price }}"
                 data-service-category="{{ substr($service->name, 0, 20) }}">

                <!-- Service Image/Icon -->
                <div class="h-48 bg-linear-to-br overflow-hidden" style="background: linear-gradient(135deg, #FFF5EE 0%, #FFE8DC 100%);">
                    @if($service->image_url)
                        <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover hover:scale-110 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Service Info -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ $service->description ?? 'Premium service by our expert professionals' }}
                    </p>

                    <!-- Service Details -->
                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium text-gray-900">{{ $service->duration }} mins</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Price:</span>
                            <span class="text-2xl font-bold" style="color: #FF7F39;">₵{{ number_format($service->price, 2) }}</span>
                        </div>
                    </div>

                    <!-- Rating (real-time) -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-0.5">
                            @php
                                $avgRating = $service->average_rating;
                                $ratingFloor = floor($avgRating);
                            @endphp
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 {{ $i < $ratingFloor ? 'text-yellow-400' : ($i < ceil($avgRating) ? 'text-yellow-200' : 'text-gray-300') }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500">({{ number_format($avgRating, 1) }})</span>
                    </div>

                    <!-- Action Button -->
                    <button class="w-full px-4 py-3 text-white rounded-lg font-medium transition flex items-center justify-center gap-2"
                            style="background: #FF7F39;"
                            onmouseover="this.style.background='#EA6C2F'"
                            onmouseout="this.style.background='#FF7F39'"
                            onclick="bookService('{{ $service->service_id }}', '{{ $service->name }}')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Book Now
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 0v10l8 4" />
                </svg>
                <p class="text-gray-600 text-lg">No services available</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.service-card').forEach(card => {
            const name = card.dataset.serviceName.toLowerCase();
            card.style.display = name.includes(searchTerm) ? '' : 'none';
        });
    });

    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', function(e) {
        const category = e.target.value.toLowerCase();
        document.querySelectorAll('.service-card').forEach(card => {
            const name = card.dataset.serviceName.toLowerCase();
            const matches = !category || name.includes(category);
            card.style.display = matches ? '' : 'none';
        });
    });

    // Sort functionality
    document.getElementById('sortFilter').addEventListener('change', function(e) {
        const grid = document.getElementById('servicesGrid');
        const cards = Array.from(grid.querySelectorAll('.service-card'));
        const sortType = e.target.value;

        cards.sort((a, b) => {
            if (sortType === 'price-asc') {
                return parseFloat(a.dataset.servicePrice) - parseFloat(b.dataset.servicePrice);
            } else if (sortType === 'price-desc') {
                return parseFloat(b.dataset.servicePrice) - parseFloat(a.dataset.servicePrice);
            } else if (sortType === 'name') {
                return a.dataset.serviceName.localeCompare(b.dataset.serviceName);
            }
            return 0;
        });

        cards.forEach(card => grid.appendChild(card));
    });

    // Book service function
    function bookService(serviceId, serviceName) {
        Swal.fire({
            title: 'Book Service',
            text: `You are about to book "${serviceName}". Please proceed to select a date and time.`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#FF7F39',
            cancelButtonColor: '#0A0A0A',
            confirmButtonText: 'Continue',
            cancelButtonText: 'Cancel',
            background: '#FFFFFF',
            color: '#0A0A0A',
            iconColor: '#FF7F39',
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to booking form
                window.location.href = '/bookings/create/' + serviceId;
            }
        });
    }
</script>

<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    select:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
    }

    input:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
    }
</style>
@endsection
