# Web UMKM — Modul Siklus Pendapatan (Revenue Cycle AIS)

Aplikasi web Sistem Informasi Akuntansi (AIS) khusus modul **Siklus Pendapatan** yang efisien, modern, dan dirancang untuk **UMKM Jasa** di Indonesia.

---

## 🚀 Fitur Unggulan

1. **Alur Transaksi Tanpa Klik Berlebih**:
   - Memotong alur birokrasi tradisional (melewati Sales Order / Delivery Order) dan langsung ke **Faktur Penjualan (Sales Invoice)**.
   - Penjualan Tunai secara otomatis men-generate **Penerimaan Kas (Receipt)** dan melunasi faktur seketika tanpa perlu input manual dua kali.
2. **Pajak Hybrid (PKP / Non-PKP)**:
   - Pengaturan status PKP global (Default PPN 11% / 12%).
   - Dukungan override per-faktur (dapat diubah langsung saat membuat faktur).
3. **Automasi Jurnal Akuntansi (Background AIS Engine)**:
   - Kasir/Admin UMKM tidak perlu mengerti debit/kredit manual.
   - Validasi *double-entry balancing* ketat dengan **Database Transactions** (`transBegin`, `transCommit`, `transRollback`).
   - Audit trail jurnal transparan di setiap rincian faktur.
4. **Multi-Role & Otorisasi**:
   - **Admin**: Akses menyeluruh (Master data CRUD, Laporan Keuangan, Pengaturan Pajak/COA, Manajemen User).
   - **Kasir**: Akses operasional transaksi (Buat Faktur, Terima Kas, Retur Penjualan, Lihat Pelanggan/Jasa).
5. **Tema Desain "Antigravity"**:
   - Layout bersih, whitespace lega, rounded cards (16px), soft shadows, dan visual responsif.
6. **Laporan & Cetak**:
   - Laporan Penjualan (Filter tanggal, status, pelanggan).
   - Ekspor Laporan ke format CSV / Excel.
   - Buku Besar Pembantu Piutang (**Kartu Piutang per Pelanggan**) dengan saldo berjalan (*running balance*).
   - Cetak Faktur format A4 rapi (*print-ready*).

---

## 🛠️ Tech Stack

- **Backend**: CodeIgniter 4 (CI4 v4.7+)
- **Database**: MySQL 8.0+ / MariaDB 10.4+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery), Bootstrap 4 + Stisla Framework
- **Custom UI Layer**: Antigravity Theme (`public/css/antigravity.css`)
- **Icons**: FontAwesome 5.15

---

## 📦 Akun Demo Siap Pakai

| Role | Email | Password | Wewenang |
|------|-------|----------|----------|
| **Admin** | `admin@umkm.id` | `admin123` | Akses penuh (Transaksi, Master Data, Laporan, Pengaturan) |
| **Kasir** | `kasir@umkm.id` | `kasir123` | Transaksi Faktur, Penerimaan Kas, dan Retur |

---

## ⚙️ Petunjuk Instalasi & Menjalankan

### Langkah 1: Siapkan Database MySQL
1. Buka XAMPP Control Panel dan nyalakan **Apache** & **MySQL**.
2. Buka `http://localhost/phpmyadmin` di browser.
3. Import file database yang sudah disediakan di:
   ```
   database/schema.sql
   ```
   *File ini otomatis membuat database `web_umkm`, tabel-tabel relasional, dan data awal (Chart of Accounts, User Admin & Kasir, Data Pelanggan awal, Layanan awal, dan Konfigurasi Pajak).*

*(Alternatif via terminal)*:
```bash
mysql -u root -p < database/schema.sql
```
atau menggunakan migrasi & seeder CodeIgniter:
```bash
php spark migrate
php spark db:seed InitialSeeder
```

### Langkah 2: Jalankan Web Server
Di folder project `web-umkm`, jalankan:
```bash
php spark serve
```
Buka browser pada alamat:
```
http://localhost:8080/login
```

---

## 📊 Matriks Automasi Jurnal Akuntansi

| No | Kejadian Transaksi | Status Pajak | Sisi Debit (Dr) | Sisi Kredit (Cr) |
|:--:|:-------------------|:------------:|:----------------|:-----------------|
| 1 | **Faktur Penjualan (Kredit)** | Non-PKP | (Dr) 1200 Piutang Usaha | (Cr) 4100 Pendapatan Jasa Penjualan |
| 2 | **Faktur Penjualan (Kredit)** | PKP | (Dr) 1200 Piutang Usaha | (Cr) 4100 Pendapatan Jasa Penjualan<br>(Cr) 2100 PPN Keluaran |
| 3 | **Faktur Penjualan (Tunai)** | PKP / Non-PKP | *(Sama seperti kredit, ditambah otomatis:)*<br>(Dr) 1100 Kas Kasir / Tunai | (Cr) 1200 Piutang Usaha |
| 4 | **Penerimaan Kas (Pelunasan)** | - | (Dr) 1100 Kas / 1110 Bank | (Cr) 1200 Piutang Usaha |
| 5 | **Retur Penjualan (Potong Piutang)** | Non-PKP | (Dr) 4200 Retur Penjualan Jasa | (Cr) 1200 Piutang Usaha |
| 6 | **Retur Penjualan (Potong Piutang)** | PKP | (Dr) 4200 Retur Penjualan Jasa<br>(Dr) 2100 PPN Keluaran | (Cr) 1200 Piutang Usaha |
| 7 | **Retur Penjualan (Kembalikan Kas)** | PKP | (Dr) 4200 Retur Penjualan Jasa<br>(Dr) 2100 PPN Keluaran | (Cr) 1100 Kas Kasir / Tunai |
