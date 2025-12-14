@props(['step', 'title', 'completed' => false, 'active' => false])

<div class="flex items-start">
    <!-- Step Indicator -->
    <div class="flex flex-col items-center">
        <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold text-sm
            @if($completed)
                bg-green-100 text-green-700
            @elseif($active)
                bg-teal-600 text-white
            @else
                bg-gray-200 text-gray-600
            @endif
        ">
            @if($completed)
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            @else
                {{ $step }}
            @endif
        </div>
        <div class="last:hidden w-1 h-16 bg-gray-300 my-2"></div>
    </div>

    <!-- Content -->
    <div class="ml-4 flex-1">
        <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
        <div class="mt-2 text-gray-600">
            {{ $slot }}
        </div>
    </div>
</div>
