<x-app-layout>
    @section('title', 'Customers')

    <style>
        @media (min-width: 768px) {
            #cust-mobile-view {
                display: none !important;
            }

            #cust-desktop-view {
                display: block !important;
            }

            [x-cloak] {
                display: none !important;
            }
        }

        @media (max-width: 767.98px) {
            #cust-mobile-view {
                display: grid !important;
            }

            #cust-desktop-view {
                display: none !important;
            }

            [x-cloak] {
                display: none !important;
            }
        }
    </style>

    <div x-data="customerList()" x-cloak>
        <!-- Filters - Instant Client-Side -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                    <input type="text" x-model="search" placeholder="Ketik nama, email, atau telepon..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                </div>
                <button type="button" x-show="search" @click="search = ''"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </div>
            <p class="text-xs text-zinc-500 mt-2"
                x-text="'Menampilkan ' + filteredCustomers().length + ' dari ' + customers.length + ' customer'"></p>
        </div>

        <!-- Action Button -->
        @if(auth()->user()->isOwner() || auth()->user()->isFinance())
            <div class="mb-4">
                <a href="{{ route('customers.create') }}"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Tambah Customer</a>
            </div>
        @endif

        <!-- Desktop Table (Protected) -->
        <div id="cust-desktop-view"
            class="hidden md:block bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Email</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Telepon</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Invoice</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="customer in filteredCustomers()" :key="customer.id">
                            <tr
                                class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white" x-text="customer.name">
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400" x-text="customer.email || '-'">
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400" x-text="customer.phone || '-'">
                                </td>
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400"
                                    x-text="customer.invoices_count"></td>
                                <td class="px-4 py-3 text-center">
                                    <a :href="'/customers/' + customer.uuid + '/edit'"
                                        class="px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredCustomers().length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Tidak ada customer yang
                                ditemukan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Grid (Protected) -->
        <div id="cust-mobile-view" class="grid grid-cols-1 gap-4 md:hidden">
            <template x-for="customer in filteredCustomers()" :key="customer.id">
                <div
                    class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="font-medium text-zinc-900 dark:text-white" x-text="customer.name"></div>
                            <div class="text-xs text-zinc-500 mt-1" x-text="customer.email || '-'"></div>
                        </div>
                        <a :href="'/customers/' + customer.uuid + '/edit'"
                            class="px-2 py-1 text-xs bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded">Edit</a>
                    </div>
                    <div
                        class="flex justify-between text-sm text-zinc-600 dark:text-zinc-400 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-3 h-3"></i>
                            <span x-text="customer.phone || '-'"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-3 h-3"></i>
                            <span x-text="customer.invoices_count + ' Inv'"></span>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="filteredCustomers().length === 0"
                class="text-center p-8 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 text-zinc-500">
                Tidak ada customer ditemukan
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function customerList() {
                return {
                    search: '',
                    customers: @json($customers),

                    filteredCustomers() {
                        if (!this.search) return this.customers;
                        const s = this.search.toLowerCase();
                        return this.customers.filter(c =>
                            (c.name && c.name.toLowerCase().includes(s)) ||
                            (c.email && c.email.toLowerCase().includes(s)) ||
                            (c.phone && c.phone.toLowerCase().includes(s))
                        );
                    }
                }
            }
        </script>
    @endpush

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>