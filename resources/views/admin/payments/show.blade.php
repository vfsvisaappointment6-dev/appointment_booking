@extends('layouts.admin')

@section('title','Payment Details')

@section('content')
<div>
    <!-- Header -->
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.payments.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i><span>Back to Payments</span>
        </a>
    </div>

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Payment {{ substr($payment->payment_id, 0, 8) }}...</h1>
        <p class="text-gray-600 mt-1">Payment details and transaction history</p>
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
            <!-- Payment Information Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Payment Information</h2>

                <div class="space-y-6">
                    <!-- Amount -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Amount</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                    </div>

                    <!-- Booking Link -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Related Booking</p>
                        <p class="text-lg font-medium text-gray-900">
                            @if($payment->booking)
                                <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="text-orange-500 hover:text-orange-600 flex items-center space-x-2">
                                    <span>{{ substr($payment->booking_id, 0, 8) }}...</span>
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </p>
                    </div>

                    <!-- Payment Method -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                        <p class="text-lg font-medium text-gray-900">
                            <i class="fas fa-credit-card mr-2"></i>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Unknown')) }}
                        </p>
                    </div>

                    <!-- Transaction ID -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Transaction ID</p>
                        <p class="text-sm font-mono text-gray-900 break-all">{{ $payment->transaction_id ?? 'Not provided' }}</p>
                    </div>

                    <!-- Status -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Payment Status</p>
                        <span class="px-4 py-2 rounded-full text-sm font-medium inline-block {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : ($payment->status === 'refunded' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            <i class="fas {{ $payment->status === 'success' ? 'fa-check-circle' : 'fa-hourglass-half' }} mr-1"></i>{{ ucfirst($payment->status) }}
                        </span>
                    </div>

                    <!-- Timestamps -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaction Date</p>
                        <p class="text-sm text-gray-500">{{ $payment->created_at?->format('F d, Y \a\t g:i A') ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Update Payment Status</h2>

                <form method="POST" action="{{ route('admin.payments.update', $payment->payment_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('status') border-red-500 @else border-gray-300 @enderror">
                            <option value="">Select a status...</option>
                            <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="success" {{ old('status', $payment->status) === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ old('status', $payment->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ old('status', $payment->status) === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        @error('status')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar - Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4">Payment Details</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">Payment ID</p>
                        <p class="text-sm font-mono text-gray-900">{{ substr($payment->payment_id, 0, 8) }}...</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">Status</p>
                        <span class="text-xs font-semibold px-2 py-1 rounded {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">Amount</p>
                        <p class="text-sm font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    @if($payment->booking)
                        <a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="block w-full text-center px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 transition">
                            <i class="fas fa-bookmark mr-2"></i>View Booking
                        </a>
                    @endif

                    <form action="{{ route('admin.payments.destroy', $payment->payment_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment record? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 transition font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete Payment
                        </button>
                    </form>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-900"><strong>Note:</strong> Update payment status to reflect the current transaction state.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
