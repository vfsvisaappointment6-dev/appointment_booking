@extends('layouts.admin')

@section('title','Booking Details')

@section('content')
<div>
    <!-- Header -->
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i><span>Back to Bookings</span>
        </a>
    </div>

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Booking {{ substr($booking->booking_id, 0, 8) }}...</h1>
        <p class="text-gray-600 mt-1">View and manage booking details</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Booking Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Booking Information</h2>

                <div class="space-y-6">
                    <!-- Customer -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Customer</p>
                        <p class="text-lg font-medium text-gray-900">{{ optional($booking->customer)->name ?? 'N/A' }}</p>
                        @if($booking->customer)
                            <p class="text-sm text-gray-500">{{ $booking->customer->email }}</p>
                        @endif
                    </div>

                    <!-- Staff -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Service Provider</p>
                        <p class="text-lg font-medium text-gray-900">{{ optional($booking->staff)->name ?? 'N/A' }}</p>
                        @if($booking->staff)
                            <p class="text-sm text-gray-500">Staff ID: {{ $booking->staff->user_id }}</p>
                        @endif
                    </div>

                    <!-- Service -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Service</p>
                        <p class="text-lg font-medium text-gray-900">{{ optional($booking->service)->name ?? 'N/A' }}</p>
                        @if($booking->service)
                            <p class="text-sm text-gray-500">Duration: {{ $booking->service->duration ?? 'N/A' }} minutes</p>
                        @endif
                    </div>

                    <!-- Date & Time -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Scheduled Date & Time</p>
                        <p class="text-lg font-medium text-gray-900">
                            <i class="fas fa-calendar mr-2"></i>{{ $booking->date ?? 'N/A' }}
                            @if($booking->time)
                                <i class="fas fa-clock mr-2"></i>{{ $booking->time }}
                            @endif
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Current Status</p>
                        <span class="px-4 py-2 rounded-full text-sm font-medium inline-block {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : ($booking->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            <i class="fas fa-circle-check mr-1"></i>{{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <!-- Created Date -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Booked On</p>
                        <p class="text-sm text-gray-500">{{ $booking->created_at?->format('M d, Y \a\t g:i A') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Update Booking Status</h2>

                <form method="POST" action="{{ route('admin.bookings.update', $booking->booking_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
                        <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('status') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Select a status...</option>
                            <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Optional: Reschedule Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Date (Optional)</label>
                            <input type="date" name="date" value="{{ old('date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @error('date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Time (Optional)</label>
                            <input type="time" name="time" value="{{ old('time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                            @error('time')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Update Booking
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar - Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($booking->customer)
                        <a href="{{ route('admin.users.show', $booking->customer->user_id) }}" class="block w-full text-center px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 transition">
                            <i class="fas fa-user mr-2"></i>View Customer
                        </a>
                    @endif

                    @if($booking->staff)
                        <a href="{{ route('admin.users.show', $booking->staff->user_id) }}" class="block w-full text-center px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 transition">
                            <i class="fas fa-user-tie mr-2"></i>View Staff
                        </a>
                    @endif

                    <form action="{{ route('admin.bookings.destroy', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 transition font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete Booking
                        </button>
                    </form>
                </div>

                <!-- Booking Status Info -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-900"><strong>Tip:</strong> Use the status dropdown to manage this booking's lifecycle throughout its progress.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
