<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Appointment Booking') - Professional Booking</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|lato:300,400,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <!-- SweetAlert2 CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
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
            @apply bg-white text-gray-900;
            background: #FFFFFF;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Vite manifest not found. Build frontend assets with `npm install` + `npm run build` or run the dev server `npm run dev`. --}}
    @endif
</head>
<body>
    <!-- Navigation -->
    @include('components.navbar')

    <!-- Main Content (stack on mobile, row on large screens) -->
    <div class="flex flex-col lg:flex-row">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Page Content -->
        <main class="flex-1">
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
    <script>
        // Flash message alerts
        @if (session('welcome'))
            Swal.fire({
                icon: 'success',
                title: 'Welcome!',
                text: "{{ session('welcome') }}",
                timer: 3000,
                timerProgressBar: true,
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
        @endif

        @if (session('login_success'))
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: "{{ session('login_success') }}",
                timer: 2500,
                timerProgressBar: true,
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
        @endif

        @if (session('logout_success'))
            Swal.fire({
                icon: 'info',
                title: 'Logged Out',
                text: "{{ session('logout_success') }}",
                timer: 2000,
                timerProgressBar: true,
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                background: '#FFFFFF',
                color: '#0A0A0A',
                confirmButtonColor: '#FF7F39',
                iconColor: '#FF7F39',
            });
        @endif

        // Logout confirmation
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

        // Prevent back navigation to login/register pages
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, null, window.location.href);
        };
    </script>
    @stack('scripts')
</body>
</html>
