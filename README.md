# 📬 SIPAS — Sistem Informasi Pengelolaan Administrasi Surat

**SIPAS** adalah aplikasi web berbasis **Laravel 11** untuk mengelola administrasi surat menyurat instansi/organisasi secara digital. Aplikasi ini mencakup pencatatan **Surat Masuk** dan **Surat Keluar** lengkap dengan fitur import/export Excel, upload lampiran, statistik dashboard, dan manajemen multi-akun admin.

---

## ✨ Fitur Utama

### 🔐 Autentikasi
- Login & Logout dengan sistem sesi yang aman
- Fitur **Remember Me** untuk sesi yang lebih panjang
- Seluruh halaman dilindungi middleware autentikasi

### 📊 Dashboard
- **Statistik ringkas**: total surat masuk & keluar (bulan ini, tahun ini, keseluruhan)
- **Grafik surat bulanan** (per tahun berjalan)
- **5 aktivitas surat terbaru** (masuk & keluar, diurutkan berdasarkan tanggal)

### 📥 Surat Masuk
Pengelolaan data surat masuk secara lengkap (CRUD):

| Field | Keterangan |
|---|---|
| Tanggal Masuk | Kapan surat diterima |
| Nomor Urut / Agenda | Auto-generate format `001/IV/2026` |
| Alamat Pengirim | Asal surat |
| Tanggal Surat | Tanggal yang tertera di surat |
| Nomor Surat | Nomor unik dari pengirim |
| Perihal | Maksud / isi surat |
| Tujuan Disposisi | Unit/divisi penerima tindak lanjut |
| Lampiran | Upload file (PDF, JPG, PNG, WEBP — maks. 10 MB) |

**Fitur tambahan:**
- 🔍 **Pencarian** berdasarkan perihal, nomor surat, nomor urut, pengirim, disposisi
- 📅 **Filter** per bulan dan tahun
- 📤 **Export Excel** sesuai filter aktif
- 📥 **Import Excel** dengan pratinjau data sebelum konfirmasi (validasi duplikat & baris tidak valid)
- 🔄 **Reset data** (kosongkan semua data surat masuk)

### 📤 Surat Keluar
Pengelolaan data surat keluar secara lengkap (CRUD):

| Field | Keterangan |
|---|---|
| Tanggal Keluar | Kapan surat dikirim |
| Nomor Urut / Agenda | Auto-generate format `001/IV/2026` |
| Alamat Penerima | Tujuan surat |
| Tanggal Surat | Tanggal yang tertera di surat |
| Nomor Surat | Nomor unik surat |
| Perihal | Maksud / isi surat |
| Asal | Unit/divisi pembuat surat |
| Lampiran | Upload file (PDF, JPG, PNG, WEBP — maks. 10 MB) |

**Fitur tambahan:** Sama seperti Surat Masuk (pencarian, filter, export, import, reset).

### 👤 Manajemen Akun (Profil)
- Edit **nama** dan **email** akun sendiri
- Ubah **password** (dengan verifikasi password lama)
- **Tambah admin** baru (multi-akun)
- **Hapus akun** admin lain (tidak bisa menghapus akun sendiri)

---

## 💻 Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | Laravel 11 (PHP ^8.2) |
| Frontend Bundler | Vite |
| CSS Framework | Tailwind CSS |
| Database (default) | SQLite |
| Import / Export | maatwebsite/excel ^3.1 |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### Prasyarat
Pastikan komputer Anda sudah terinstal:
- **PHP** >= 8.2 (dengan ekstensi `sqlite3`, `pdo_sqlite`, `fileinfo`, `gd`)
- **Composer**
- **Node.js** & **npm**
- **Git**

---

### 1. Clone Repositori

```bash
git clone https://github.com/Kustina29/Sistemdatasurat.git
cd Sistemdatasurat
```

---

### 2. Install Dependensi

```bash
composer install
npm install
```

---

### 3. Konfigurasi Environment

Salin file contoh `.env` lalu generate app key:

```bash
# Windows (CMD/PowerShell)
copy .env.example .env

# Linux / macOS
cp .env.example .env

php artisan key:generate
```

> **Catatan:** Proyek ini menggunakan **SQLite** secara default. Pastikan di file `.env` hanya ada `DB_CONNECTION=sqlite` dan baris konfigurasi `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` tidak ada atau sudah dihapus.

---

### 4. Buat Database & Jalankan Migrasi

```bash
php artisan migrate
```

