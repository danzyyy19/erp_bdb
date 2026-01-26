<x-app-layout>
    @section('title', 'Daftar SPK')

    <!-- Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 mb-4"
        x-data="spkFilter()">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Cari</label>
                <input type="text" x-model="search" @input.debounce.300ms="fetchData()" placeholder="Cari no SPK..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
            </div>
            <div class="w-32">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Status</label>
                <select x-model="status" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="w-28">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Bulan</label>
                <select x-model="month" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('M') }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-24">
                <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400 mb-1">Tahun</label>
                <select x-model="year" @change="fetchData()"
                    class="w-full px-3 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white">
                    <option value="">Semua</option>
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <template x-if="search || status || month || year">
                <button type="button" @click="resetFilter()"
                    class="px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">Reset</button>
            </template>
        </div>
    </div>

    <!-- Action Button -->
    @if(auth()->user()->isOwner() || auth()->user()->isOperasional())
        <div class="mb-4">
            <a href="{{ route('spk.create') }}"
                class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Buat SPK Baru</a>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            No SPK</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Dibuat Oleh</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Tgl Produksi</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Deadline</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Output</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase border-r border-zinc-200 dark:border-zinc-700">
                            Status</th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold text-zinc-600 dark:text-zinc-400 uppercase">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @include('spk.partials.spk-table')
                </tbody>
            </table>
        </div>
        @if($spks->hasPages())
            <div id="pagination"
                class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50">
                {{ $spks->links() }}
            </div>
        @else
            <div id="pagination" class="hidden"></div>
        @endif
    </div>

    @push('scripts')
        <script>
            function spkFilter() {
                return {
                    search: '{{ request('search') }}',
                    status: '{{ request('status') }}',
                    month: '{{ request('month') }}',
                    year: '{{ request('year') }}',

                    fetchData() {
                        const params = new URLSearchParams();
                        if (this.search) params.set('search', this.search);
                        if (this.status) params.set('status', this.status);
                        if (this.month) params.set('month', this.month);
                        if (this.year) params.set('year', this.year);

                        const newUrl = '{{ route('spk.index') }}' + (params.toString() ? '?' + params.toString() : '');
                        window.history.replaceState({}, '', newUrl);

                        fetch(newUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('table-body').innerHTML = data.html;
                                document.getElementById('pagination').innerHTML = data.pagination || '';
                                if (data.pagination) {
                                    document.getElementById('pagination').classList.remove('hidden');
                                } else {
                                    document.getElementById('pagination').classList.add('hidden');
                                }
                            })
                            .catch(err => console.error('Error fetching data:', err));
                    },

                    resetFilter() {
                        this.search = '';
                        this.status = '';
                        this.month = '';
                        this.year = '';
                        this.fetchData();
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>