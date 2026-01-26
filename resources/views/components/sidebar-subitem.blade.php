@props(['href', 'active' => false])

<a href="{{ $href }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
          {{ $active
    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-medium'
    : 'text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-white' 
          }}">
    {{ $slot }}
</a>
