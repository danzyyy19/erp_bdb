<x-app-layout>
    @section('title', 'Detail Supplier')

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Detail Supplier</h2>
                @if($supplier->is_active)
                    <span
                        class="px-2 py-0.5 text-xs rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">Aktif</span>
                @else
                    <span
                        class="px-2 py-0.5 text-xs rounded bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Nonaktif</span>
                @endif
            </div>

            <div class="space-y-4 text-sm">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Kode</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->code }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Nama</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->name }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Contact Person</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->contact_person ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-zinc-500 dark:text-zinc-400">Telepon</p>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->phone ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Email</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 dark:text-zinc-400">Alamat</p>
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $supplier->address ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <a href="{{ route('suppliers.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← Kembali</a>
                <div class="flex gap-2">
                    <a href="{{ route('suppliers.edit', $supplier) }}"
                        class="px-4 py-2 text-sm bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">Edit</a>
                    <form action="{{ route('suppliers.toggle-status', $supplier) }}" method="POST"
                        onsubmit="return confirm('{{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }} supplier ini?')">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm {{ $supplier->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg">
                            {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>