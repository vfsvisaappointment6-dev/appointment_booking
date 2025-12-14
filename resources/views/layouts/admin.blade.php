<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|lato:300,400,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-orange: #FF7F39;
            --secondary-orange: #EA6C2F;
            --dark-black: #0A0A0A;
        }

        * {
            font-family: 'Lato', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        body {
            @apply bg-gray-50 text-gray-900;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50">
    <!-- Admin Navbar -->
    <nav class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 border-b border-orange-500 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3 sm:space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 sm:space-x-3 group flex-shrink-0">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:shadow-orange-500/50 transition">
                            <i class="fas fa-crown text-white text-lg"></i>
                        </div>
                        <div class="hidden xs:block">
                            <h1 class="text-lg sm:text-xl font-black text-white">3_AURA</h1>
                            <p class="text-xs text-orange-400 font-semibold -mt-1">Admin Hub</p>
                        </div>
                    </a>

                    <!-- Desktop Menu -->
                    <div class="hidden lg:flex space-x-1">
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-chart-pie mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-users mr-2"></i>Users
                        </a>
                        <a href="{{ route('admin.bookings.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.bookings.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-calendar mr-2"></i>Bookings
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.payments.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-credit-card mr-2"></i>Payments
                        </a>
                        <a href="{{ route('admin.promos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.promos.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-tags mr-2"></i>Discounts
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.services.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-concierge-bell mr-2"></i>Services
                        </a>
                        <a href="{{ route('admin.reports') }}" class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.reports') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-orange-400 hover:bg-gray-700' }}">
                            <i class="fas fa-chart-line mr-2"></i>Reports
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" type="button" class="lg:hidden text-gray-300 hover:text-white focus:outline-none p-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="hidden sm:block">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <img src="{{ Auth::guard('admin')->user()->profile_picture_url }}" alt="{{ Auth::guard('admin')->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-semibold text-white">{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-orange-400">Administrator</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition shadow-lg whitespace-nowrap">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i><span class="hidden sm:inline">Logout</span><span class="sm:hidden">Out</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden lg:hidden max-h-0 overflow-hidden transition-all duration-300">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-chart-pie mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-users mr-2"></i>Users
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.bookings.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-calendar mr-2"></i>Bookings
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.payments.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-credit-card mr-2"></i>Payments
                    </a>
                    <a href="{{ route('admin.promos.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.promos.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-tags mr-2"></i>Discounts
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.services.*') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-concierge-bell mr-2"></i>Services
                    </a>
                    <a href="{{ route('admin.reports') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.reports') ? 'text-white bg-orange-600' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                        <i class="fas fa-chart-line mr-2"></i>Reports
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (menuBtn) {
            menuBtn.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                if (menu.classList.contains('hidden')) {
                    menu.style.maxHeight = '0px';
                } else {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                }
            });
        }
    </script>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-8 sm:mt-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8 text-center text-gray-600 text-xs sm:text-sm">
            &copy; 2025 3_Aura Admin Dashboard. All rights reserved.
        </div>
    </footer>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
    <!-- Alert Utility Functions -->
    <script>
        // Success Alert
        function showSuccess(title, message = '') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                confirmButtonColor: '#FF7F39',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#10B981',
            });
        }

        // Error Alert
        function showError(title, message = '') {
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#FF7F39',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#EF4444',
            });
        }

        // Warning Alert
        function showWarning(title, message = '') {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                confirmButtonColor: '#FF7F39',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#F59E0B',
            });
        }

        // Info Alert
        function showInfo(title, message = '') {
            Swal.fire({
                icon: 'info',
                title: title,
                text: message,
                confirmButtonColor: '#FF7F39',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#3B82F6',
            });
        }

        // Confirmation Alert
        function showConfirm(title, message = '', onConfirm = () => {}) {
            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                showCancelButton: true,
                confirmButtonColor: '#FF7F39',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#F59E0B',
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        }

        // Logout Confirmation
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Logout',
                text: 'Are you sure you want to logout?',
                showCancelButton: true,
                confirmButtonColor: '#FF7F39',
                cancelButtonColor: '#0A0A0A',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                background: '#FFFFFF',
                color: '#0A0A0A',
                iconColor: '#FF7F39',
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }
    </script>
</body>
</html>
