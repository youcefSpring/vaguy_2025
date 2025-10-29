<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full bg-gray-50" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Client dashboard for {{ config('app.name') }}">
    <meta name="keywords" content="dashboard, client, management">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logoIcon/favicon.png') }}" sizes="16x16">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="h-full">
    <div class="min-h-full">
        <p>This file is deprecated. Please use the proper Blade layouts in layouts/dashboard.blade.php</p>
    </div>

    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        window.routePaths = {
            transactions: "{{ localized_route('user.transactions') }}"
        };
    </script>
</body>
</html>