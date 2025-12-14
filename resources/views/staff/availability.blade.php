@extends('layouts.app')

@section('title', 'Availability Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Availability Management</h1>
        <p class="text-gray-600 mt-2" style="color: #757575;">Set your working hours and manage your availability</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Status</h3>

                <form method="POST" action="{{ route('staff.availability.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                        <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="active" {{ auth()->user()->staffProfile?->status === 'active' ? 'selected' : '' }}>Active & Available</option>
                            <option value="inactive" {{ auth()->user()->staffProfile?->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="on-leave" {{ auth()->user()->staffProfile?->status === 'on-leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="busy" {{ auth()->user()->staffProfile?->status === 'busy' ? 'selected' : '' }}>Busy</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium text-white transition"
                            style="background: #FF7F39;"
                            onmouseover="this.style.background='#EA6C2F'"
                            onmouseout="this.style.background='#FF7F39'">
                        Update Status
                    </button>
                </form>

                <hr class="my-6">

                <h3 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Quick Actions</h3>
                <div class="space-y-2">
                    <button type="button" class="w-full px-4 py-2 rounded-lg font-medium text-sm transition"
                            style="border: 2px solid #FF7F39; color: #FF7F39; background: white;"
                            onmouseover="this.style.background='#FFF5EE'"
                            onmouseout="this.style.background='white'"
                            onclick="openBlockTimeModal()">
                        Block Time
                    </button>
                    <button type="button" class="w-full px-4 py-2 rounded-lg font-medium text-sm transition"
                            style="border: 2px solid #0A0A0A; color: #0A0A0A; background: white;"
                            onmouseover="this.style.background='#FFF5EE'"
                            onmouseout="this.style.background='white'"
                            onclick="toggleCalendarView()">
                        View Calendar
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Weekly Schedule -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Weekly Schedule</h2>

                <form method="POST" action="{{ route('staff.availability.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="schedule">

                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $dayKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    @endphp

                    @foreach($days as $index => $day)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-gray-900">{{ $day }}</h3>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="schedule[{{ $dayKeys[$index] }}][is_working]" value="1" class="w-4 h-4 rounded" checked style="border-color: #FF7F39; accent-color: #FF7F39;">
                                    <span class="text-sm text-gray-600">Working Day</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">Start Time</label>
                                    <input type="time" name="schedule[{{ $dayKeys[$index] }}][start]" value="09:00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-1">End Time</label>
                                    <input type="time" name="schedule[{{ $dayKeys[$index] }}][end]" value="18:00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="schedule[{{ $dayKeys[$index] }}][has_break]" value="1" class="w-4 h-4 rounded" style="border-color: #FF7F39; accent-color: #FF7F39;">
                                    <span class="text-sm text-gray-600">Break Time (12:00 - 13:00)</span>
                                </label>
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>

            <!-- Block Schedule -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Blocked Times</h2>

                <p class="text-gray-600 mb-6">Block specific dates and times when you're not available for bookings.</p>

                <form method="POST" action="{{ route('staff.availability.update') }}" class="space-y-4 mb-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="action" value="block_time">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="block_start_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="block_end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason (Optional)</label>
                        <input type="text" name="block_reason" placeholder="e.g., Vacation, Personal Leave, Conference"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition"
                            style="background: #FF7F39;"
                            onmouseover="this.style.background='#EA6C2F'"
                            onmouseout="this.style.background='#FF7F39'">
                        Block Time
                    </button>
                </form>

                <!-- Blocked Times List -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900">Your Blocked Times</h3>
                    @php
                        $blockedTimes = auth()->user()->staffProfile?->blockedTimes()->get() ?? [];
                    @endphp

                    @forelse($blockedTimes as $blocked)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $blocked->start_date->format('M d') }} - {{ $blocked->end_date->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $blocked->reason ?? 'No reason provided' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Added {{ $blocked->created_at->diffForHumans() }}</p>
                            </div>
                            <form method="POST" action="{{ route('staff.availability.delete-blocked-time', $blocked->blocked_time_id) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm" onclick="return confirm('Are you sure you want to remove this blocked time?')">
                                    Remove
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm py-4">No blocked times yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Block Time Modal -->
<div id="blockTimeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Block Time</h3>

        <form method="POST" action="{{ route('staff.availability.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="action" value="block_time">

            <div>
                <label for="blockDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" id="blockDate" name="block_date" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="blockStartTime" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                <input type="time" id="blockStartTime" name="block_start_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="blockEndTime" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                <input type="time" id="blockEndTime" name="block_end_time" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="blockReason" class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                <input type="text" id="blockReason" name="block_reason" placeholder="e.g., Personal appointment"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-2 rounded-lg font-medium text-white transition"
                        style="background: #FF7F39;"
                        onmouseover="this.style.background='#EA6C2F'"
                        onmouseout="this.style.background='#FF7F39'">
                    Block Time
                </button>
                <button type="button" class="flex-1 px-4 py-2 rounded-lg font-medium text-gray-700 border border-gray-300 transition"
                        onclick="closeBlockTimeModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Calendar Modal -->
<div id="calendarModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Calendar View</h3>
            <button type="button" onclick="toggleCalendarView()" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>

        <div id="calendar" class="space-y-4">
            <div class="flex items-center justify-between mb-6">
                <button type="button" onclick="previousMonth()" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">← Prev</button>
                <h4 class="text-lg font-semibold" id="currentMonth"></h4>
                <button type="button" onclick="nextMonth()" class="px-3 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">Next →</button>
            </div>

            <div class="grid grid-cols-7 gap-2">
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Sun</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Mon</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Tue</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Wed</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Thu</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Fri</div>
                <div class="text-center font-semibold text-gray-600 text-sm py-2">Sat</div>
                <div id="calendarDays" class="col-span-7 grid grid-cols-7 gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
let currentDate = new Date();

function openBlockTimeModal() {
    document.getElementById('blockTimeModal').classList.remove('hidden');
}

function closeBlockTimeModal() {
    document.getElementById('blockTimeModal').classList.add('hidden');
}

function toggleCalendarView() {
    const modal = document.getElementById('calendarModal');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        renderCalendar();
    } else {
        modal.classList.add('hidden');
    }
}

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    document.getElementById('currentMonth').textContent = currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();

    let html = '';

    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        html += `<div class="text-center py-2 text-gray-400 text-sm">${daysInPrevMonth - i}</div>`;
    }

    // Current month days
    for (let i = 1; i <= daysInMonth; i++) {
        const isToday = new Date(year, month, i).toDateString() === new Date().toDateString();
        html += `<div class="text-center py-2 rounded ${isToday ? 'bg-orange-100 font-bold text-orange-600' : 'hover:bg-gray-100'} cursor-pointer text-sm">${i}</div>`;
    }

    // Next month days
    const totalCells = firstDay + daysInMonth;
    const remainingCells = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
    for (let i = 1; i <= remainingCells; i++) {
        html += `<div class="text-center py-2 text-gray-400 text-sm">${i}</div>`;
    }

    document.getElementById('calendarDays').innerHTML = html;
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

// Close modals when clicking outside
document.getElementById('blockTimeModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeBlockTimeModal();
});

document.getElementById('calendarModal')?.addEventListener('click', function(e) {
    if (e.target === this) toggleCalendarView();
});
</script>
@endsection

