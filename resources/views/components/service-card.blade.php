@props(['service'])

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow group cursor-pointer">
    <!-- Image -->
    <div class="relative h-48 bg-gradient-to-br from-gray-200 to-gray-300 overflow-hidden">
        @if($service->image_url)
            <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-teal-100 to-blue-100">
                <svg class="w-16 h-16 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        @endif

        <!-- Category Badge -->
        <div class="absolute top-4 left-4">
            <span class="inline-block px-3 py-1 bg-teal-600 text-white text-xs font-semibold rounded-full">
                {{ $service->category ?? 'Service' }}
            </span>
        </div>

        <!-- Rating Badge -->
        <div class="absolute top-4 right-4">
            <div class="flex items-center gap-1 bg-white rounded-full px-3 py-1 shadow">
                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-semibold text-gray-900">{{ number_format($service->average_rating, 1) }}</span>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-4">
        <!-- Name & Description -->
        <h3 class="text-lg font-bold text-gray-900 group-hover:text-teal-600 transition">{{ $service->name }}</h3>
        <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $service->description }}</p>

        <!-- Service Details -->
        <div class="flex items-center gap-4 mt-4 text-sm text-gray-600">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.447.894l1.006.605a1 1 0 001.894-.894V6z" clip-rule="evenodd" />
                </svg>
                {{ $service->duration }} min
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.16 5.314l4.897-1.596A1 1 0 0114 4.69v4.622a4.5 4.5 0 01-1.414 3.192l-5.854 5.854a3 3 0 11-4.242-4.242l5.854-5.854A1 1 0 018.16 5.314z" />
                </svg>
                {{ $service->availability_count ?? '0' }} staff
            </span>
        </div>
    </div>

    <!-- Price & Action -->
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-xs text-gray-600 uppercase font-medium">Starting from</p>
            <p class="text-2xl font-bold text-teal-600">${{ number_format($service->base_price, 2) }}</p>
        </div>
        <a href="{{ route('services.show', $service) }}" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium text-sm transition">
            Book Now
        </a>
    </div>
</div>
