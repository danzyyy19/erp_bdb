<x-app-layout>
    @section('title', 'Daftar Supplier')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau kode..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
        </form>
    </div>

    <!-- Action Button -->
    <div class="mb-4 flex justify-between">
        <a href="{{ route('suppliers.create') }}"
            class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Tambah Supplier</a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Kode</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Nama</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Kontak</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Telepon</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Status</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($suppliers as $supplier)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $supplier->code }}</td>
                            <td class="px-4 py-3 text-zinc-900 dark:text-white">{{ $supplier->name }}</td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $supplier->contact_person ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400">{{ $supplier->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($supplier->is_active)
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aktif</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if($supplier->uuid)
                                        <a href="{{ route('suppliers.show', $supplier) }}"
                                            class="p-1.5 text-zinc-500 hover:text-blue-600" title="Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        <span class="p-1.5 text-zinc-300 cursor-not-allowed" title="Missing UUID">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </span>
                                    @endif
                                    @if($supplier->uuid)
                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                            class="p-1.5 text-zinc-500 hover:text-yellow-600" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                    @else
                                        <span class="p-1.5 text-zinc-300 cursor-not-allowed" title="Missing UUID">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">Tidak ada
                                supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
            <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>