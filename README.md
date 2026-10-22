# BI-Kantin

Website kantin online berbasis Laravel untuk siswa, admin kantin, dan kasir.

## Fitur

- **Siswa:** daftar dan masuk menggunakan username, melihat katalog, mengelola keranjang, checkout, dan memantau status pesanan.
- **Admin:** menambah, mengubah, dan menghapus menu serta memperbarui status semua pesanan.
- **Kasir:** memvalidasi pesanan menjadi **Lunas** dan meneruskan pesanan lunas menjadi **Siap Diambil**.
- Password baru disimpan dengan hash Laravel. Password lama dari database desktop yang masih berupa teks biasa otomatis di-hash setelah login yang berhasil.

## Menjalankan aplikasi

1. Buat database MySQL bernama `db_bikantin`, lalu salin `.env.example` menjadi `.env`.
2. Buat kunci aplikasi:

   ```powershell
   php artisan key:generate
   ```

3. Atur koneksi database di `.env`:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_bikantin
   DB_USERNAME=root
   DB_PASSWORD=
   SESSION_DRIVER=file
   CACHE_STORE=file
   QUEUE_CONNECTION=sync
   ```

4. Untuk database lama dari aplikasi desktop, buat backup terlebih dahulu. Struktur awal dokumen menggunakan `password VARCHAR(50)` dan `role ENUM('admin','siswa')`; jalankan migrasi agar password dapat menyimpan hash dan role `kasir` dapat digunakan:

   ```powershell
   php artisan migrate
   ```

   Migrasi mempertahankan tabel dan isi `users`, `menu`, serta `pesanan` yang sudah ada. Tabel menu dan pesanan hanya dibuat jika belum tersedia.
5. (Opsional untuk data demo) Jalankan seeder. Akun yang sudah ada tidak akan ditimpa:

   ```powershell
   php artisan db:seed
   ```

   Akun demo baru: `admin_kantin` / `admin123`, `budi_siswa` / `123`, dan `kasir_kantin` / `kasir12345`. Segera ganti password demo sebelum aplikasi digunakan bersama.
6. Pasang aset frontend dan jalankan aplikasi:

   ```powershell
   npm install
   npm run build
   php artisan serve
   ```

## Membuat akun kasir

Registrasi publik hanya membuat akun siswa. Setelah migrasi, administrator dapat membuat akun kasir melalui Tinker:

```powershell
php artisan tinker
```

```php
\App\Models\User::create([
    'username' => 'kasir_kantin',
    'password' => 'kasir12345',
    'role' => 'kasir',
]);
```

Model otomatis meng-hash password saat menyimpan akun.

## Status pesanan

- Checkout siswa membuat satu baris `pesanan` untuk setiap menu dalam keranjang dengan status **Menunggu Konfirmasi**.
- Kasir mengubah status menjadi **Lunas**, lalu menjadi **Siap Diambil** setelah pesanan siap.
- Admin dapat mengatur status operasional **Menunggu Konfirmasi**, **Sedang Dimasak**, **Siap Diambil**, dan **Selesai**.

Struktur tabel mengikuti database desktop (`users`, `menu`, `pesanan`); gambar/deskripsi menu dan waktu pesanan belum tersedia di kolom skema lama.

## Pengujian

```powershell
php artisan test
```
