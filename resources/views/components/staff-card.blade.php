@props(['staff'])

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
    <div class="relative">
        <!-- Cover Image -->
        <div class="h-32 bg-linear-to-r from-teal-400 to-blue-500"></div>

        <!-- Avatar -->
        <div class="absolute -bottom-6 left-6">
            <img src="https://ui-avatars.com/api/?name={{ $staff->user->name }}&size=120&background=0F766E&color=fff"
                 alt="{{ $staff->user->name }}"
                 class="w-24 h-24 rounded-full border-4 border-white">
        </div>
    </div>

    <!-- Content -->
    <div class="px-6 pt-10 pb-6">
        <!-- Name & Title -->
        <div>
            <h3 class="text-lg font-bold text-gray-900">{{ $staff->user->name }}</h3>
            <p class="text-teal-600 font-medium text-sm mt-1">{{ $staff->specialization ?? 'Professional' }}</p>
        </div>

        <!-- Rating -->
        <div class="flex items-center gap-2 mt-3">
            <div class="flex items-center gap-0.5">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 {{ $i < floor($staff->reviews->avg('rating') ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            <span class="text-sm text-gray-600">({{ $staff->reviews->count() }} reviews)</span>
        </div>

        <!-- Bio -->
        @if($staff->bio)
            <p class="text-gray-600 text-sm mt-4 line-clamp-2">{{ $staff->bio }}</p>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
            <div>
                <p class="text-xs text-gray-600 uppercase font-medium">Experience</p>
                <p class="text-lg font-bold text-gray-900 mt-1">{{ $staff->experience_years ?? 'N/A' }} yrs</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 uppercase font-medium">Bookings</p>
                <p class="text-lg font-bold text-gray-900 mt-1">{{ $staff->bookings->count() }}</p>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mt-4">
            @if($staff->is_available)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" /></svg>
                    Available Now
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="8" /></svg>
                    Unavailable
                </span>
            @endif
        </div>
    </div>

    <!-- Action Button -->
    <div class="px-6 pb-6 border-t border-gray-100">
        <a href="{{ route('staff-profiles.show', $staff) }}" class="block w-full px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium text-center text-sm transition">
            View Profile
        </a>
    </div>
</div>
