@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">My Bookings</h1>
        <p class="text-gray-600 mt-2">Manage and track all your appointments</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200">
            <button onclick="filterBookings('all')" class="filter-tab px-6 py-4 font-medium transition active" data-filter="all" style="color: #FF7F39; border-bottom: 2px solid #FF7F39;">
                All Bookings
            </button>
            <button onclick="filterBookings('upcoming')" class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition" data-filter="upcoming">
                Upcoming
            </button>
            <button onclick="filterBookings('completed')" class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition" data-filter="completed">
                Completed
            </button>
            <button onclick="filterBookings('cancelled')" class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition" data-filter="cancelled">
                Cancelled
            </button>
        </div>
    </div>

    <!-- Bookings Grid -->
    <div id="bookings-container" class="space-y-4">
        @forelse(auth()->user()->bookings()->with(['service', 'staff', 'payment'])->latest('date')->get() as $booking)
            <div class="booking-item" data-status="{{ $booking->status }}" data-date="{{ $booking->date }}">
                <x-booking-card :booking="$booking" />
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mt-4">No bookings yet</h3>
                <p class="text-gray-600 mt-2">Start by booking your first service</p>
                <a href="{{ route('services.index') }}" class="inline-block mt-6 px-6 py-3 text-white rounded-lg font-medium transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    Browse Services
                </a>
            </div>
        @endforelse
    </div>

    <script>
    function filterBookings(filter) {
        const bookingItems = document.querySelectorAll('.booking-item');
        const filterTabs = document.querySelectorAll('.filter-tab');
        const emptyState = document.querySelector('[data-empty-state]');
        let visibleCount = 0;
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Update tab styling
        filterTabs.forEach(tab => {
            if (tab.dataset.filter === filter) {
                tab.classList.add('active');
                tab.style.color = '#FF7F39';
                tab.style.borderBottom = '2px solid #FF7F39';
            } else {
                tab.classList.remove('active');
                tab.style.color = '#4B5563';
                tab.style.borderBottom = 'none';
            }
        });

        // Filter bookings
        bookingItems.forEach(item => {
            const status = item.dataset.status;
            const bookingDate = new Date(item.dataset.date);
            let show = false;

            if (filter === 'all') {
                show = true;
            } else if (filter === 'upcoming') {
                show = (status === 'confirmed' || status === 'pending') && bookingDate >= today;
            } else if (filter === 'completed') {
                show = status === 'completed';
            } else if (filter === 'cancelled') {
                show = status === 'cancelled';
            }

            if (show) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show empty state if no bookings match filter
        if (visibleCount === 0 && bookingItems.length > 0) {
            if (!emptyState) {
                const container = document.getElementById('bookings-container');
                const empty = document.createElement('div');
                empty.setAttribute('data-empty-state', 'true');
                empty.className = 'bg-white rounded-lg border border-gray-200 p-12 text-center';
                empty.innerHTML = `
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mt-4">No ${filter} bookings</h3>
                    <p class="text-gray-600 mt-2">You don't have any ${filter} appointments</p>
                `;
                container.appendChild(empty);
            }
        } else {
            const empty = document.querySelector('[data-empty-state]');
            if (empty) {
                empty.remove();
            }
        }
    }
</script>
@endsection
