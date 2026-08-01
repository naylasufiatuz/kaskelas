# KasKelas — Sistem Pengelolaan Keuangan Kelas

**Kelola Kas Kelas dengan Mudah dan Transparan**

Aplikasi web berbasis Laravel untuk bendahara kelas mencatat kas mingguan, pemasukan, pengeluaran, dan menghasilkan laporan keuangan — dengan role bendahara, ketua kelas, dan siswa.

## Stack

Laravel 11, PHP 8.2+, MySQL, Blade, CSS custom (tanpa framework frontend berat), Chart.js (CDN, untuk grafik saja), dompdf (export laporan PDF).

No `npm run build` required — CSS/JS are served directly from `public/css` and `public/js`, no bundler step.

## 1. Persiapan

- PHP 8.2+ dengan ekstensi: mbstring, pdo_mysql, openssl, tokenizer, xml, ctype, json, gd
- Composer
- MySQL 8+ (atau MariaDB 10.6+)

## 2. Instalasi

```bash
# 1. Buat database MySQL kosong terlebih dahulu, misal:
#    CREATE DATABASE kaskelas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Isi .env — minimal ubah bagian berikut sesuai kredensial MySQL Anda:
#    DB_DATABASE=kaskelas
#    DB_USERNAME=root
#    DB_PASSWORD=your_password

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migration + seeder
php artisan migrate --seed

# 7. Buat symbolic link storage (untuk logo kelas)
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

Buka `http://localhost:8000`.

## 3. Akun Login Awal

Dibuat otomatis oleh seeder (`database/seeders/UserSeeder.php`):

| Role | Email | Password Sementara |
|---|---|---|
| Bendahara | bendahara@kaskelas.test | `Bendahara#2026` |
| Ketua Kelas | ketua@kaskelas.test | `KetuaKelas#2026` |

**Kedua akun wajib mengganti password saat login pertama** — sistem akan otomatis mengarahkan ke halaman ganti password (`must_change_password` flag).

Akun siswa dibuat manual oleh bendahara melalui Tinker atau langsung di database, lalu dihubungkan ke data siswa (`student_id`) — lihat bagian 5.

## 4. Cara Mengubah Nominal Kas & Tanggal Mulai

Login sebagai bendahara → menu **Settings**:
- **Nominal Kas Mingguan** — default Rp5.000, bisa diubah kapan saja. Perubahan berlaku untuk minggu berjalan dan seterusnya; riwayat pembayaran yang sudah tercatat tidak ikut berubah.
- **Tanggal Mulai Kas** — default 27 Juli 2026 (Senin), menentukan Week 1. Sebaiknya tidak diubah setelah ada data pembayaran, karena akan menggeser perhitungan minggu-minggu berikutnya.

Kedua nilai juga bisa diatur awal lewat `.env` (`KASKELAS_WEEKLY_AMOUNT`, `KASKELAS_START_DATE`) sebelum seeding pertama kali.

## 5. Cara Memasukkan Daftar Siswa

1. Login sebagai bendahara → menu **Data Siswa** → **+ Tambah Siswa**, isi nama & NIS satu per satu. Sistem otomatis menghitung target kas mingguan berdasarkan jumlah siswa **aktif**.
2. (Opsional) Buat akun login untuk tiap siswa lewat `php artisan tinker`:
   ```php
   $student = \App\Models\Student::where('name', 'Nama Siswa')->first();
   \App\Models\User::create([
       'name' => $student->name,
       'email' => 'siswa1@kaskelas.test',
       'password' => 'PasswordAwal123',
       'role' => 'student',
       'student_id' => $student->id,
       'must_change_password' => true,
   ]);
   ```
   Siswa tanpa akun login tetap terhitung dalam target kas, hanya belum bisa login melihat status kasnya sendiri.

## 6. Alur Kerja Bendahara (tercepat)

1. Login → Dashboard langsung menampilkan saldo & siapa yang belum bayar minggu ini.
2. Menu **Pembayaran Kas** → pilih minggu → klik **Tandai Lunas** per siswa (otomatis membuat transaksi pemasukan, tidak bisa duplikat).
3. Menu **Transaksi** → **+ Pengeluaran** untuk mencatat belanja kelas, upload bukti/nota.
4. Menu **Laporan** → filter periode → **Export PDF** untuk laporan bulanan.

## 7. Keamanan yang Diterapkan

- Autentikasi Laravel bawaan, password di-hash (bcrypt via `'password' => 'hashed'` cast).
- Semua endpoint sensitif dilindungi Policy + Gate di backend (`app/Policies/*`), **bukan** hanya menyembunyikan tombol di frontend — mencoba akses `/transactions/{id}/edit` langsung sebagai siswa akan mendapat `403 Forbidden`.
- Middleware `role:treasurer` mengunci route Settings & Activity Log di level route.
- Siswa hanya bisa melihat data pembayaran kasnya sendiri (`CashPaymentPolicy`, di-scope ke `Auth::id()`, mencegah IDOR lewat mengganti ID di URL).
- Saldo **tidak pernah** disimpan sebagai angka manual — selalu dihitung ulang dari `SUM(income) - SUM(expense)` (`BalanceService`).
- Satu siswa tidak bisa punya dua transaksi pemasukan untuk minggu yang sama (unique constraint `student_id + week_start` di tabel `cash_payments`, plus pengecekan transaksi terkait sebelum membuat baru).
- Pengeluaran yang akan membuat saldo minus divalidasi di backend, bukan hanya di frontend.
- CSRF protection aktif (bawaan Laravel), file upload dibatasi tipe (jpg/png/pdf) dan ukuran (4MB), disimpan di disk privat (`storage/app/private`), diakses lewat route yang tetap dicek Policy — bukan folder publik langsung.
- Semua create/update/delete penting tercatat ke `activity_logs` beserta `old_values`/`new_values`.

## 8. Struktur Folder

```
app/
  Http/Controllers/      -> Dashboard, Student, CashPayment, Transaction, Report, ActivityLog, Setting, Auth/Login
  Http/Middleware/        -> EnsureUserHasRole, ForcePasswordChange
  Models/                 -> User, Student, CashPayment, Transaction, Category, ActivityLog, Setting
  Policies/                -> StudentPolicy, TransactionPolicy, CashPaymentPolicy, ActivityLogPolicy
  Services/                -> WeekService (perhitungan minggu kas), BalanceService (saldo live), ActivityLogService
database/
  migrations/
  seeders/                 -> CategorySeeder, SettingSeeder, UserSeeder
resources/
  views/                   -> layouts, components, auth, dashboard, students, cash-payments, transactions, reports, activity-logs, settings
  css/app.css, js/app.js   -> juga disalin ke public/css & public/js (tanpa build step)
routes/web.php
```

## 9. Catatan Penting

- Tidak ada data dummy sebagai sistem utama — seeder hanya membuat kategori default, pengaturan default, dan 2 akun staf awal. Data siswa & transaksi harus dimasukkan nyata setelah instalasi.
- Format Rupiah (`Rp5.000`, bukan `5000`) ditangani oleh helper global `rupiah()` di `app/helpers.php`.
- Untuk export PDF, pastikan `barryvdh/laravel-dompdf` sudah ter-install lewat `composer install` (sudah ada di `composer.json`).
