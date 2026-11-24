# Panduan Hosting FreshWash Laundry
## TiDB Cloud (Database) + GitHub (Repository) + Vercel (Web Serverless)

Aplikasi **FreshWash Laundry & Dry Cleaning (CodeIgniter 4)** telah dikonfigurasi secara lengkap dan siap untuk di-deploy ke **Vercel Serverless** dengan database cloud **TiDB Cloud**.

---

### LANGKAH 1: Setup Database di TiDB Cloud (Gratis)

1. Buka [https://tidbcloud.com](https://tidbcloud.com) dan login/daftar (bisa menggunakan akun Google/GitHub).
2. Klik **Create Cluster** ➔ Pilih **Serverless (Free Tier)**.
3. Beri nama cluster (misal: `freshwash-db`) ➔ Klik **Create**.
4. Setelah cluster aktif:
   - Klik tombol **Connect** untuk melihat informasi koneksi:
     - **Host:** (contoh: `gateway01.ap-southeast-1.prod.aws.tidbcloud.com`)
     - **Port:** `4000`
     - **User:** (contoh: `xxxxxx.root`)
     - **Password:** (buat/salin password yang diberikan)
     - **Database:** `web_umkm` (atau `test`)
5. Buka tab **SQL Editor** di TiDB Cloud:
   - Buka file `database/freshwash_dump.sql` yang ada di proyek ini.
   - Salin (*copy*) seluruh isinya, lalu *paste* ke SQL Editor TiDB Cloud dan klik **Run**.
   - Seluruh tabel, master data pelanggan/layanan, transaksi faktur, struk, dan jurnal akuntansi otomatis terisi di cloud!

---

### LANGKAH 2: Push Proyek ke GitHub

1. Buka [https://github.com/new](https://github.com/new) dan buat repository baru:
   - Repository name: `freshwash-laundry` (atau sesuai keinginan).
   - Pilih **Public** atau **Private**.
   - **Jangan centang** "Add a README file" (karena kode lokal sudah memiliki file lengkap).
   - Klik **Create repository**.
2. Salin URL repositori Anda (contoh: `https://github.com/USERNAME/freshwash-laundry.git`).
3. Di terminal/command prompt folder proyek ini, jalankan:
   ```bash
   git branch -M main
   git remote add origin https://github.com/USERNAME/freshwash-laundry.git
   git push -u origin main
   ```

---

### LANGKAH 3: Deploy ke Vercel

Proyek ini sudah dilengkapi file konfigurasi Vercel:
- `vercel.json` (routing static asset, model 3D, dan PHP runtime)
- `api/index.php` (serverless entry point)
- `app/Config/Paths.php` (auto-redirect session & cache ke `/tmp/writable` di lingkungan serverless)

**Cara Deploy:**
1. Buka [https://vercel.com](https://vercel.com) dan login menggunakan akun GitHub Anda.
2. Klik **Add New...** ➔ **Project**.
3. Cari repository `freshwash-laundry` yang baru saja di-push, lalu klik **Import**.
4. Buka bagian **Environment Variables**, lalu masukkan konfigurasi dari TiDB Cloud:
   - `CI_ENVIRONMENT` = `production`
   - `database.default.hostname` = *(Host dari TiDB Cloud)*
   - `database.default.port` = `4000`
   - `database.default.database` = `web_umkm` (atau nama DB di TiDB)
   - `database.default.username` = *(Username dari TiDB Cloud)*
   - `database.default.password` = *(Password dari TiDB Cloud)*
   - `database.default.encrypt` = `true`
5. Klik **Deploy**.
6. Dalam 1-2 menit, website FreshWash Laundry Anda sudah online dan dapat diakses publik melalui domain Vercel (contoh: `https://freshwash-laundry.vercel.app`)!
