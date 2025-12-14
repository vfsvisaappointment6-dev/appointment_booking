@extends('layouts.printable')

@section('title', ucfirst($reportType) . ' Report')

@section('content')
<!-- Report Header -->
<div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ ucfirst($reportType) }} Report</h1>
    <p class="text-gray-600">
        <i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
    </p>
    <p class="text-sm text-gray-500 mt-2">Generated on {{ now()->format('M d, Y \a\t H:i A') }}</p>
</div>

<!-- Report Content -->
<div class="bg-white">
    @switch($reportType)
        @case('overview')
            @include('admin.reports.overview')
            @break
        @case('revenue')
            @include('admin.reports.revenue')
            @break
        @case('bookings')
            @include('admin.reports.bookings')
            @break
        @case('customers')
            @include('admin.reports.customers')
            @break
        @case('staff')
            @include('admin.reports.staff')
            @break
        @case('services')
            @include('admin.reports.services')
            @break
        @case('payments')
            @include('admin.reports.payments')
            @break
        @case('promos')
            @include('admin.reports.promos')
            @break
        @case('reviews')
            @include('admin.reports.reviews')
            @break
        @default
            @include('admin.reports.overview')
    @endswitch
</div>

<!-- Footer Note -->
<div class="mt-8 pt-6 border-t border-gray-300 text-center text-xs text-gray-500 no-print">
    <p>This is a computer-generated report. For official use only.</p>
</div>

<!-- Print Button (hidden in print) -->
<div class="no-print mt-6 text-center">
    <button onclick="window.print()" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
        <i class="fas fa-print mr-2"></i>Print / Save as PDF
    </button>
</div>
@endsection
