<!-- Searchable Select Component -->
<div x-data="{
    open: false,
    search: '',
    selected: @entangle($attributes->wire('model')).defer ?? '',
    get filteredOptions() {
        return this.options.filter(option => 
            option.text.toLowerCase().includes(this.search.toLowerCase())
        );
    }
}" 
x-init="$watch('selected', value => $dispatch('change', value))"
@click.away="open = false" 
class="relative"
{{ $attributes->except(['wire:model', 'options']) }}>
    
    <!-- Search Input -->
    <div class="relative">
        <input 
            type="text"
            x-model="search"
            @focus="open = true"
            @keydown.escape="open = false"
            @keydown.enter.prevent="if(filteredOptions.length > 0) { selected = filteredOptions[0].value; open = false; }"
            placeholder="{{ $placeholder ?? 'Cari...' }}"
            class="w-full px-4 py-2 pr-10 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i data-lucide="search" class="w-4 h-4 text-zinc-400"></i>
        </div>
    </div>

    <!-- Hidden Input for Form -->
    <input type="hidden" name="{{ $name }}" x-model="selected">

    <!-- Dropdown -->
    <div 
        x-show="open && filteredOptions.length > 0"
        x-transition
        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-auto"
    >
        <template x-for="option in filteredOptions" :key="option.value">
            <button
                type="button"
                @click="selected = option.value; search = option.text; open = false"
                class="w-full px-4 py-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white"
                :class="{ 'bg-blue-50 dark:bg-blue-900/20': selected === option.value }"
            >
                <span x-text="option.text"></span>
            </button>
        </template>
    </div>

    <!-- No Results -->
    <div 
        x-show="open && search && filteredOptions.length === 0"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg px-4 py-3"
    >
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Tidak ditemukan</p>
    </div>
</div>
