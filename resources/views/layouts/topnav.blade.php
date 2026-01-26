<!-- Top Navigation -->
<header
    class="sticky top-0 z-30 h-14 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between px-4 lg:px-6">
    <!-- Left: Mobile menu + Page Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg">
            <i data-lucide="menu" class="w-5 h-5 text-zinc-600 dark:text-zinc-400"></i>
        </button>
        <h1 class="text-base font-semibold text-zinc-900 dark:text-white truncate">@yield('title', 'Dashboard')</h1>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-1">
        <!-- Real-time Clock -->
        <div x-data="{
            time: '',
            date: '',
            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
            },
            updateClock() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                this.date = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            }
        }" class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-lg mr-2">
            <i data-lucide="calendar" class="w-4 h-4 text-zinc-500"></i>
            <span class="text-xs text-zinc-600 dark:text-zinc-400" x-text="date"></span>
            <span class="text-xs font-mono font-medium text-zinc-900 dark:text-white" x-text="time"></span>
        </div>
        <!-- Dark Mode Toggle -->
        <button @click="toggleDark()" class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg"
            title="Toggle Dark Mode">
            <i data-lucide="sun" class="w-5 h-5 text-zinc-600 dark:text-zinc-400" x-show="darkMode" x-cloak></i>
            <i data-lucide="moon" class="w-5 h-5 text-zinc-600 dark:text-zinc-400" x-show="!darkMode"></i>
        </button>

        <!-- Notifications -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg">
                <i data-lucide="bell" class="w-5 h-5 text-zinc-600 dark:text-zinc-400"></i>
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                @endif
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-72 bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="p-3 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                    <span class="font-medium text-sm text-zinc-900 dark:text-white">Notifikasi</span>
                    @if($unreadCount > 0)
                        <span class="text-xs text-zinc-500">{{ $unreadCount }} baru</span>
                    @endif
                </div>
                <div class="max-h-48 overflow-y-auto">
                    @forelse(auth()->user()->userNotifications()->latest()->take(5)->get() as $notification)
                        <div
                            class="p-3 border-b border-zinc-100 dark:border-zinc-800 last:border-0 {{ !$notification->is_read ? 'bg-blue-50 dark:bg-blue-900/10' : '' }}">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $notification->title }}</p>
                            <p class="text-xs text-zinc-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-zinc-500 text-sm">Tidak ada notifikasi</div>
                    @endforelse
                </div>
                <a href="{{ route('notifications.index') }}"
                    class="block p-2 text-center text-xs text-blue-600 dark:text-blue-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-800">
                    Lihat Semua
                </a>
            </div>
        </div>

        <!-- User Menu -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg">
                <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-400 hidden sm:block"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-2 w-44 bg-white dark:bg-zinc-900 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="p-3 border-b border-zinc-200 dark:border-zinc-800">
                    <p class="font-medium text-sm text-zinc-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-zinc-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>