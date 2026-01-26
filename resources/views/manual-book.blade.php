<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku Panduan Sistem (Manual Book)') }}
        </h2>
    </x-slot>

    <!-- Mermaid JS -->
    <script src="https://cdn.jsdelivr.net/npm/mermaid/dist/mermaid.min.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Intro -->
                    <div class="mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
                        <h3 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">Selamat Datang di
                            Sistem ERP CV. BDB</h3>
                        <p class="text-gray-600 dark:text-gray-300">
                            Dokumen ini adalah panduan resmi penggunaan sistem. Silakan pelajari istilah, hak akses, dan
                            alur kerja sistem di bawah ini.
                        </p>
                    </div>

                    <!-- TOC -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <div class="sticky top-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4
                                    class="font-bold mb-3 text-gray-700 dark:text-gray-200 uppercase text-xs tracking-wider">
                                    Daftar Isi</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#glosarium"
                                            class="block text-indigo-600 dark:text-indigo-400 hover:underline">1.
                                            Glosarium & Istilah</a></li>
                                    <li><a href="#roles"
                                            class="block text-indigo-600 dark:text-indigo-400 hover:underline">2. Hak
                                            Akses (Roles)</a></li>
                                    <li><a href="#flowchart"
                                            class="block text-indigo-600 dark:text-indigo-400 hover:underline">3.
                                            Flowchart Alur Kerja</a></li>
                                    <li><a href="#modules"
                                            class="block text-indigo-600 dark:text-indigo-400 hover:underline">4.
                                            Panduan Per Modul</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="md:col-span-3 space-y-12">

                            <!-- 1. Glosarium -->
                            <section id="glosarium">
                                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">BAB
                                        1</span>
                                    Glosarium & Istilah Penting
                                </h3>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th class="px-6 py-3">Istilah</th>
                                                <th class="px-6 py-3">Kepanjangan</th>
                                                <th class="px-6 py-3">Penjelasan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">SO</td>
                                                <td class="px-6 py-4">Special Order</td>
                                                <td class="px-6 py-4">Pesanan khusus dari pelanggan untuk produk
                                                    custom/non-stok.</td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">SPK</td>
                                                <td class="px-6 py-4">Surat Perintah Kerja</td>
                                                <td class="px-6 py-4">Perintah produksi. Tipe: <b>Barang Jadi</b>
                                                    (Finale) & <b>Barang Setengah Jadi</b> (Base).</td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">FPB</td>
                                                <td class="px-6 py-4">Form Permintaan Barang</td>
                                                <td class="px-6 py-4">Bukti pengambilan bahan baku dari gudang ke
                                                    produksi.</td>
                                            </tr>
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">SJ</td>
                                                <td class="px-6 py-4">Surat Jalan</td>
                                                <td class="px-6 py-4">Dokumen pengantar barang. Wajib dibawa supir &
                                                    ditandai Delivered jika sudah sampai.</td>
                                            </tr>
                                            <tr class="bg-white dark:bg-gray-800">
                                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">Invoice
                                                </td>
                                                <td class="px-6 py-4">Faktur / Tagihan</td>
                                                <td class="px-6 py-4">Dokumen penagihan. Hanya bisa dibuat dari SJ yang
                                                    sudah Delivered.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>

                            <!-- 2. Roles -->
                            <section id="roles">
                                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">BAB
                                        2</span>
                                    Manajemen Pengguna (Hak Akses)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div
                                        class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                        <div class="text-2xl mb-2">👑</div>
                                        <h4 class="font-bold text-yellow-800 dark:text-yellow-400">Owner</h4>
                                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-2">Approval Master</p>
                                        <ul
                                            class="text-xs text-left mt-3 space-y-1 text-gray-600 dark:text-gray-400 list-disc pl-4">
                                            <li>Approve SPK & SJ</li>
                                            <li>Approve & Reject Invoice</li>
                                            <li>Lihat Semua Laporan</li>
                                        </ul>
                                    </div>
                                    <div
                                        class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                                        <div class="text-2xl mb-2">💰</div>
                                        <h4 class="font-bold text-blue-800 dark:text-blue-400">Finance</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-2">Admin & Keuangan</p>
                                        <ul
                                            class="text-xs text-left mt-3 space-y-1 text-gray-600 dark:text-gray-400 list-disc pl-4">
                                            <li>Input SO & SJ</li>
                                            <li>Buat & Kirim Invoice</li>
                                            <li>Catat Pembayaran</li>
                                        </ul>
                                    </div>
                                    <div
                                        class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                                        <div class="text-2xl mb-2">🏭</div>
                                        <h4 class="font-bold text-green-800 dark:text-green-400">Operasional</h4>
                                        <p class="text-sm text-green-700 dark:text-green-300 mt-2">Produksi & Gudang</p>
                                        <ul
                                            class="text-xs text-left mt-3 space-y-1 text-gray-600 dark:text-gray-400 list-disc pl-4">
                                            <li>Cek Stok & Buat SPK</li>
                                            <li>Buat FPB & Produksi</li>
                                            <li>Set Pengiriman (Delivered)</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <!-- 3. Flowchart -->
                            <section id="flowchart">
                                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">BAB
                                        3</span>
                                    Alur Kerja Sistem (Flowchart)
                                </h3>

                                <div class="space-y-8">
                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-200">A. Alur
                                            Produksi Regular (Stok)</h4>
                                        <div
                                            class="mermaid bg-white p-4 rounded-lg border border-gray-200 overflow-x-auto">
                                            graph TD
                                            Start((Mulai)) --> CekStok[1. Cek Stok Barang]
                                            CekStok --> |Stok Menipis| BuatSPK[2. Operasional Buat SPK]
                                            BuatSPK --> Note1[/Tipe: Barang Jadi / Setengah Jadi/]
                                            BuatSPK --> AppSPK{3. Owner Approval?}

                                            AppSPK -- Ditolak --> Revisi[Revisi / Cancel]
                                            AppSPK -- Disetujui --> PrintSPK[4. Print SPK]

                                            PrintSPK --> BuatFPB[5. Buat Form Permintaan Barang]
                                            BuatFPB --> AmbilBahan[6. Gudang Siapkan Bahan]
                                            AmbilBahan --> Produksi[7. Proses Produksi]

                                            Produksi --> SelesaiSPK[8. Tandai SPK Selesai]
                                            SelesaiSPK --> StokUpdate[Stok Bertambah]
                                            StokUpdate --> End((Selesai))

                                            classDef default stroke:#cbd5e1,fill:white;
                                            classDef green fill:#dcfce7,stroke:#22c55e,color:#15803d;
                                            classDef yellow fill:#fef9c3,stroke:#ca8a04,color:#854d0e;

                                            class AppSPK yellow;
                                            class Start,End,StokUpdate green;
                                        </div>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-200">B. Alur
                                            Pesanan Khusus (Make to Order)</h4>
                                        <div
                                            class="mermaid bg-white p-4 rounded-lg border border-gray-200 overflow-x-auto">
                                            graph TD
                                            Cust[Customer Order] --> InputSO[1. Finance Input SO]
                                            InputSO --> BuatSPK[2. Klik 'Buat SPK' dari SO]
                                            BuatSPK --> AppSPK{3. Owner Approval?}

                                            AppSPK -- Yes --> Produksi[4. Produksi & FPB]
                                            Produksi --> BarangReady[Barang Siap]

                                            BarangReady --> BuatSJ[5. Buat Surat Jalan]
                                            BuatSJ --> AppSJ{6. Owner Approve SJ?}

                                            AppSJ -- Yes --> Kirim[7. Pengiriman]
                                            Kirim --> SetDelivered[8. Set 'Delivered']

                                            SetDelivered --> BuatInv[9. Buat Invoice]
                                            BuatInv --> NoteInv[/Tarik Data SJ/]
                                            BuatInv --> AppInv{10. Owner Approve Inv?}

                                            AppInv -- Reject --> ResetSJ[Reset Status SJ]
                                            ResetSJ --> BuatInv

                                            AppInv -- Approve --> KirimInv[Kirim Invoice]
                                            KirimInv --> Bayar[11. Catat Bayar]
                                            Bayar --> End((Selesai))

                                            classDef default stroke:#cbd5e1,fill:white;
                                            classDef green fill:#dcfce7,stroke:#22c55e,color:#15803d;
                                            classDef blue fill:#dbeafe,stroke:#3b82f6,color:#1e40af;
                                            classDef yellow fill:#fef9c3,stroke:#ca8a04,color:#854d0e;

                                            class AppSPK,AppSJ,AppInv yellow;
                                            class InputSO,BuatSJ,BuatInv,Bayar,KirimInv blue;
                                            class SetDelivered,Produksi green;
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- 4. Modul -->
                            <section id="modules">
                                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">BAB
                                        4</span>
                                    Panduan Fitur Per Modul
                                </h3>

                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Inventory -->
                                    <details class="group bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg cursor-pointer">
                                        <summary
                                            class="font-bold text-gray-800 dark:text-gray-200 flex justify-between items-center">
                                            <span>📦 Inventory (Manajemen Stok)</span>
                                            <span
                                                class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                                            <p>Modul ini digunakan untuk memantau stok bahan baku dan barang jadi.</p>
                                            
                                            <div class="border-l-4 border-blue-500 pl-3">
                                                <h5 class="font-bold text-gray-800 dark:text-white">1. Menambah Barang Baru (Master Data)</h5>
                                                <p class="mt-1">Siapa: <span class="font-semibold text-green-600">Operasional</span> & <span class="font-semibold text-yellow-600">Owner</span></p>
                                                <p>Gunakan fitur ini jika ada jenis produk baru yang belum pernah diproduksi sebelumnya.</p>
                                                <ul class="list-disc pl-5 mt-1">
                                                    <li>Klik tombol "Tambah Item" di halaman Inventory.</li>
                                                    <li>Isi Nama Barang, SKU (Kode unik), Satuan, dan Kategori.</li>
                                                    <li>Set Stok Awal (Opname) jika barang sudah ada di gudang.</li>
                                                </ul>
                                            </div>

                                            <div class="border-l-4 border-red-500 pl-3">
                                                <h5 class="font-bold text-gray-800 dark:text-white">2. Koreksi Stok (Opname)</h5>
                                                <p>Gunakan tombol <span class="bg-gray-200 dark:bg-gray-600 px-1 rounded text-xs">Edit</span> pada item hanya untuk koreksi selisih stok (Stock Opname).</p>
                                                <p class="text-red-500 text-xs italic font-bold">JANGAN edit stok manual untuk mencatat hasil produksi. Gunakan alur SPK agar tercatat di laporan resmi.</p>
                                            </div>
                                        </div>
                                    </details>

                                    <!-- SPK -->
                                    <details class="group bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg cursor-pointer">
                                        <summary
                                            class="font-bold text-gray-800 dark:text-gray-200 flex justify-between items-center">
                                            <span>📝 SPK (Surat Perintah Kerja)</span>
                                            <span
                                                class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                                            <p>Ini adalah inti dari sistem produksi.</p>
                                            <ul class="list-disc pl-5">
                                                <li><b class="text-indigo-600">Draft / Pending:</b> SPK baru dibuat oleh Operasional. Stok bahan baku belum berkurang.</li>
                                                <li><b class="text-indigo-600">Approved:</b> Disetujui Owner. Stok bahan baku otomatis ter-booking (dipesan).</li>
                                                <li><b class="text-indigo-600">Completed (Selesai):</b> Barang Jadi sudah selesai dibuat. Stok fisik bahan baku berkurang, Stok Barang Jadi bertambah.</li>
                                            </ul>
                                        </div>
                                    </details>

                                    <!-- Surat Jalan -->
                                    <details class="group bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg cursor-pointer">
                                        <summary
                                            class="font-bold text-gray-800 dark:text-gray-200 flex justify-between items-center">
                                            <span>🚚 Surat Jalan (Pengiriman)</span>
                                            <span
                                                class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                                            <p>Setiap pengiriman barang wajib disertai Surat Jalan.</p>
                                            <ul class="list-disc pl-5">
                                                <li>Surat Jalan mengurangi stok barang jadi secara real (fisik keluar gudang).</li>
                                                <li>Status <b>Delivered</b> (Terkirim) adalah syarat mutlak untuk menagih customer (Invoice).</li>
                                                <li>Jika terjadi retur/batal kirim, Admin bisa membatalkan Surat Jalan selama belum di-Invoice.</li>
                                            </ul>
                                        </div>
                                    </details>

                                    <!-- Invoice -->
                                    <details class="group bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg cursor-pointer">
                                        <summary
                                            class="font-bold text-gray-800 dark:text-gray-200 flex justify-between items-center">
                                            <span>💰 Invoice & Keuangan</span>
                                            <span
                                                class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                                            <div class="border-l-4 border-green-500 pl-3">
                                                <h5 class="font-bold text-gray-800 dark:text-white">Pembuatan Invoice</h5>
                                                <p>Finance hanya perlu mencari Nomor Surat Jalan yang sudah terkirim. Sistem akan otomatis menarik data item dan harga.</p>
                                            </div>
                                            <div class="border-l-4 border-yellow-500 pl-3">
                                                <h5 class="font-bold text-gray-800 dark:text-white">Revisi & Edit</h5>
                                                <p>Salah input harga? Tenang.</p>
                                                <ul class="list-disc pl-5 mt-1">
                                                    <li>Sebelum diapprove Owner, Finance bisa klik tombol kuning <b>Edit Invoice</b>.</li>
                                                    <li>Jika sudah terlanjur diapprove, Owner harus klik <b>Reject</b> dulu agar bisa direvisi.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </details>

                                    <!-- Admin -->
                                    <details class="group bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg cursor-pointer">
                                        <summary
                                            class="font-bold text-gray-800 dark:text-gray-200 flex justify-between items-center">
                                            <span>👥 User & Customer Management</span>
                                            <span
                                                class="text-gray-400 group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-3">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <h5 class="font-bold text-gray-800 dark:text-white">Tambah Customer</h5>
                                                    <p>Dilakukan oleh Finance saat membuat Sales Order atau Invoice baru. Klik tombol "+" di sebelah dropdown customer.</p>
                                                </div>
                                                <div>
                                                    <h5 class="font-bold text-gray-800 dark:text-white">Reset Password User</h5>
                                                    <p>Hubungi Owner/IT Admin untuk mereset password pengguna yang lupa via database atau halaman Profile (jika akses tersedia).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </details>
                                </div>
                            </section>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            mermaid.initialize({
                startOnLoad: true,
                theme: 'default',
                securityLevel: 'loose'
            });
        });
    </script>
</x-app-layout>