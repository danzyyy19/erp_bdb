<x-app-layout>
    @section('title', 'SPK Menunggu Persetujuan')

    <div x-data="pendingSpkList()" x-cloak>
        <!-- Search & Filter - Instant Client-Side -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari SPK</label>
                    <input type="text" x-model="search" placeholder="Ketik nomor SPK, pembuat, atau catatan..."
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                </div>
                <div class="w-40">
                    <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tipe SPK</label>
                    <select x-model="filterType"
                        class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                        <option value="">Semua Tipe</option>
                        <option value="base">Base (Setengah Jadi)</option>
                        <option value="finishgood">Finished Good</option>
                    </select>
                </div>
                <button type="button" x-show="search || filterType" @click="search = ''; filterType = ''"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </div>
            <p class="text-xs text-zinc-500 mt-2"
                x-text="'Menampilkan ' + filteredSpks().length + ' dari ' + spks.length + ' SPK pending'"></p>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden"
            x-show="spks.length > 0">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                        <tr>
                            <th
                                class="px-3 py-2.5 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-36 whitespace-normal">
                                No SPK</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-24 whitespace-normal">
                                Tipe SPK</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-32 whitespace-normal">
                                Customer</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-28 whitespace-normal">
                                Dibuat Oleh</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-28 whitespace-normal">
                                Tgl Produksi</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700 w-28 whitespace-normal">
                                Deadline</th>
                            <th
                                class="px-2 py-2.5 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase w-32 whitespace-normal">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="spk in filteredSpks()" :key="spk.id">
                            <tr
                                class="border-b border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-3 py-2 border-r border-zinc-100 dark:border-zinc-800">
                                    <a :href="'/spk/' + spk.uuid"
                                        class="font-mono text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                        x-text="spk.spk_number"></a>
                                    <p class="text-[10px] text-zinc-500" x-text="spk.created_at_human"></p>
                                </td>
                                <td class="px-2 py-2 text-center border-r border-zinc-100 dark:border-zinc-800">
                                    <span class="px-2 py-0.5 text-[10px] font-medium rounded"
                                        :class="spk.spk_type === 'base' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'"
                                        x-text="spk.spk_type === 'base' ? 'Manual' : 'Dari SO'"></span>
                                </td>
                                <td
                                    class="px-2 py-2 text-xs text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800">
                                    <template x-if="spk.spk_type === 'finishgood' && spk.customer_name">
                                        <span x-text="spk.customer_name"></span>
                                    </template>
                                    <template x-if="spk.spk_type !== 'finishgood' || !spk.customer_name">
                                        <span class="text-zinc-500 dark:text-zinc-500 italic">Material Base</span>
                                    </template>
                                </td>
                                <td class="px-2 py-2 text-xs text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800"
                                    x-text="spk.creator_name"></td>
                                <td class="px-2 py-2 text-xs text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap"
                                    x-text="spk.production_date || '-'"></td>
                                <td class="px-2 py-2 text-xs text-center text-zinc-600 dark:text-zinc-400 border-r border-zinc-100 dark:border-zinc-800 whitespace-nowrap"
                                    x-text="spk.deadline || '-'">
                                </td>
                                <td class="px-2 py-2 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <form :action="'/spk/' + spk.uuid + '/approve'" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-2 py-1 text-[10px] bg-green-600 hover:bg-green-700 text-white rounded">Setujui</button>
                                        </form>
                                        <form :action="'/spk/' + spk.uuid + '/reject'" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-2 py-1 text-[10px] bg-red-600 hover:bg-red-700 text-white rounded">Tolak</button>
                                        </form>
                                        <a :href="'/spk/' + spk.uuid + '/edit'"
                                            class="px-2 py-1 text-[10px] text-amber-600 dark:text-amber-400 hover:underline">Edit</a>
                                        <a :href="'/spk/' + spk.uuid"
                                            class="px-2 py-1 text-[10px] text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredSpks().length === 0 && spks.length > 0">
                            <td colspan="7" class="px-4 py-8 text-center text-xs text-zinc-500">Tidak ada SPK yang cocok
                                dengan
                                filter</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="spks.length === 0"
            class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-2">Tidak Ada SPK Pending</h3>
            <p class="text-zinc-500 dark:text-zinc-400">Semua SPK sudah diproses. Tidak ada yang menunggu persetujuan.
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            function pendingSpkList() {
                return {
                    search: '',
                    filterType: '',
                    spks: @json($spks),

                    filteredSpks() {
                        let result = this.spks;

                        if (this.filterType) {
                            result = result.filter(s => s.spk_type === this.filterType);
                        }

                        if (this.search) {
                            const s = this.search.toLowerCase();
                            result = result.filter(spk =>
                                (spk.spk_number && spk.spk_number.toLowerCase().includes(s)) ||
                                (spk.creator_name && spk.creator_name.toLowerCase().includes(s)) ||
                                (spk.notes && spk.notes.toLowerCase().includes(s))
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