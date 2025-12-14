@extends('layouts.admin')

@section('title', 'Reports - Admin')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Reports & Analytics</h1>
        <p class="text-gray-600 mt-2">Comprehensive business intelligence and performance metrics</p>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex items-end gap-4 flex-wrap">
            <div class="flex-1 min-w-fit">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
            <div class="flex-1 min-w-fit">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Report Type Tabs -->
    <div class="report-tabs flex gap-2 mb-8 overflow-x-auto pb-2">
        @php
            $reportTypes = [
                'overview' => ['label' => 'Overview', 'icon' => 'fa-chart-pie'],
                'revenue' => ['label' => 'Revenue', 'icon' => 'fa-money-bill-wave'],
                'bookings' => ['label' => 'Bookings', 'icon' => 'fa-calendar'],
                'customers' => ['label' => 'Customers', 'icon' => 'fa-users'],
                'staff' => ['label' => 'Staff', 'icon' => 'fa-user-tie'],
                'services' => ['label' => 'Services', 'icon' => 'fa-concierge-bell'],
                'payments' => ['label' => 'Payments', 'icon' => 'fa-credit-card'],
                'promos' => ['label' => 'Promotions', 'icon' => 'fa-tags'],
                'reviews' => ['label' => 'Reviews', 'icon' => 'fa-star'],
            ];
        @endphp

        @foreach($reportTypes as $type => $info)
            <a href="{{ route('admin.reports', ['type' => $type, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
               class="px-4 py-3 rounded-lg font-medium transition whitespace-nowrap {{ $reportType === $type ? 'text-white' : 'text-gray-700 border border-gray-300 hover:border-orange-500' }}"
               style="{{ $reportType === $type ? 'background: #FF7F39;' : 'background: #FFFFFF;' }}">
                <i class="fas {{ $info['icon'] }} mr-2"></i>{{ $info['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Print Header (visible only when printing) -->
    <div class="print-header hidden">
        <div class="text-center mb-8 pb-6 border-b-2 border-gray-300">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                @php
                    $typeLabels = [
                        'overview' => 'Overview',
                        'revenue' => 'Revenue',
                        'bookings' => 'Bookings',
                        'customers' => 'Customers',
                        'staff' => 'Staff',
                        'services' => 'Services',
                        'payments' => 'Payments',
                        'promos' => 'Promotions',
                        'reviews' => 'Reviews',
                    ];
                @endphp
                {{ $typeLabels[$reportType] ?? ucfirst($reportType) }} Report
            </h1>
            <p class="text-gray-600">
                <i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </p>
            <p class="text-sm text-gray-500 mt-2">Generated on {{ now()->format('M d, Y \a\t H:i A') }}</p>
        </div>
    </div>

    <!-- Report Content -->
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

    <!-- Export Options -->
    <div class="mt-12 bg-white rounded-lg shadow-sm border border-gray-200 p-6 no-print">
        <h3 class="font-bold text-gray-900 mb-4">Export Report</h3>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('admin.reports.export-csv', ['type' => $reportType, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-file-csv text-blue-500 mr-2"></i>Export as CSV
            </a>
            <a href="{{ route('admin.reports.print', ['type' => $reportType, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" target="_blank" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                <i class="fas fa-file-pdf text-white mr-2"></i>Export as PDF
            </a>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" onclick="window.print()">
                <i class="fas fa-print text-gray-600 mr-2"></i>Print Report
            </button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition" onclick="showInfo('Export', 'Excel export feature coming soon')">
                <i class="fas fa-file-excel text-green-500 mr-2"></i>Export as Excel
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: white;
            color: black;
            font-size: 12pt;
        }

        /* Show print header */
        .print-header {
            display: block !important;
        }

        /* Hide all unnecessary elements */
        .no-print,
        .navbar,
        .nav,
        .sidebar,
        footer,
        .admin-nav,
        nav,
        button,
        form,
        .report-tabs,
        .mb-8:first-of-type,
        .rounded-lg:first-of-type,
        [class*="filter"],
        [class*="export"] {
            display: none !important;
        }

        .max-w-7xl {
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .max-w-7xl > div:first-child {
            display: block;
            margin-bottom: 20pt;
            padding-bottom: 10pt;
            border-bottom: 2px solid #333;
        }

        .max-w-7xl > div:first-child h1 {
            font-size: 28pt;
            margin-bottom: 5pt;
        }

        .max-w-7xl > div:first-child p {
            font-size: 11pt;
            color: #666;
        }

        div {
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            margin: 15pt 0;
        }

        th, td {
            border: 1px solid #333;
            padding: 8pt;
            text-align: left;
            font-size: 11pt;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        h1 {
            font-size: 24pt;
            margin: 15pt 0 10pt 0;
            page-break-after: avoid;
        }

        h2 {
            font-size: 16pt;
            margin: 15pt 0 10pt 0;
            page-break-after: avoid;
        }

        h3 {
            font-size: 13pt;
            margin: 10pt 0 8pt 0;
            page-break-after: avoid;
        }

        p {
            margin: 5pt 0;
            font-size: 11pt;
        }

        .stat-card, .card, .bg-white {
            page-break-inside: avoid;
            border: 1px solid #ccc;
            padding: 10pt;
            margin: 10pt 0;
        }

        .page-break {
            page-break-after: always;
        }
    }

    @page {
        margin: 20mm;
    }
</style>
@endsection
