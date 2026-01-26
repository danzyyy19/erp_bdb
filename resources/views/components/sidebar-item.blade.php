@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors group
          {{ $active
    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
    : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' 
          }}">
    <i data-lucide="{{ $icon }}" class="w-5 h-5 flex-shrink-0"></i>
    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">
        {{ $slot }}
    </span>
</a>