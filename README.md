# Toko Online CodeIgniter 4

Proyek ini adalah platform toko online berbasis web yang dibangun menggunakan framework [CodeIgniter 4](https://codeigniter.com/). Sistem ini menyediakan fitur lengkap untuk kebutuhan toko online, mulai dari katalog produk, keranjang belanja, transaksi, hingga panel admin untuk manajemen data. Proyek ini juga sudah dilengkapi sistem autentikasi dan tampilan responsif berbasis template NiceAdmin.

## Fitur

- **Katalog Produk**
  - Menampilkan daftar produk beserta gambar, harga, dan deskripsi.
  - Fitur pencarian produk berdasarkan nama atau kategori.
- **Keranjang Belanja**
  - Pengguna dapat menambah, mengurangi, atau menghapus produk dari keranjang.
  - Update jumlah produk secara dinamis.
- **Transaksi**
  - Proses checkout dengan pengisian data pembeli dan alamat.
  - Riwayat transaksi untuk pengguna.
- **Panel Admin**
  - CRUD produk: tambah, edit, hapus, dan lihat detail produk.
  - Manajemen kategori produk.
  - Laporan transaksi dan export data ke PDF.
  - Manajemen diskon harian.
- **Sistem Autentikasi**
  - Login dan register pengguna.
  - Manajemen akun dan hak akses (admin/user).
- **Notifikasi**
  - Notifikasi sukses/error pada setiap aksi penting (login, transaksi, CRUD).
- **UI Responsif**
  - Menggunakan template NiceAdmin untuk tampilan modern dan mobile-friendly.

## Instalasi

1. **Clone repository**
   ```bash
   git clone [URL repository]
   cd radit-ci
   ```
2. **Install dependensi**
   ```bash
   composer install
   ```
3. **Konfigurasi database**
   - Jalankan XAMPP, aktifkan Apache & MySQL.
   - Buat database, misal: `db_ci4` di phpMyAdmin.
   - Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database:
     ```
     database.default.hostname = localhost
     database.default.database = db_ci4_reno
     database.default.username = root
     database.default.password =
     database.default.DBDriver = MySQLi
     ```
4. **Migrasi dan seeder database**
   ```bash
   php spark migrate
   php spark db:seed ProductSeeder
   php spark db:seed ProductCategorySeeder
   php spark db:seed UserSeeder
   ```
5. **Jalankan server**
   ```bash
   php spark serve
   ```
6. **Akses aplikasi**
   - Buka browser dan akses [http://localhost:8080](http://localhost:8080)

## Struktur Proyek

Struktur utama proyek mengikuti pola MVC CodeIgniter 4:

```
app/
  Controllers/
    AuthController.php            # Login, register, logout, session
    DiskonController.php          # Manajemen diskon harian
    ProductCategoryController.php # CRUD kategori produk
    ProdukController.php          # CRUD produk
    TransaksiController.php       # Proses transaksi & riwayat
    ContactController.php         # Form kontak
    Home.php                      # Halaman utama
  Models/
    UserModel.php                 # Model user
    ProductModel.php              # Model produk
    ProductCategoryModel.php      # Model kategori produk
    DiskonModel.php               # Model diskon
    TransactionModel.php          # Model transaksi
    TransactionDetailModel.php    # Model detail transaksi
  Views/
    layout.php                    # Layout utama
    v_login.php                   # Halaman login
    v_produk.php                  # Daftar produk
    v_kategori.php                # Daftar kategori
    v_keranjang.php               # Keranjang belanja
    v_checkout.php                # Checkout
    v_diskon.php                  # Manajemen diskon
    v_contact.php                 # Kontak
    v_home.php                    # Beranda
    v_profile.php                 # Profil user
public/
  img/                            # Gambar produk
  NiceAdmin/                      # Template admin
.env                              # Konfigurasi environment
composer.json                     # Dependensi PHP
```

> **Catatan:**  
> - Pastikan semua migrasi dan seeder dijalankan agar database terisi data awal.
> - Untuk login admin, gunakan user yang sudah di-seed atau buat user baru lewat phpMyAdmin.
