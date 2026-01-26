<!-- Top Navigation -->
<header
    class="sticky top-0 z-30 h-16 bg-blue-600 dark:bg-zinc-900 border-b border-blue-500 dark:border-slate-800 shadow-md flex items-center justify-between px-4 lg:px-6 transition-colors duration-200">
    <!-- Left: Mobile menu + Page Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 hover:bg-blue-500 dark:hover:bg-slate-800 rounded-lg text-white dark:text-slate-400">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        <h1 class="text-lg font-bold text-white dark:text-white truncate tracking-tight">@yield('title', 'Dashboard')</h1>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-2">
        <!-- Real-time Clock -->
        <div x-data="{
            time: '00:00:00',
            date: '...',
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
        }" class="flex items-center gap-3 px-4 py-1.5 bg-blue-800/40 dark:bg-slate-800 rounded-lg border border-white/20 dark:border-slate-700 shadow-sm backdrop-blur-sm mx-1">
            <div class="hidden sm:flex flex-col items-end leading-none">
                <span class="text-[10px] font-bold uppercase text-blue-100 dark:text-slate-400 tracking-wider mb-0.5" x-text="date">Loading...</span>
                <span class="text-sm font-mono font-bold text-white dark:text-white" x-text="time">--:--:--</span>
            </div>
            <!-- Mobile compact view -->
            <div class="sm:hidden flex flex-col items-end leading-none">
                <span class="text-xs font-mono font-bold text-white" x-text="time"></span>
            </div>
            <div class="hidden sm:block h-8 w-px bg-white/20 dark:bg-slate-600"></div>
            <i data-lucide="clock" class="w-5 h-5 text-white dark:text-slate-400"></i>
        </div>

        <!-- Dark Mode Toggle -->
        <button @click="toggleDark()" class="p-2 hover:bg-blue-500 dark:hover:bg-slate-800 rounded-full transition-colors"
            title="Toggle Dark Mode">
            <i data-lucide="sun" class="w-5 h-5 text-amber-300" x-show="darkMode" x-cloak></i>
            <i data-lucide="moon" class="w-5 h-5 text-white dark:text-slate-400" x-show="!darkMode"></i>
        </button>

        <!-- Notifications -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative p-2 hover:bg-blue-500 dark:hover:bg-slate-800 rounded-full transition-colors">
                <i data-lucide="bell" class="w-5 h-5 text-white dark:text-slate-400"></i>
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                @if($unreadCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-400 rounded-full ring-2 ring-blue-600 dark:ring-zinc-900"></span>
                @endif
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-3 w-80 bg-white dark:bg-zinc-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden ring-1 ring-black/5 z-50">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                    <span class="font-semibold text-sm text-slate-900 dark:text-white">Notifikasi</span>
                    @if($unreadCount > 0)
                        <span class="px-2 py-0.5 text-[10px] bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-full font-bold">{{ $unreadCount }} baru</span>
                    @endif
                </div>
                <div class="max-h-[300px] overflow-y-auto">
                    @forelse(auth()->user()->userNotifications()->latest()->take(5)->get() as $notification)
                        <div
                            class="p-4 border-b border-slate-50 dark:border-zinc-800 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ !$notification->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                        <i data-lucide="info" class="w-4 h-4"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white leading-snug">{{ $notification->title }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <i data-lucide="bell-off" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                            <p class="text-sm text-slate-500">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('notifications.index') }}"
                    class="block p-3 text-center text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800 border-t border-slate-100 dark:border-slate-800 transition-colors">
                    Lihat Semua Notifikasi
                </a>
            </div>
        </div>

        <!-- User Menu -->
        <div x-data="{ open: false }" class="relative ml-1">
            <button @click="open = !open"
                class="flex items-center gap-2 pl-1 pr-2 py-1 hover:bg-blue-500 dark:hover:bg-slate-800 rounded-full transition-colors border border-transparent hover:border-blue-400 dark:hover:border-slate-700">
                <div class="w-8 h-8 bg-white dark:bg-slate-700 rounded-full flex items-center justify-center shadow-sm">
                    <span class="text-blue-700 dark:text-slate-300 text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-blue-200 dark:text-slate-400 hidden sm:block"></i>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute right-0 mt-3 w-56 bg-white dark:bg-zinc-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden ring-1 ring-black/5 z-50">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <p class="font-semibold text-sm text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <div class="p-1">
                     <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>