@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Welcome Section -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Welcome back, <span class="hidden sm:inline">{{ auth()->user()->name }}</span><span class="sm:hidden">{{ explode(' ', auth()->user()->name)[0] }}</span></h1>
        <p class="text-sm sm:text-base text-gray-600 mt-2" style="color: #757575;">Manage your appointments and track your bookings</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <x-stat-card
            title="Upcoming Bookings"
            :value="auth()->user()->bookings()->where('date', '>=', now()->toDateString())->where('status', 'confirmed')->count()"
            color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        />

        <x-stat-card
            title="Completed Services"
            :value="auth()->user()->bookings()->where('status', 'completed')->count()"
            color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />

        <x-stat-card
            title="Pending Payments"
            :value="auth()->user()->bookings()->where('payment_status', 'unpaid')->count()"
            color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10M7 15H5.5a2.5 2.5 0 110-5H7m10 0h1.5a2.5 2.5 0 110 5H17" /></svg>'
        />

        <x-stat-card
            title="Unread Messages"
            :value="auth()->user()->receivedChatMessages()->where('seen', false)->count()"
            color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>'
        />
    </div>

    <!-- Alerts -->
    @if(auth()->user()->bookings()->where('date', '=', now()->toDateString())->where('status', 'confirmed')->count() > 0)
        <div class="mb-8">
            <x-alert type="info" title="Reminder">
                You have {{ auth()->user()->bookings()->where('date', '=', now()->toDateString())->where('status', 'confirmed')->count() }} appointment(s) scheduled for today. Make sure you're prepared!
            </x-alert>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Upcoming Appointments</h2>
                    <a href="{{ route('bookings.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition" style="color: #FF7F39;">View All</a>
                </div>

                <div class="divide-y">
                    @forelse(auth()->user()->bookings()
                        ->with(['service', 'staff'])
                        ->where('date', '>=', now()->toDateString())
                        ->where('status', 'confirmed')
                        ->orderBy('date')
                        ->orderBy('time')
                        ->take(3)
                        ->get() as $booking)

                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        with <span class="font-medium">{{ $booking->staff->name ?? 'Staff' }}</span>
                                    </p>
                                    <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" /></svg>
                                            {{ $booking->date->format('M d') }}, {{ $booking->time->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="font-semibold text-gray-900">{{ ucfirst($booking->payment_status) }}</p>
                                    <span class="inline-block mt-2 px-2 py-1 text-xs font-medium rounded" style="background: #FFF5EE; color: #FF7F39;">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium">No upcoming appointments</p>
                            <p class="text-gray-500 text-sm mt-1">Book a service to get started</p>
                            <a href="{{ route('services.index') }}" class="inline-block mt-4 px-4 py-2 text-white rounded-lg font-medium text-sm transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                                Browse Services
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Your Recent Reviews</h2>
                    <a href="{{ route('reviews.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition" style="color: #FF7F39;">See All</a>
                </div>

                <div class="divide-y">
                    @forelse(auth()->user()->reviews()
                        ->with(['booking.service', 'staff'])
                        ->latest()
                        ->take(3)
                        ->get() as $review)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">{{ $review->booking->service->name }}</h3>
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ Str::limit($review->comment, 150) }}</p>
                                    <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <p class="text-gray-600 font-medium">No reviews yet</p>
                            <p class="text-gray-500 text-sm mt-1">Complete a service to leave a review</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('services.index') }}" class="block w-full px-4 py-3 text-white rounded-lg font-medium text-center transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        Book an Appointment
                    </a>
                    <a href="{{ route('bookings.index') }}" class="block w-full px-4 py-3 border-2 rounded-lg font-medium text-center transition" style="border-color: #FF7F39; color: #FF7F39; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                        View All Bookings
                    </a>
                    <a href="{{ route('messages.index') }}" class="block w-full px-4 py-3 border-2 rounded-lg font-medium text-center transition" style="border-color: #0A0A0A; color: #0A0A0A; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                        Message Staff
                    </a>
                </div>
            </div>

            <!-- Staff Recommendations -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Featured Staff</h2>
                <div class="space-y-4">
                    @forelse(\App\Models\StaffProfile::with(['user', 'reviews'])
                        ->where('status', 'active')
                        ->withAvg('reviews', 'rating')
                        ->orderByDesc('reviews_avg_rating')
                        ->take(3)
                        ->get() as $staff)
                        <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name={{ $staff->user->name }}&background=FF7F39&color=fff"
                                 alt="{{ $staff->user->name }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $staff->user->name }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3 h-3 {{ $i < floor($staff->reviews_avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="text-xs text-gray-600 ml-1">({{ $staff->reviews->count() }})</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-sm text-center py-4">No featured staff available</p>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Holidays -->
            <div class="rounded-lg border p-6" style="background: linear-gradient(135deg, #FFF5EE 0%, #FFE8DC 100%); border-color: #FFE0CC;">
                <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Pro Tip</h2>
                <p class="text-sm text-gray-700">Book services in advance to secure your preferred time slot. Premium members get priority scheduling!</p>
                <button class="mt-4 w-full px-4 py-2 text-white rounded-lg font-medium text-sm transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    Upgrade Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