> Jika muncul konfirmasi untuk membuat file `database/database.sqlite`, pilih **Yes**.

---

### 5. Buat Symlink Storage (untuk Lampiran)

```bash
php artisan storage:link
```

---

### 6. Buat Akun Admin Pertama

Gunakan Tinker untuk membuat akun admin pertama:

```bash
php artisan tinker
```

Kemudian jalankan perintah berikut di dalam Tinker:

```php
\App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@example.com',
    'password' => bcrypt('password123'),
]);
exit
```

> Ganti `name`, `email`, dan `password` sesuai kebutuhan. Setelah itu Anda bisa menambah akun admin lainnya langsung dari halaman **Profil** di aplikasi.

---

### 7. Menjalankan Aplikasi

**Cara Cepat (semua sekaligus — direkomendasikan):**

```bash
composer run dev
```

Perintah ini secara otomatis menjalankan server Laravel, queue listener, log viewer (Pail), dan Vite frontend sekaligus dalam satu terminal.

---

**Cara Manual (dua terminal terpisah):**

**Terminal 1 — Vite (Frontend):**
```bash
npm run dev
```

**Terminal 2 — Laravel (Backend):**
```bash
php artisan serve
```

> Jika port `8000` sudah terpakai, gunakan: `php artisan serve --port=8080`

---

Akses aplikasi melalui browser di: **[http://localhost:8000](http://localhost:8000)**

---

## 📁 Struktur Modul Utama

```
app/
├── Http/Controllers/
│   ├── AuthController.php            # Login & Logout
│   ├── DashboardController.php       # Statistik & grafik
│   ├── SuratMasukController.php      # CRUD Surat Masuk
│   ├── SuratMasukImportController.php # Import Excel Surat Masuk
│   ├── SuratKeluarController.php     # CRUD Surat Keluar
│   ├── SuratKeluarImportController.php # Import Excel Surat Keluar
│   └── ProfileController.php         # Manajemen akun admin
resources/views/
├── dashboard.blade.php               # Halaman dashboard
├── surat_masuk/                      # Views surat masuk
├── surat_keluar/                     # Views surat keluar
├── profile/                          # Halaman profil & manajemen user
└── auth/                             # Halaman login
```

---

## 🌐 Deploy ke Server (Production dengan MySQL)

Saat deploy ke server hosting/VPS, ganti konfigurasi database dari SQLite ke **MySQL**.

### 1. Buat Database MySQL di Server

Masuk ke MySQL lalu buat database baru:
```sql
CREATE DATABASE sipas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Sesuaikan File `.env` di Server

Ubah bagian database di `.env` menjadi:
```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipas_db
DB_USERNAME=user_mysql_anda
DB_PASSWORD=password_mysql_anda
```

> **Penting:** Pastikan `APP_DEBUG=false` saat production agar pesan error tidak tampil ke publik.

### 3. Jalankan Migrasi

```bash
php artisan migrate --force
```

Flag `--force` diperlukan karena environment `production` Laravel meminta konfirmasi.

### 4. Optimasi untuk Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 5. Build Asset Frontend

```bash
npm run build
```

> Di server production, **tidak perlu** menjalankan `npm run dev` atau `php artisan serve`. Server web (Apache/Nginx) langsung mengarahkan ke folder `public/`.

---

### ⚠️ Catatan Penting Kompatibilitas Database

Kode aplikasi ini telah dirancang kompatibel dengan **SQLite** (lokal) maupun **MySQL** (production):
- Query statistik dashboard menggunakan Eloquent `whereMonth()` — bukan `strftime` SQLite
- Fungsi reset data menggunakan `match(DB::getDriverName())` untuk menentukan syntax yang tepat per database

---

## 📄 Format Template Import Excel

Untuk fitur **Import Excel**, gunakan kolom berikut (baris pertama adalah header):

**Surat Masuk:**
| tanggal_masuk_surat | nomor_urut | alamat_pengirim | tanggal_surat | nomor_surat | perihal | tujuan_disposisi |
|---|---|---|---|---|---|---|

**Surat Keluar:**
| tanggal_keluar_surat | nomor_urut | alamat_penerima | tanggal_surat | nomor_surat | perihal | asal |
|---|---|---|---|---|---|---|

> Format tanggal: `YYYY-MM-DD` (contoh: `2026-04-25`)

---

**Semoga berhasil dan selamat mengembangkan SIPAS! 🚀**
