<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ERP Pabrik Cat') }} - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bdb.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js for guest layout (not using Livewire) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body
    class="font-sans antialiased bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-950 text-zinc-900 dark:text-zinc-100 transition-colors duration-200">
    <div class="min-h-screen flex">
        <!-- Left side - Branding -->
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-700 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <!-- Logo -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-bdb.png') }}" alt="CV BDB" class="w-16 h-16 object-contain">
                    <span class="text-2xl font-bold text-white">CV BDB</span>
                </div>
            </div>

            <!-- Content -->
            <div class="relative z-10 space-y-6">
                <h1 class="text-4xl font-bold text-white leading-tight">
                    Sistem ERP<br>Pabrik Cat
                </h1>
                <p class="text-blue-100 text-lg max-w-md">
                    Kelola produksi, inventory, dan penjualan cat Anda dengan mudah dan efisien dalam satu platform
                    terintegrasi.
                </p>
                <div class="flex items-center gap-6 text-blue-100">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>SPK Management</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Inventory Control</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-blue-100 text-sm">
                &copy; {{ date('Y') }} CV BDB. All rights reserved.
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12">
            <!-- Dark mode toggle -->
            <div class="absolute top-6 right-6">
                <button @click="darkMode = !darkMode"
                    class="p-2.5 rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                    <i data-lucide="sun" class="w-5 h-5 hidden dark:block text-yellow-500"></i>
                    <i data-lucide="moon" class="w-5 h-5 block dark:hidden text-zinc-600"></i>
                </button>
            </div>

            <!-- Mobile logo -->
            <div class="lg:hidden mb-8 text-center">
                <div class="inline-flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo-bdb.png') }}" alt="CV BDB" class="w-16 h-16 object-contain">
                    <span class="text-2xl font-bold text-zinc-900 dark:text-white">CV BDB</span>
                </div>
                <p class="text-zinc-500 dark:text-zinc-400">Sistem ERP Pabrik Cat</p>
            </div>

            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>

</html>