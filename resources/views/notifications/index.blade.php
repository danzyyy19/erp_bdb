<x-app-layout>
    @section('title', 'Notifikasi')

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Notifikasi</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Semua notifikasi Anda</p>
            </div>
            @if($notifications->where('is_read', false)->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        @forelse($notifications as $notification)
            <div
                class="flex items-start gap-4 p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-0 {{ !$notification->is_read ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                <div
                    class="w-10 h-10 rounded-full bg-{{ $notification->type_color }}-100 dark:bg-{{ $notification->type_color }}-900/30 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $notification->type_icon }}"
                        class="w-5 h-5 text-{{ $notification->type_color }}-600 dark:text-{{ $notification->type_color }}-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p
                        class="font-medium text-zinc-900 dark:text-white {{ !$notification->is_read ? 'font-semibold' : '' }}">
                        {{ $notification->title }}
                    </p>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-2">
                        {{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if(!$notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf
                        <button type="submit" class="p-2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="py-12 text-center">
                <i data-lucide="bell-off" class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-3"></i>
                <p class="text-zinc-500 dark:text-zinc-400">Tidak ada notifikasi</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-6">{{ $notifications->links() }}</div>
    @endif
</x-app-layout>
