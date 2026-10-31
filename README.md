# Aplikasi Gudang

Aplikasi inventaris sederhana untuk mengelola kategori, satuan, barang, dan transaksi barang masuk/keluar. Aplikasi menggunakan CodeIgniter 4.

## Persyaratan

- PHP yang memenuhi `composer.json` beserta ekstensi `intl`, `curl`, `json`, dan `mbstring`
- MySQL/MariaDB
- Composer
- PHP `fileinfo` extension (for validating and serving uploaded images)

## Instalasi

1. Pasang dependensi dengan `composer install`.
2. Salin `env` menjadi `.env` (PowerShell: `Copy-Item env .env`), lalu isi konfigurasi database (`database.default.*`).
3. Buat password hash admin:

   ```sh
   php -r "echo password_hash('ganti-password-ini', PASSWORD_DEFAULT), PHP_EOL;"
   ```

4. Atur kredensial dalam `.env`:

   ```ini
   auth.adminUsername = 'admin'
   auth.adminPasswordHash = '$2y$...'
   ```

   Gunakan password unik yang kuat. Jangan commit `.env` atau membagikan hash/kredensial. Aplikasi menolak login dan menampilkan status layanan tidak tersedia sampai kedua nilai tersebut dikonfigurasi.

5. Jalankan migrasi dengan `php spark migrate`.
6. Arahkan document root web server ke folder `public`, bukan root repository.
7. Login melalui `/login`.

Untuk database lama, migrasi terbaru menambahkan kolom stok bila belum ada, mengubah harga menjadi `DECIMAL`, dan membuat tabel transaksi. Cadangkan database sebelum menjalankan migrasi.

## Transaksi dan file

Transaksi memilih barang, jenis masuk/keluar, dan jumlah. Harga diambil dari data barang, stok diperbarui dalam transaksi database, dan barang keluar ditolak bila stok tidak mencukupi. Riwayat disimpan pada tabel `transaksi` dan `transaksi_detail`.

Gambar barang JPG/PNG (maksimal 2 MB) disimpan di `writable/uploads/barang`, tidak di direktori publik. Gambar dilayani melalui route aplikasi yang memerlukan login.

## Keamanan

Semua halaman pengelolaan memerlukan sesi administrator. Permintaan perubahan data dilindungi CSRF. Kredensial admin hanya diambil dari environment; tidak ada password bawaan. Gunakan HTTPS di deployment produksi.
