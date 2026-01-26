<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'CV BDB') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-bdb.png') }}">

    <!-- Fonts - Inter (Premium, Modern Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        /* Monospace font for codes/numbers */
        .font-mono {
            font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        // Initialize dark mode before page renders
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-zinc-100 dark:bg-zinc-950 text-zinc-900 dark:text-white min-h-screen antialiased" x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true', 
          sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
          toggleDark() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              document.documentElement.classList.toggle('dark', this.darkMode);
          },
          toggleSidebar() {
              this.sidebarOpen = !this.sidebarOpen;
              localStorage.setItem('sidebarOpen', this.sidebarOpen);
          }
      }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 w-full" :class="{'lg:ml-64': sidebarOpen, 'lg:ml-16': !sidebarOpen}">
            <!-- Top Navigation -->
            @include('layouts.topnav')

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 overflow-x-auto">
                <!-- Toast Notifications -->
                <div x-data="{ 
                    toasts: [], 
                    show(message, type = 'success') {
                        const id = Date.now();
                        this.toasts.push({ id, message, type });
                        setTimeout(() => this.remove(id), 3000);
                    },
                    remove(id) {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }
                }" x-init="
                    @if(session('success')) show('{{ session('success') }}', 'success'); @endif
                    @if(session('error')) show('{{ session('error') }}', 'error'); @endif
                " class="fixed top-4 right-4 z-50 space-y-2" style="max-width: 320px;">
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-show="true" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-4" @click="remove(toast.id)"
                            class="px-4 py-2.5 rounded-lg shadow-lg cursor-pointer text-xs font-medium flex items-center gap-2"
                            :class="toast.type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'">
                            <i :data-lucide="toast.type === 'success' ? 'check-circle' : 'alert-circle'"
                                class="w-4 h-4"></i>
                            <span x-text="toast.message"></span>
                        </div>
                    </template>
                </div>

                @if($errors->any())
                    <div
                        class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>

    @stack('scripts')

    @livewireScripts
</body>

</html>