<aside class="flex flex-col w-full lg:w-64 bg-white border-r border-gray-200">
    <nav class="flex-1 px-4 py-4 lg:py-6 space-y-2">
        @php
            use Illuminate\Support\Facades\Auth;
            try {
                // Only get the web guard user for customer/staff pages
                $user = Auth::user();
            } catch (\Throwable $e) {
                $user = null;
            }
        @endphp
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
           style="{{ request()->routeIs('dashboard') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
            </svg>
            <span>Dashboard</span>
        </a>

        @if(($user->role ?? null) === 'staff')
            <!-- Staff Navigation -->
            <!-- Bookings (Staff) -->
            <a href="{{ route('staff.bookings') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('staff.bookings') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('staff.bookings') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                </svg>
                <span>My Schedule</span>
            </a>

            <!-- Messages (Staff) -->
            <a href="{{ route('messages.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('messages.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('messages.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Messages</span>
            </a>

            <!-- Earnings -->
            <a href="{{ route('staff.earnings') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('staff.earnings') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('staff.earnings') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                </svg>
                <span>Earnings</span>
            </a>

            <!-- Availability -->
            <a href="{{ route('staff.availability') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('staff.availability') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('staff.availability') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Availability</span>
            </a>
        @else
            <!-- Customer Navigation -->
            <!-- Bookings (Customer) -->
            <a href="{{ route('bookings.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('bookings.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('bookings.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>My Bookings</span>
            </a>

            <!-- Services -->
            <a href="{{ route('services.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('services.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('services.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-1.5a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2v-12a2 2 0 00-2-2h-1.5M6 4h12a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z" />
                </svg>
                <span>Browse Services</span>
            </a>

            <!-- Messages (Customer) -->
            <a href="{{ route('messages.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('messages.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('messages.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>Messages</span>
            </a>

            <!-- Reviews -->
            <a href="{{ route('reviews.index') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('reviews.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
               style="{{ request()->routeIs('reviews.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>My Reviews</span>
            </a>
        @endif

        <hr class="my-4">

        <!-- Settings -->
        <a href="{{ route('profile.edit') }}"
           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('profile.*') ? 'font-medium' : 'text-gray-700 hover:bg-gray-100' }}"
           style="{{ request()->routeIs('profile.*') ? 'background: #FFF5EE; color: #FF7F39;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Account Settings</span>
        </a>
    </nav>

    <!-- Admin Section -->
    @if(($user->is_admin ?? false))
        <div class="px-4 py-4 border-t border-gray-200">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Admin</h3>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-gray-700 hover:bg-red-50 hover:text-red-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" clip-rule="evenodd" />
                </svg>
                <span>Admin Panel</span>
            </a>
        </div>
    @endif
</aside>
