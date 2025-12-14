@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="text-gray-600 mt-2" style="color: #757575;">Manage your appointments, track earnings, and engage with clients</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-stat-card
            title="Today's Appointments"
            :value="$todayBookings->count()"
            color="orange"
            icon='<i class="fas fa-calendar-day w-6 h-6"></i>'
        />

        <x-stat-card
            title="Completed Services"
            :value="$completedBookings"
            color="green"
            icon='<i class="fas fa-check-circle w-6 h-6"></i>'
        />

        <x-stat-card
            title="Total Earnings"
            :value="'₵' . number_format($totalEarnings)"
            color="blue"
            icon='<i class="fas fa-wallet w-6 h-6"></i>'
        />

        <x-stat-card
            title="Unread Messages"
            :value="$unreadMessages"
            color="orange"
            icon='<i class="fas fa-comment-dots w-6 h-6"></i>'
        />
    </div>

    <!-- Alerts -->
    @if($todayBookings->count() > 0)
        <div class="mb-8">
            <x-alert type="info" title="Today's Schedule">
                You have {{ $todayBookings->count() }} appointment(s) scheduled for today. Make sure you're ready to provide excellent service!
            </x-alert>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Today's Appointments -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Today's Schedule</h2>
                    <a href="{{ route('bookings.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition" style="color: #FF7F39;">View Calendar</a>
                </div>

                <div class="divide-y">
                    @forelse($todayBookings as $booking)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        with <span class="font-medium">{{ $booking->customer->name ?? 'Customer' }}</span>
                                    </p>
                                    <div class="flex items-center gap-4 mt-3 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock w-4 h-4"></i>
                                            {{ $booking->time->format('h:i A') }}
                                        </span>
                                        @if($booking->payment_status === 'paid')
                                            <span class="inline-block px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded">
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 bg-yellow-50 text-yellow-700 text-xs font-medium rounded">
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <a href="{{ route('bookings.show', $booking) }}" class="text-sm font-medium px-3 py-1.5 rounded transition" style="color: #FF7F39; background: #FFF5EE;" onmouseover="this.style.background='#FFEEE6'" onmouseout="this.style.background='#FFF5EE'">
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 font-medium">No appointments scheduled for today</p>
                            <p class="text-gray-500 text-sm mt-1">You have a free day! Catch up on messages or update your profile.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Appointments (Next 7 days) -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Upcoming Week</h2>
                    <a href="{{ route('bookings.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition" style="color: #FF7F39;">View All</a>
                </div>

                <div class="divide-y">
                    @forelse($upcomingBookings->take(5) as $booking)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                                        <span class="text-xs font-medium px-2 py-1 rounded-full" style="background: #FFF5EE; color: #FF7F39;">
                                            {{ $booking->date->format('M d') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        with <span class="font-medium">{{ $booking->customer->name ?? 'Customer' }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-clock inline mr-1"></i>
                                        {{ $booking->time->format('h:i A') }}
                                    </p>
                                </div>
                                <div class="text-right ml-4">
                                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded">
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
                            <p class="text-gray-500 text-sm mt-1">Your calendar is clear for the next week</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Reviews from Customers -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Customer Reviews</h2>
                    <a href="#" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition" style="color: #FF7F39;">See All</a>
                </div>

                <div class="divide-y">
                    @forelse($recentReviews as $review)
                        <div class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-0.5">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->rating }} / 5</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ Str::limit($review->comment, 150) }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        <p class="text-xs text-gray-500">by <span class="font-medium">{{ $review->booking->customer->name ?? 'Customer' }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <p class="text-gray-600 font-medium">No reviews yet</p>
                            <p class="text-gray-500 text-sm mt-1">Complete more appointments to receive reviews from customers</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Staff Info Card -->
            @if($staffProfile)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Your Profile</h2>

                    <div class="text-center mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=FF7F39&color=fff&size=80"
                             alt="{{ auth()->user()->name }}" class="w-20 h-20 rounded-full mx-auto mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $staffProfile->specialty ?? 'Professional' }}</p>
                    </div>

                    <div class="space-y-3 py-4 border-t border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Rating</span>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="font-semibold text-gray-900">{{ $staffProfile->rating ?? '0.0' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Reviews</span>
                            <span class="font-semibold text-gray-900">{{ $recentReviews->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Services Completed</span>
                            <span class="font-semibold text-gray-900">{{ $completedBookings }}</span>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <a href="#" class="block w-full px-4 py-2 text-white text-center rounded-lg font-medium text-sm transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                            Edit Profile
                        </a>
                        <a href="#" class="block w-full px-4 py-2 border-2 rounded-lg font-medium text-center text-sm transition" style="border-color: #0A0A0A; color: #0A0A0A; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                            View Public Profile
                        </a>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('bookings.index') }}" class="block w-full px-4 py-3 text-white rounded-lg font-medium text-center transition" style="background: #FF7F39; color: white;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        View Bookings
                    </a>
                    <a href="{{ route('messages.index') }}" class="block w-full px-4 py-3 border-2 rounded-lg font-medium text-center transition" style="border-color: #FF7F39; color: #FF7F39; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                        Message Customers
                    </a>
                    <a href="#" class="block w-full px-4 py-3 border-2 rounded-lg font-medium text-center transition" style="border-color: #0A0A0A; color: #0A0A0A; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                        Availability
                    </a>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Recent Customers</h2>
                <div class="space-y-3">
                    @forelse($recentCustomers->take(5) as $customer)
                        <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name={{ $customer->name }}&background=FF7F39&color=fff"
                                 alt="{{ $customer->name }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $customer->email }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600 text-sm text-center py-4">No recent customers</p>
                    @endforelse
                </div>
            </div>

            <!-- Performance Tip -->
            <div class="rounded-lg border p-6" style="background: linear-gradient(135deg, #FFF5EE 0%, #FFE8DC 100%); border-color: #FFE0CC;">
                <h2 class="text-lg font-bold text-gray-900 mb-3" style="font-family: 'Playfair Display', serif;">Performance Tip</h2>
                <p class="text-sm text-gray-700 mb-4">Maintain a high rating and fast response times to increase your visibility and booking rate. Customers prefer professionals with excellent reviews!</p>
                <a href="#" class="text-sm font-medium" style="color: #FF7F39;">Learn more →</a>
            </div>
        </div>
    </div>
</div>
@endsection
