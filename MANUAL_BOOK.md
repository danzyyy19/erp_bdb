# 📘 Buku Panduan Sistem Informasi Manajemen CV. BDB

Dokumen ini adalah panduan resmi penggunaan sistem ERP CV. Berkah Doa Bunda. Panduan ini mencakup penjelasan istilah, hak akses pengguna, alur kerja sistem (flowchart), dan panduan teknis per modul.

---

## 📚 Daftar Isi

1.  [Glosarium & Istilah](#1-glosarium--istilah-penting)
2.  [Manajemen Pengguna (Roles & Permissions)](#2-manajemen-pengguna-roles--permissions)
3.  [Alur Kerja Sistem (Flowchart)](#3-alur-kerja-sistem-flowchart)
    *   [A. Alur Produksi Regular (Stok)](#a-alur-produksi-regular-make-to-stock)
    *   [B. Alur Pesanan Khusus (Custom)](#b-alur-pesanan-khusus-make-to-order)
4.  [Panduan Fitur Per Modul](#4-panduan-fitur-per-modul)

---

## 1. Glosarium & Istilah Penting

Sebelum menggunakan aplikasi, pahami istilah-istilah berikut:

| Istilah | Kepanjangan | Penjelasan |
| :--- | :--- | :--- |
| **SO** | *Special Order* | Pesanan khusus dari pelanggan untuk produk yang mungkin tidak ada di stok reguler atau request khusus. |
| **SPK** | *Surat Perintah Kerja* | Dokumen perintah dari manajemen ke bagian produksi untuk membuat barang. Ada 2 tipe: **Barang Jadi** (Produk akhir) dan **Barang Setengah Jadi** (Adonan/Base). |
| **FPB** | *Form Permintaan Barang* | Bukti pengambilan bahan baku dari gudang untuk dibawa ke lantai produksi berdasarkan SPK. |
| **SJ** | *Surat Jalan* (Delivery Note) | Dokumen yang wajib dibawa supir saat mengirim barang ke customer. Berisi daftar barang dan jumlah fisik. |
| **Invoice** | *Faktur Penagihan* | Dokumen tagihan harga yang harus dibayar customer. Dibuat berdasarkan Surat Jalan yang sudah terkirim. |
| **Barang Jadi** | *Finished Goods* | Produk akhir yang siap dijual (contoh: Cat Tembok 5Kg, Cat Minyak 1L). |
| **Bahan Baku** | *Raw Material* | Bahan dasar untuk produksi (contoh: Tepung, Zat Pewarna, Kaleng Kosong). |

---

## 2. Manajemen Pengguna (Roles & Permissions)

Sistem ini membagi akses menjadi 3 role utama. Berikut adalah matriks wewenang setiap role:

| Fitur / Aksi | 👑 Owner (Pemilik) | 💰 Finance (Keuangan) | 🏭 Operasional (Gudang/Prod) |
| :--- | :---: | :---: | :---: |
| **Inventory (Stok)** |
| Melihat Stok | ✅ | ✅ | ✅ |
| Tambah Item Baru | ✅ | ❌ | ✅ |
| Edit Data Item | ✅ | ❌ | ✅ |
| **Special Order (SO)** |
| Buat Order Baru | ✅ | ✅ | ⚠️ (View Only) |
| **SPK (Produksi)** |
| Buat SPK | ❌ | ❌ | ✅ |
| **Approval SPK** | ✅ (Wajib) | ❌ | ❌ |
| Selesaikan SPK | ❌ | ❌ | ✅ |
| **FPB (Gudang)** |
| Buat FPB | ❌ | ❌ | ✅ |
| Print FPB | ✅ | ❌ | ✅ |
| **Surat Jalan (SJ)** |
| Buat Surat Jalan | ❌ | ✅ | ⚠️ (Bantu Input) |
| **Approval SJ** | ✅ (Wajib) | ❌ | ❌ |
| Set Pengiriman (Delivered) | ❌ | ❌ | ✅ |
| **Invoice (Tagihan)** |
| Buat Invoice | ❌ | ✅ | ❌ |
| Edit Invoice (Draft) | ✅ | ✅ | ❌ |
| **Approval Invoice** | ✅ (Wajib) | ❌ | ❌ |
| **Reject (Tolak) Invoice** | ✅ | ❌ | ❌ |
| Input Pembayaran | ❌ | ✅ | ❌ |

---

## 3. Alur Kerja Sistem (Flowchart)

Berikut adalah visualisasi alur kerja sistem menggunakan diagram.

### A. Alur Produksi Regular (Make to Stock)
Digunakan untuk mengisi stok gudang tanpa menunggu pesanan customer.

```mermaid
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
    SelesaiSPK --> StokUpdate[Stok Barang Jadi Bertambah]
    StokUpdate --> End((Selesai))
    
    style AppSPK fill:#f9f,stroke:#333
    style StokUpdate fill:#cfc,stroke:#333
```

### B. Alur Pesanan Khusus (Make to Order)
Digunakan saat ada pesanan masuk dari customer.

```mermaid
graph TD
    Cust[Customer Order] --> InputSO[1. Finance Input Special Order]
    InputSO --> BuatSPK[2. Klik 'Buat SPK' dari SO]
    BuatSPK --> AppSPK{3. Owner Approval?}
    
    AppSPK -- Yes --> Produksi[4. Proses Produksi & FPB]
    Produksi --> BarangReady[Barang Jadi Siap]
    
    BarangReady --> BuatSJ[5. Finance Buat Surat Jalan]
    BuatSJ --> AppSJ{6. Owner Approve SJ?}
    
    AppSJ -- Yes --> Kirim[7. Pengiriman Barang]
    Kirim --> SetDelivered[8. Operasional Set 'Delivered']
    
    SetDelivered --> BuatInv[9. Finance Buat Invoice]
    BuatInv --> NoteInv[/Tarik Data dari SJ/]
    BuatInv --> AppInv{10. Owner Approve Invoice?}
    
    AppInv -- Reject --> ResetSJ[Reset Status SJ]
    ResetSJ --> BuatInv
    
    AppInv -- Approve --> KirimInv[Kirim Invoice ke Customer]
    KirimInv --> Bayar[11. Catat Pembayaran]
    Bayar --> End((Selesai))

    style AppSPK fill:#f9f,stroke:#333
    style AppSJ fill:#f9f,stroke:#333
    style AppInv fill:#f9f,stroke:#333
    style SetDelivered fill:#bbf,stroke:#333
```

---

## 4. Panduan Fitur Per Modul

### 📦 Modul Inventory
*   **Akses:** Semua Role (View), Operasional (Edit).
*   **Fungsi:** Melihat ketersediaan stok fisik real-time.
*   **Tips:** Jangan mengedit stok manual (via edit item) jika selisih stok disebabkan oleh produksi. Gunakan alur SPK agar pemakaian bahan tercatat. Update manual hanya untuk *Stock Opname* atau penyesuaian awal.

### 📝 Modul SPK (Surat Perintah Kerja)
*   **Cara Membuat:** Menu SPK -> Buat SPK Baru.
*   **Data Wajib:**
    *   *Tipe:* Pilih "Barang Jadi" atau "Setengah Jadi".
    *   *Target:* Pilih produk yang mau dibuat.
    *   *Deadline:* Tanggal target selesai.
*   **Penting:** SPK yang sudah "Planned" akan membooking stok bahan baku secara virtual.

### 🚚 Modul Surat Jalan (Delivery Note)
*   **Syarat Utama:**
    1.  Harus ada **Customer**.
    2.  Harus ada **Barang** (bisa dari Pilih SO atau Pilih Manual Item).
*   **Status Pengiriman:**
    *   *Approved:* Sudah disetujui Owner, belum jalan.
    *   *Delivered:* Barang sudah sampai di customer. Tombol ini baru muncul setelah status Approved.
*   **Koneksi ke Invoice:** Surat Jalan yang belum status **Delivered** TIDAK AKAN muncul di menu pembuatan Invoice.

### 💰 Modul Invoice (Penagihan)
*   **Fitur Keamanan:** Invoice tidak bisa dibuat manual sembarangan. Harus **"Tarik Data"** dari Surat Jalan yang valid.
*   **Cara Revisi (Edit):**
    *   Jika status **Draft / Menunggu Persetujuan**: Klik tombol kuning **"Edit Invoice"**.
    *   Jika status **Disetujui Owner**: Minta Owner klik tombol **"Tolak"** (Reject). Setelah ditolak, Surat Jalan akan lepas kaitan dan bisa dibuatkan Invoice baru yang benar.
*   **Pembayaran:** Bisa dicatat bertahap (Partial Payment) atau langsung Lunas.

---
*Dokumen diperbarui terakhir: 10 Desember 2025*
*Dibuat Otomatis oleh Sistem*
