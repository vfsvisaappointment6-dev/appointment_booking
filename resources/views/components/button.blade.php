@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button'])

@php
    $baseClass = 'inline-flex items-center justify-center font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variants = [
        'primary' => 'bg-teal-600 hover:bg-teal-700 text-white focus:ring-teal-500',
        'secondary' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'success' => 'text-white focus:ring-orange-500' style="background: #FF7F39; hover:background: #EA6C2F;",
    ];

    $sizes = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    $classes = "$baseClass {$variants[$variant]} {$sizes[$size]}";
@endphp

<{{ $type === 'submit' ? 'button' : 'a' }}
    @if($type !== 'submit') href="{{ $attributes->get('href') }}" @endif
    {{ $attributes->except('href')->class($classes) }}>
    {{ $slot }}
</{{ $type === 'submit' ? 'button' : 'a' }}>
