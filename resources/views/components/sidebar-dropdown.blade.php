@props(['title', 'icon', 'active' => false])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }" class="space-y-1">
    <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors group
                   {{ $active
    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
    : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' 
                   }}">
        <div class="flex items-center gap-3">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 flex-shrink-0"></i>
            <span x-show="!sidebarCollapsed" x-transition class="text-sm font-medium">{{ $title }}</span>
        </div>
        <i data-lucide="chevron-down" x-show="!sidebarCollapsed" class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"></i>
    </button>

    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2" class="ml-8 space-y-1" x-cloak>
        {{ $slot }}
    </div>
</div>
