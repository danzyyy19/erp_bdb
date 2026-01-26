@forelse($products as $product)
    <tr class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
        <td class="px-4 py-3 font-mono text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
            {{ $product->code }}
        </td>
        <td class="px-4 py-3 text-zinc-900 dark:text-white border-r border-zinc-100 dark:border-zinc-800">
            {{ $product->name }}
        </td>
        <td class="px-4 py-3 text-center border-r border-zinc-100 dark:border-zinc-800">
            @if($product->spec_type == 'high_spec')
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">High</span>
            @else
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400">Medium</span>
            @endif
        </td>
        <td
            class="px-4 py-3 text-right font-medium border-r border-zinc-100 dark:border-zinc-800 {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">
            {{ number_format($product->current_stock, 0) }} {{ $product->unit }}
        </td>
        <td class="px-4 py-3 text-right text-zinc-500 border-r border-zinc-100 dark:border-zinc-800">
            {{ number_format($product->min_stock, 0) }}
        </td>
        <td class="px-4 py-3 text-center border-r border-zinc-100 dark:border-zinc-800">
            @if($product->isLowStock())
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Rendah</span>
            @else
                <span
                    class="px-2 py-0.5 text-xs font-medium rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aman</span>
            @endif
        </td>
        <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('inventory.show', $product->uuid) }}"
                    class="px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
                    <a href="{{ route('inventory.edit', $product->uuid) }}"
                        class="px-2 py-1 text-xs text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                    <form action="{{ route('inventory.destroy', $product->uuid) }}" method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-2 py-1 text-xs text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-8 text-center text-zinc-500">Tidak ada bahan baku</td>
    </tr>
@endforelse