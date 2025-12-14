@extends('layouts.admin')

@section('title', 'Create Discount Code - Admin')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.promos.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to Codes
        </a>
        <h1 class="text-4xl font-bold text-gray-900">Create Discount Code</h1>
        <p class="text-gray-600 mt-2">Set up a new promotional offer for your customers</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <form method="POST" action="{{ route('admin.promos.store') }}" class="space-y-8">
            @csrf

            <!-- Code Details Section -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Basic Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Promo Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="e.g., NEWYEAR2024"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent uppercase"
                            required @error('code') is-invalid @enderror>
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Use uppercase letters and numbers (e.g., WELCOME50)</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            placeholder="Holiday special discount"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Discount Type Section -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Discount Details</h2>

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Discount Type <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="discount_type" value="percentage" checked
                                    onchange="toggleDiscountType()"
                                    class="w-4 h-4 text-orange-600">
                                <span class="text-gray-700 font-medium">Percentage Off</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="discount_type" value="fixed_amount"
                                    onchange="toggleDiscountType()"
                                    class="w-4 h-4 text-orange-600">
                                <span class="text-gray-700 font-medium">Fixed Amount (₵)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Percentage Input -->
                    <div id="percentage-input" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="discount_percentage" value="{{ old('discount_percentage') }}"
                                    min="1" max="100" placeholder="e.g., 25"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    @error('discount_percentage') is-invalid @enderror>
                                <span class="text-gray-700 font-medium">%</span>
                            </div>
                            @error('discount_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Fixed Amount Input -->
                    <div id="fixed-input" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Discount Amount <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-700 font-medium">₵</span>
                                <input type="number" name="discount_amount" value="{{ old('discount_amount') }}"
                                    min="0.01" step="0.01" placeholder="e.g., 50.00"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                    @error('discount_amount') is-invalid @enderror>
                            </div>
                            @error('discount_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Minimum Order Value -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Value (₵)</label>
                        <div class="flex items-center gap-2">
                            <span class="text-gray-700 font-medium">₵</span>
                            <input type="number" name="minimum_order_value" value="{{ old('minimum_order_value', 0) }}"
                                min="0" step="0.01" placeholder="0.00"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        </div>
                        <p class="text-gray-500 text-xs mt-1">Leave as 0 for no minimum</p>
                        @error('minimum_order_value')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Applicability Section -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Applicability</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Apply To <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="applicable_to" value="all" checked
                                    onchange="toggleServiceSelect()"
                                    class="w-4 h-4 text-orange-600">
                                <span class="text-gray-700">All Services</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="applicable_to" value="first_booking"
                                    onchange="toggleServiceSelect()"
                                    class="w-4 h-4 text-orange-600">
                                <span class="text-gray-700">First Booking Only</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="applicable_to" value="specific_service"
                                    onchange="toggleServiceSelect()"
                                    class="w-4 h-4 text-orange-600">
                                <span class="text-gray-700">Specific Service</span>
                            </label>
                        </div>
                        @error('applicable_to')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Select -->
                    <div id="service-select" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Service <span class="text-red-500">*</span></label>
                        <select name="service_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            <option value="">-- Choose a service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->service_id }}" {{ old('service_id') == $service->service_id ? 'selected' : '' }}>
                                    {{ $service->name }} (₵{{ number_format($service->price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Validity Section -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Validity & Limits</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Expires At -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valid Until <span class="text-red-500">*</span></label>
                        <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            required @error('expires_at') is-invalid @enderror>
                        @error('expires_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usage Limit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit (Optional)</label>
                        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}"
                            min="1" placeholder="Leave empty for unlimited usage"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            @error('usage_limit') is-invalid @enderror>
                        <p class="text-gray-500 text-xs mt-1">Maximum number of times this code can be used</p>
                        @error('usage_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">Status</h2>

                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="active" checked
                            class="w-4 h-4 text-orange-600">
                        <span class="text-gray-700 font-medium">Active (Start immediately)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="inactive"
                            class="w-4 h-4 text-orange-600">
                        <span class="text-gray-700 font-medium">Inactive (Start later)</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-between pt-8 border-t border-gray-200">
                <a href="{{ route('admin.promos.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg text-white font-medium transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <i class="fas fa-check mr-2"></i>Create Discount Code
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDiscountType() {
    const type = document.querySelector('input[name="discount_type"]:checked').value;
    const percentageInput = document.getElementById('percentage-input');
    const fixedInput = document.getElementById('fixed-input');

    if (type === 'percentage') {
        percentageInput.classList.remove('hidden');
        fixedInput.classList.add('hidden');
        document.querySelector('input[name="discount_percentage"]').required = true;
        document.querySelector('input[name="discount_amount"]').required = false;
    } else {
        percentageInput.classList.add('hidden');
        fixedInput.classList.remove('hidden');
        document.querySelector('input[name="discount_percentage"]').required = false;
        document.querySelector('input[name="discount_amount"]').required = true;
    }
}

function toggleServiceSelect() {
    const applicableTo = document.querySelector('input[name="applicable_to"]:checked').value;
    const serviceSelect = document.getElementById('service-select');

    if (applicableTo === 'specific_service') {
        serviceSelect.classList.remove('hidden');
        document.querySelector('select[name="service_id"]').required = true;
    } else {
        serviceSelect.classList.add('hidden');
        document.querySelector('select[name="service_id"]').required = false;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDiscountType();
    toggleServiceSelect();
});
</script>
@endsection
