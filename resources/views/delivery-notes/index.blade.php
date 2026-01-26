<x-app-layout>
    @section('title', 'Surat Jalan')

    <div x-data="deliveryNoteList()" x-cloak>
        <!-- Search & Filter -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                    <input type="text" x-model="search" placeholder="Ketik nomor SJ, customer..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                </div>
                <div class="w-40">
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                    <select x-model="filterStatus"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="delivered">Terkirim</option>
                    </select>
                </div>
                <button type="button" x-show="search || filterStatus" @click="search = ''; filterStatus = ''"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </div>
            <p class="text-xs text-zinc-500 mt-2"
                x-text="'Menampilkan ' + filteredNotes().length + ' dari ' + notes.length + ' surat jalan'"></p>
        </div>

        <!-- Action Button -->
        <div class="mb-4">
            <a href="{{ route('delivery-notes.create') }}"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Buat Surat Jalan</a>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                No. SJ</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Customer</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Tgl Kirim</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Status</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Items</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="note in filteredNotes()" :key="note.id">
                            <tr
                                class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3">
                                    <a :href="'/delivery-notes/' + note.uuid"
                                        class="font-medium text-blue-600 dark:text-blue-400 hover:underline"
                                        x-text="note.sj_number"></a>
                                    <p class="text-xs text-zinc-500" x-text="note.created_at_human"></p>
                                </td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400" x-text="note.customer_name"></td>
                                <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400" x-text="note.delivery_date"></td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full" :class="{
                                        'bg-yellow-100 text-yellow-700': note.status === 'pending',
                                        'bg-blue-100 text-blue-700': note.status === 'approved',
                                        'bg-green-100 text-green-700': note.status === 'delivered',
                                        'bg-red-100 text-red-700': note.status === 'returned'
                                    }" x-text="note.status_label"></span>
                                </td>
                                <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400"
                                    x-text="note.items_count + ' item'"></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a :href="'/delivery-notes/' + note.uuid"
                                            class="px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                                        <a :href="'/delivery-notes/' + note.uuid + '/print'" target="_blank"
                                            class="px-2 py-1 text-xs text-green-600 dark:text-green-400 hover:underline">Print</a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredNotes().length === 0">
                            <td colspan="6" class="px-4 py-8 text-center text-zinc-500">Tidak ada surat jalan yang
                                ditemukan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function deliveryNoteList() {
                return {
                    search: '',
                    filterStatus: '',
                    notes: @json($deliveryNotes),

                    filteredNotes() {
                        let result = this.notes;

                        if (this.filterStatus) {
                            result = result.filter(n => n.status === this.filterStatus);
                        }

                        if (this.search) {
                            const s = this.search.toLowerCase();
                            result = result.filter(n =>
                                n.sj_number.toLowerCase().includes(s) ||
                                n.customer_name.toLowerCase().includes(s)
                            );
                        }

                        return result;
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