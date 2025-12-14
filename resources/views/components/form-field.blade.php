@props(['label', 'name', 'type' => 'text', 'required' => false, 'error' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>{{ old($name) }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
            <option value="">-- Select {{ strtolower($label) }} --</option>
            {{ $slot }}
        </select>
    @elseif($type === 'date')
        <input
            type="date"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
    @elseif($type === 'time')
        <input
            type="time"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
    @elseif($type === 'email')
        <input
            type="email"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
    @elseif($type === 'number')
        <input
            type="number"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name) }}"
            @if($required) required @endif
            {{ $attributes->class(['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition',
                'border-red-500' => $error,
                'border-gray-300' => !$error
            ]) }}>
    @endif

    @if($error)
        <p class="text-red-500 text-sm mt-2">{{ $error }}</p>
    @endif
</div>
