<x-app-layout>
    @section('title', 'Edit Customer')

    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Customer</h2>
            <p class="text-zinc-500 dark:text-zinc-400 mt-1">{{ $customer->name }}</p>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('customers.update', $customer) }}"
            class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Nama *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Kode Customer *
                        <span class="text-xs text-zinc-400">(3 huruf untuk nomor faktur)</span></label>
                    <input type="text" name="code" value="{{ old('code', $customer->code) }}" required maxlength="10"
                        placeholder="Contoh: RLI"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 uppercase">
                    @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Alamat</label>
                    <textarea name="address" rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Update
                </button>
                <a href="{{ route('customers.index') }}"
                    class="px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>