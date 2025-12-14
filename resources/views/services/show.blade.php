@extends('layouts.app')

@section('title', isset($service) ? $service->name : 'Service Details')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Service Header -->
        <div class="bg-linear-to-r from-orange-500 to-orange-600" style="background: linear-gradient(135deg, #FF7F39 0%, #EA6C2F 100%); height: 300px; display: flex; align-items: center; justify-content: center;">
            <div class="text-center text-white">
                <i class="fas fa-spa text-6xl mb-4"></i>
                <h1 class="text-4xl font-bold" style="font-family: 'Playfair Display', serif;">Service Details</h1>
            </div>
        </div>

        <!-- Service Content -->
        <div class="p-8">
            @if(isset($service))
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2" style="font-family: 'Playfair Display', serif;">{{ $service->name }}</h2>
                    <p class="text-gray-600 text-lg">{{ $service->description ?? 'Professional service' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-600 text-sm mb-2">Price</p>
                        <p class="text-2xl font-bold text-gray-900">₵{{ number_format($service->price ?? 0) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="text-gray-600 text-sm mb-2">Duration</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $service->duration ?? 'N/A' }} mins</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">About This Service</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $service->description ?? 'This is a professional service offered by our experienced staff members.' }}
                    </p>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('services.index') }}" class="px-6 py-3 text-white rounded-lg font-medium transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Services
                    </a>
                    <a href="{{ route('bookings.index') }}" class="px-6 py-3 border-2 rounded-lg font-medium transition" style="border-color: #FF7F39; color: #FF7F39; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                        <i class="fas fa-calendar-plus mr-2"></i> Book Now
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Service Not Found</h3>
                    <p class="text-gray-600 mb-6">The service you're looking for doesn't exist or has been removed.</p>
                    <a href="{{ route('services.index') }}" class="inline-block px-6 py-2 text-white rounded-lg font-medium transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        Browse All Services
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
