<x-app-layout>
    @section('title', 'Buat Invoice')

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Buat Invoice</h2>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Pilih Surat Jalan untuk membuat invoice baru.</p>
            </div>
            <a href="{{ route('invoice.index') }}"
                class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white rounded-lg transition-colors">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="deliveryNoteSelector()">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Delivery Note Selection -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Informasi Surat Jalan</h3>

                    <div class="relative">
                        <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari Surat Jalan
                            (Status: Terkirim) *</label>
                        <div class="relative">
                            <input type="text" x-model="search" @input="filterNotes()" @focus="showDropdown = true"
                                @click.away="showDropdown = false" placeholder="Ketik No. SJ atau Nama Customer..."
                                class="w-full pl-10 px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                                autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-zinc-400"></i>
                            </div>

                            <!-- Dropdown Results -->
                            <div x-show="showDropdown && filteredNotes.length > 0" x-cloak
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                <template x-for="note in filteredNotes" :key="note.id">
                                    <div @click="selectNote(note)"
                                        class="px-4 py-3 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700/50 border-b border-zinc-100 dark:border-zinc-700/50 last:border-0 group">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <div class="font-medium text-zinc-900 dark:text-white"
                                                    x-text="note.sj_number"></div>
                                                <div
                                                    class="text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-1 mt-0.5">
                                                    <i data-lucide="user" class="w-3 h-3"></i>
                                                    <span
                                                        x-text="note.customer ? note.customer.name : 'No Customer'"></span>
                                                </div>
                                            </div>
                                            <div class="text-xs text-zinc-400" x-text="formatDate(note.created_at)">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- No Results -->
                            <div x-show="showDropdown && search.length > 0 && filteredNotes.length === 0" x-cloak
                                class="absolute z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg p-4 text-center text-sm text-zinc-500">
                                Tidak ada Surat Jalan yang cocok (Pastikan status 'delivered' dan belum invoice).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Placeholder for Form Look -->
                <div
                    class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5 opacity-50 pointer-events-none">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Item Invoice</h3>
                    <div
                        class="p-8 text-center text-zinc-400 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-lg">
                        Pilih Surat Jalan terlebih dahulu untuk memuat item otomatis...
                    </div>
                </div>
            </div>

            <!-- Right Column Placeholder -->
            <div class="space-y-6 opacity-50 pointer-events-none">
                <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                    <h3 class="font-semibold text-sm text-zinc-900 dark:text-white mb-4">Ringkasan</h3>
                    <div class="space-y-4">
                        <div class="h-10 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                        <div class="h-10 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                        <hr class="border-zinc-200 dark:border-zinc-700">
                        <div class="h-12 bg-zinc-100 dark:bg-zinc-800 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function deliveryNoteSelector() {
                return {
                    search: '',
                    showDropdown: false,
                    notes: @json($deliveryNotes),
                    filteredNotes: [],

                    init() {
                        this.filteredNotes = this.notes;
                    },



                    filterNotes() {
                        const query = this.search.toLowerCase();
                        if (query === '') {
                            this.filteredNotes = this.notes;
                        } else {
                            this.filteredNotes = this.notes.filter(note => {
                                const sjMatch = note.sj_number.toLowerCase().includes(query);
                                const customerMatch = note.customer && note.customer.name.toLowerCase().includes(query);
                                return sjMatch || customerMatch;
                            });
                        }
                        this.showDropdown = true;
                    },

                    selectNote(note) {
                        // Redirect to the create-from-delivery-note page
                        window.location.href = '/invoice/create-from-delivery-note/' + note.uuid;
                    },

                    formatDate(dateString) {
                        const date = new Date(dateString);
                        return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(date);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>