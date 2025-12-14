@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">My Bookings</h1>
        <p class="text-gray-600 mt-2" style="color: #757575;">Manage and track all your appointments and bookings</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200 flex-wrap">
            <button class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition border-b-2 border-transparent active-tab"
                    data-filter="all"
                    style="color: #FF7F39; border-color: #FF7F39;">
                All Bookings
            </button>
            <button class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition border-b-2 border-transparent"
                    data-filter="upcoming">
                Upcoming
            </button>
            <button class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition border-b-2 border-transparent"
                    data-filter="today">
                Today
            </button>
            <button class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition border-b-2 border-transparent"
                    data-filter="completed">
                Completed
            </button>
            <button class="filter-tab px-6 py-4 font-medium text-gray-600 hover:text-gray-900 transition border-b-2 border-transparent"
                    data-filter="cancelled">
                Cancelled
            </button>
        </div>
    </div>

    <!-- Bookings Calendar View -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calendar Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-4">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Calendar</h3>
                </div>

                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-4">
                    <button id="prevMonth" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-chevron-left w-5 h-5"></i>
                    </button>
                    <h4 class="font-semibold text-gray-900 text-center min-w-32" id="monthYear"></h4>
                    <button id="nextMonth" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-chevron-right w-5 h-5"></i>
                    </button>
                </div>

                <!-- Days of Week -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center text-xs font-semibold text-gray-600 py-2">{{ $day }}</div>
                    @endforeach
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-1" id="calendarDays"></div>

                <!-- Selected Date Info -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Selected Date</p>
                    <p class="text-lg font-semibold text-gray-900" id="selectedDate">—</p>
                </div>

                <!-- Clear Filter Button -->
                <button id="clearDateFilter" class="w-full mt-4 px-4 py-2 rounded-lg font-medium text-sm transition"
                        style="border: 2px solid #FF7F39; color: #FF7F39; background: white;"
                        onmouseover="this.style.background='#FFF5EE'"
                        onmouseout="this.style.background='white'">
                    Clear Date Filter
                </button>
                </div>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="lg:col-span-2 space-y-4">
            @php
                $staffBookings = \App\Models\Booking::where('staff_id', auth()->user()->user_id)
                    ->with(['service', 'customer', 'payment'])
                    ->latest('date')
                    ->paginate(10);
            @endphp

            @forelse($staffBookings as $booking)
                @php
                    $filterClass = 'all ' . strtolower($booking->status);
                    if ($booking->date->isToday()) {
                        $filterClass .= ' today';
                    } elseif ($booking->date->isFuture()) {
                        $filterClass .= ' upcoming';
                    }
                @endphp
                <div class="booking-card bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition" data-filter="{{ $filterClass }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Booking Header -->
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $booking->service->name ?? 'Service' }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium" style="background: #FFF5EE; color: #FF7F39;">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <!-- Customer Info -->
                            <div class="flex items-center gap-2 mb-4">
                                <img src="https://ui-avatars.com/api/?name={{ $booking->customer->name }}&background=FF7F39&color=fff&size=32"
                                     alt="{{ $booking->customer->name }}" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->customer->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $booking->customer->email }}</p>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-600 uppercase tracking-wide">Date</p>
                                    <p class="font-semibold text-gray-900 booking-date">{{ $booking->date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase tracking-wide">Time</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->time->format('h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase tracking-wide">Duration</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->service->duration ?? 60 }} min</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 uppercase tracking-wide">Payment</p>
                                    <p class="font-semibold" style="color: {{ $booking->payment_status === 'paid' ? '#10b981' : '#f59e0b' }};">
                                        {{ ucfirst($booking->payment_status) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Notes if any -->
                            @if($booking->notes)
                                <div class="bg-gray-50 border border-gray-200 rounded p-3 mb-4">
                                    <p class="text-sm text-gray-700"><strong>Notes:</strong> {{ $booking->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="ml-4 flex gap-2">
                            <a href="{{ route('bookings.show', $booking) }}" class="px-4 py-2 rounded-lg font-medium text-sm transition" style="color: #FF7F39; background: #FFF5EE;" onmouseover="this.style.background='#FFEEE6'" onmouseout="this.style.background='#FFF5EE'">
                                View Details
                            </a>
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if($booking->status === 'completed')
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                ✓ Completed on {{ $booking->updated_at->format('M d, Y') }}
                            </span>
                        </div>
                    @elseif($booking->status === 'confirmed' && $booking->date->isPast())
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('bookings.complete', $booking) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 rounded-lg font-medium text-sm text-white transition" style="background: #10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    Mark as Completed
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <!-- Empty State -->
                <div class="empty-state bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mt-4">No bookings yet</h3>
                    <p class="text-gray-600 mt-2">You don't have any bookings scheduled. Check back soon!</p>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($staffBookings->count() > 0)
                <div class="mt-6">
                    {{ $staffBookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    let currentDate = new Date();
    let selectedDate = null;
    let bookingDates = [];

    // Get all booking dates
    function getBookingDates() {
        const cards = document.querySelectorAll('.booking-card');
        bookingDates = [];
        cards.forEach(card => {
            const dateText = card.querySelector('.booking-date');
            if (dateText) {
                const dateStr = dateText.textContent.trim();
                const dateObj = new Date(dateStr);
                if (!isNaN(dateObj)) {
                    bookingDates.push(dateObj.toDateString());
                }
            }
        });
    }

    // Render calendar
    function renderCalendar() {
        getBookingDates();

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Update month/year display
        document.getElementById('monthYear').textContent = currentDate.toLocaleString('default', {
            month: 'long',
            year: 'numeric'
        });

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        let html = '';

        // Previous month's days
        for (let i = firstDay - 1; i >= 0; i--) {
            const day = daysInPrevMonth - i;
            html += `<div class="text-center py-2 text-gray-400 text-sm cursor-not-allowed">${day}</div>`;
        }

        // Current month's days
        const today = new Date();
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateString = date.toDateString();
            const isToday = dateString === today.toDateString();
            const hasBooking = bookingDates.includes(dateString);
            const isSelected = selectedDate && dateString === selectedDate.toDateString();

            let classes = 'text-center py-2 text-sm font-medium rounded cursor-pointer transition ';
            let style = '';

            if (isSelected) {
                classes += 'text-white font-bold';
                style = 'background: #FF7F39;';
            } else if (hasBooking) {
                classes += 'text-white';
                style = 'background: #EA6C2F;';
            } else if (isToday) {
                classes += 'text-gray-900 border-2';
                style = 'border-color: #FF7F39;';
            } else {
                classes += 'text-gray-900 hover:bg-gray-100';
            }

            html += `<div class="calendar-day" data-date="${dateString}" style="${style}" class="${classes}">${day}</div>`;
        }

        // Next month's days
        const remainingDays = 42 - (firstDay + daysInMonth);
        for (let day = 1; day <= remainingDays; day++) {
            html += `<div class="text-center py-2 text-gray-400 text-sm cursor-not-allowed">${day}</div>`;
        }

        document.getElementById('calendarDays').innerHTML = html;

        // Add click handlers
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('click', function() {
                const dateString = this.dataset.date;
                selectedDate = new Date(dateString);

                // Update selected date display
                document.getElementById('selectedDate').textContent = selectedDate.toLocaleDateString('default', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric'
                });

                // Rerender calendar
                renderCalendar();

                // Filter bookings by date
                const cards = document.querySelectorAll('.booking-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const dateText = card.querySelector('.booking-date');
                    if (dateText) {
                        const cardDate = dateText.textContent.trim();
                        if (cardDate.includes(selectedDate.toLocaleDateString())) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });

                // Show/hide empty state
                const emptyState = document.querySelector('.empty-state');
                if (emptyState) {
                    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
                }
            });
        });
    }

    // Month navigation
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Clear date filter
    document.getElementById('clearDateFilter').addEventListener('click', () => {
        selectedDate = null;
        document.getElementById('selectedDate').textContent = '—';
        renderCalendar();

        // Show all bookings
        document.querySelectorAll('.booking-card').forEach(card => {
            card.style.display = 'block';
        });
    });

    // Initial render
    renderCalendar();

    // Filter tabs functionality
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();

            const filter = this.dataset.filter;

            // Update active tab styling
            document.querySelectorAll('.filter-tab').forEach(t => {
                t.classList.remove('active-tab');
                t.style.color = '';
                t.style.borderColor = '';
            });

            this.classList.add('active-tab');
            this.style.color = '#FF7F39';
            this.style.borderColor = '#FF7F39';

            // Filter booking cards
            const cards = document.querySelectorAll('.booking-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const filterClasses = card.dataset.filter.split(' ');

                if (filter === 'all' || filterClasses.includes(filter)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide empty state
            const emptyState = document.querySelector('.empty-state');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });
</script>

<style>
    .active-tab {
        color: #FF7F39 !important;
        border-color: #FF7F39 !important;
    }
</style>
@endsection
