@props(['icon', 'title', 'value', 'change' => null, 'trend' => null, 'color' => 'teal'])

@php
    $colorClasses = [
        'teal' => 'bg-orange-50 text-orange-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'black' => 'bg-gray-100 text-gray-900',
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-orange-50 text-orange-600',
    ];

    $colorStyle = [
        'orange' => 'background: #FFF5EE; color: #FF7F39;',
        'blue' => 'background: #F0F9FF; color: #0284C7;',
        'green' => 'background: #FFF5EE; color: #FF7F39;',
    ];
@endphp

<div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" style="font-family: 'Playfair Display', serif;">{{ $value }}</p>
            @if($change)
                <p class="text-sm mt-3 flex items-center gap-1
                    @if($trend === 'up')
                        text-green-600
                    @elseif($trend === 'down')
                        text-red-600
                    @else
                        text-gray-600
                    @endif
                ">
                    @if($trend === 'up')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414-1.414L13.586 7H12z" clip-rule="evenodd" />
                        </svg>
                    @elseif($trend === 'down')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 13a1 1 0 110 2H7a1 1 0 01-1-1V9a1 1 0 112 0v2.586l4.293-4.293a1 1 0 011.414 1.414L8.414 13H12z" clip-rule="evenodd" />
                        </svg>
                    @endif
                    {{ $change }}
                </p>
            @endif
        </div>
        <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="{{ $colorStyle[$color] ?? $colorStyle['orange'] }}">
            {!! $icon !!}
        </div>
    </div>
</div>
