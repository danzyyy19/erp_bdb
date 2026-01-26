<x-app-layout>
    @section('title', 'Buat Surat Jalan')

    <form method="POST" action="{{ route('delivery-notes.store') }}" class="space-y-6" x-data="deliveryNoteForm()"
        x-cloak>
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Select Customer -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Pilih Customer *</h3>
                    <div class="relative" @click.away="showCustomerDropdown = false">
                        <input type="text" x-model="customerSearch" @input="showCustomerDropdown = true"
                            @focus="showCustomerDropdown = true" placeholder="Ketik nama customer..."
                            class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        <input type="hidden" name="customer_id" :value="selectedCustomer?.id || ''" required>

                        <!-- Dropdown results -->
                        <div x-show="showCustomerDropdown && filterCustomers().length > 0" x-transition
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="customer in filterCustomers()" :key="customer.id">
                                <div @click="selectCustomer(customer)"
                                    class="px-4 py-3 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                                    <span class="font-medium text-zinc-900 dark:text-white"
                                        x-text="customer.name"></span>
                                    <p class="text-xs text-zinc-500" x-text="customer.address || 'Alamat belum diisi'">
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- No results -->
                        <div x-show="showCustomerDropdown && customerSearch.length > 0 && filterCustomers().length === 0"
                            x-transition
                            class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg p-4 text-center">
                            <p class="text-sm text-zinc-500">Customer tidak ditemukan</p>
                        </div>
                    </div>

                    <!-- Selected Customer display -->
                    <div x-show="selectedCustomer" x-transition
                        class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-blue-900 dark:text-blue-300"
                                    x-text="selectedCustomer?.name"></h4>
                                <p class="text-sm text-blue-700 dark:text-blue-400"
                                    x-text="selectedCustomer?.address || '-'"></p>
                            </div>
                            <button type="button" @click="clearCustomer()"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm">Ganti</button>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Informasi Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tanggal Kirim
                                *</label>
                            <input type="date" name="delivery_date" value="{{ old('delivery_date', date('Y-m-d')) }}"
                                required
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nama
                                Sopir</label>
                            <input type="text" name="driver_name" value="{{ old('driver_name') }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">No.
                                Kendaraan</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Nama
                                Penerima</label>
                            <input type="text" name="recipient_name" value="{{ old('recipient_name') }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Alamat
                                Pengiriman *</label>
                            <textarea name="delivery_address" rows="2" required x-model="deliveryAddress"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label
                                class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Catatan</label>
                            <textarea name="notes" rows="2"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">{{ old('notes') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">No.
                                Faktur</label>
                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}"
                                placeholder="Contoh: YYK/001/BDB/XII/2025"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tanggal
                                Faktur</label>
                            <input type="date" name="invoice_date" value="{{ old('invoice_date') }}"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Payment
                                Faktur</label>
                            <input type="text" name="payment_method" value="{{ old('payment_method') }}"
                                placeholder="30 Hari"
                                class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Items (Product Selection) -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">Item Barang Jadi *</h3>
                        <button type="button" @click="addItem()"
                            class="px-3 py-1.5 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                            Tambah Item
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex items-center gap-3 p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                <!-- Product Select -->
                                <div class="flex-1 relative" @click.away="item.showDropdown = false">
                                    <input type="text" x-model="item.search"
                                        @input="item.showDropdown = true; item.product_id = null"
                                        @focus="item.showDropdown = true" placeholder="Cari produk..."
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                                    <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id"
                                        required>

                                    <div x-show="item.showDropdown && filterProducts(item.search).length > 0"
                                        x-transition
                                        class="absolute z-40 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                        <template x-for="product in filterProducts(item.search)" :key="product.id">
                                            <div @click="selectProduct(index, product)"
                                                class="px-3 py-2 cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-700 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="font-medium text-zinc-900 dark:text-white"
                                                        x-text="product.name"></span>
                                                    <span class="text-xs text-zinc-500"
                                                        x-text="'Stok: ' + product.stock + ' ' + product.unit"></span>
                                                </div>
                                                <p class="text-xs text-zinc-500" x-text="product.code"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="w-24">
                                    <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity"
                                        step="any" min="0.01" required placeholder="Qty"
                                        class="w-full px-2 py-2 text-sm rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white text-center">
                                </div>

                                <!-- Unit Display (Read-only) -->
                                <div class="w-20">
                                    <input type="text" :value="item.unit" readonly
                                        class="w-full px-2 py-2 text-sm rounded border border-zinc-200 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-700 text-zinc-600 dark:text-zinc-400 text-center">
                                    <input type="hidden" :name="'items['+index+'][unit]'" :value="item.unit">
                                </div>

                                <!-- Remove -->
                                <button type="button" @click="removeItem(index)"
                                    class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"
                                    x-show="items.length > 1">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <p x-show="items.length === 0" class="text-center text-sm text-zinc-500 py-4">
                        Belum ada item. Klik "Tambah Item" untuk menambahkan.
                    </p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div
                    class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5 lg:sticky lg:top-20">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Ringkasan</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Customer</span>
                            <span class="font-medium text-zinc-900 dark:text-white"
                                x-text="selectedCustomer?.name || '-'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Total Item</span>
                            <span class="font-medium text-zinc-900 dark:text-white"
                                x-text="items.filter(i => i.product_id).length + ' item'"></span>
                        </div>
                    </div>

                    <div x-show="!selectedCustomer || items.filter(i => i.product_id && i.quantity).length === 0"
                        class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <p class="text-xs text-yellow-700 dark:text-yellow-400">
                            <span x-show="!selectedCustomer">Pilih customer terlebih dahulu</span>
                            <span
                                x-show="selectedCustomer && items.filter(i => i.product_id && i.quantity).length === 0">Tambahkan
                                minimal 1 item</span>
                        </p>
                    </div>

                    <button type="submit"
                        :disabled="!selectedCustomer || items.filter(i => i.product_id && i.quantity).length === 0"
                        :class="(!selectedCustomer || items.filter(i => i.product_id && i.quantity).length === 0) ? 'bg-zinc-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                        class="w-full mt-5 py-2.5 px-4 text-white text-sm font-medium rounded-lg">Buat Surat
                        Jalan</button>
                    <a href="{{ route('delivery-notes.index') }}"
                        class="block w-full mt-2 py-2 px-4 text-center text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Batal</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function deliveryNoteForm() {
                return {
                    customerSearch: '',
                    showCustomerDropdown: false,
                    selectedCustomer: null,
                    deliveryAddress: '',
                    customers: @json($customers),
                    products: @json($products),
                    items: [{
                        product_id: null,
                        search: '',
                        quantity: '',
                        unit: '',
                        showDropdown: false
                    }],

                    filterCustomers() {
                        if (!this.customerSearch) return this.customers.slice(0, 10);
                        const s = this.customerSearch.toLowerCase();
                        return this.customers.filter(c =>
                            c.name.toLowerCase().includes(s)
                        ).slice(0, 10);
                    },

                    selectCustomer(customer) {
                        this.selectedCustomer = customer;
                        this.customerSearch = customer.name;
                        this.showCustomerDropdown = false;
                        this.deliveryAddress = customer.address || '';
                    },

                    clearCustomer() {
                        this.selectedCustomer = null;
                        this.customerSearch = '';
                        this.deliveryAddress = '';
                    },

                    filterProducts(search) {
                        if (!search) return this.products.slice(0, 10);
                        const s = search.toLowerCase();
                        return this.products.filter(p =>
                            p.name.toLowerCase().includes(s) ||
                            p.code.toLowerCase().includes(s)
                        ).slice(0, 10);
                    },

                    selectProduct(index, product) {
                        this.items[index].product_id = product.id;
                        this.items[index].search = product.name + ' (' + product.code + ')';
                        this.items[index].unit = product.unit; // Auto-set unit from product
                        this.items[index].showDropdown = false;
                    },

                    addItem() {
                        this.items.push({
                            product_id: null,
                            search: '',
                            quantity: '',
                            unit: '',
                            showDropdown: false
                        });
                        this.$nextTick(() => lucide.createIcons());
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
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