<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Report') - Report</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|lato:300,400,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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

        @media print {
            body {
                margin: 0;
                padding: 20mm;
                background: white;
            }

            .no-print,
            .print-hide,
            button,
            form,
            .navbar,
            .nav,
            .sidebar,
            footer,
            .header,
            .admin-nav {
                display: none !important;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            tr, td, th {
                border: 1px solid #000;
                padding: 8px;
            }

            th {
                background-color: #f3f4f6;
                font-weight: bold;
            }

            .page-break {
                page-break-after: always;
            }
        }

        @page {
            margin: 20mm;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white">
    <!-- Content Only -->
    <div class="max-w-5xl mx-auto px-4 py-8">
        @yield('content')
    </div>

    <script>
        // Auto-print on load (optional)
        // window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
