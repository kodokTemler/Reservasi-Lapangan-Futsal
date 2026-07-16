# Futsal SK-13 - Aplikasi Reservasi Lapangan Futsal

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-2.2-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Midtrans](https://img.shields.io/badge/Midtrans-Payment-FF3F2D?style=for-the-badge&logo=m midtrans&logoColor=white)](https://www.midtrans.com/)

Aplikasi web pemesanan/reservasi lapangan futsal secara online yang dibangun menggunakan PHP dan MySQL. Aplikasi ini memungkinkan pengguna untuk melihat daftar lapangan, melakukan pemesanan, melakukan pembayaran via Midtrans, serta melihat riwayat pesanan. Panel admin tersedia untuk mengelola seluruh data aplikasi.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Struktur Proyek](#struktur-proyek)
- [Instalasi & Setup](#instalasi--setup)
- [Konfigurasi](#konfigurasi)
- [Akses Aplikasi](#akses-aplikasi)
- [Database](#database)
- [License](#license)

---

## Fitur Utama

### Pengguna (User)

- **Daftar & Login** dengan verifikasi email
- **Jelajahi Lapangan** lengkap dengan foto, jenis, lokasi, harga, dan status ketersediaan
- **Pesan Lapangan** dengan pemilihan tanggal, jam mulai, dan jam selesai
- **Pembayaran Online** via Midtrans (e-wallet, kartu kredit, transfer bank)
- **Lihat Riwayat Pesanan** dan status pembayaran
- **Kirim Ulasan** atau pesan ke pengelola lapangan

### Admin

- **Dashboard** dengan ringkasan statistik (total admin, user, lapangan, pesanan) dan grafik pendapatan bulanan
- **Kelola Lapangan** (CRUD) — tambah, edit, hapus, unggah foto lapangan
- **Kelola Pengguna** — lihat, edit, dan hapus akun pengguna
- **Kelola Pesanan** — lihat, ubah status, edit, dan hapus pemesanan
- **Kelola Pembayaran** — lihat, konfirmasi, hapus, dengan filter rentang tanggal
- **Kelola Ulasan** — lihat dan hapus ulasan dari pengguna
- **Kelola Akun Admin** — tambah, edit, dan hapus akun admin

---

## Tech Stack

| Komponen | Teknologi |
|---|---|
| **Backend** | PHP 8.3 (vanilla/procedural) |
| **Database** | MySQL 8.0 |
| **Frontend (User)** | Tailwind CSS 2.2, Animate.css |
| **Frontend (Admin)** | Bootstrap 5, KaiAdmin Template |
| **JavaScript** | jQuery 3.7, DataTables, SweetAlert2, Chart.js, FullCalendar |
| **Dependency Manager** | Composer |
| **Payment Gateway** | Midtrans Snap API |
| **Email** | PHPMailer (SMTP) |
| **Icons** | Font Awesome 6 |
| **Font** | Google Fonts (Poppins) |

---

## Struktur Proyek

```
AplikasiKonsentrasi/
|
|-- index.php                          # Halaman login/registrasi pengguna
|-- composer.json                      # Dependensi Composer
|-- lapanganbola.sql                   # File dump database (schema + data)
|
|-- controller/                        # Logika backend (PHP)
|   |-- koneksi.php                    # Koneksi database (MySQL)
|   |-- login.php                      # Handler login admin
|   |-- loginUser.php                  # Registrasi + login pengguna (verifikasi email)
|   |-- verify.php                     # Verifikasi email pengguna
|   |-- CrudAdmin.php                  # CRUD akun admin
|   |-- CrudUser.php                   # CRUD pengguna
|   |-- CrudLapangan.php               # CRUD lapangan futsal
|   |-- CrudPemesanan.php              # CRUD pemesanan
|   |-- CrudPembayaran.php             # CRUD pembayaran
|   |-- CrudUlasan.php                 # CRUD ulasan
|   |-- UserPemesanan.php              # Logika pemesanan pengguna
|   |-- create_snap_token.php          # Endpoint pembuatan token Midtrans Snap
|   |-- midtrans_notification.php      # Webhook notifikasi Midtrans
|
|-- admin/                             # Panel admin
|   |-- index.php                      # Login admin
|   |-- dashboard.php                  # Dashboard (statistik + grafik)
|   |-- admin.php                      # Manajemen akun admin
|   |-- user.php                       # Manajemen pengguna
|   |-- lapangan.php                   # Manajemen lapangan
|   |-- pemesanan.php                  # Manajemen pemesanan
|   |-- daftarPembayaran.php           # Daftar pembayaran
|   |-- ulasan.php                     # Manajemen ulasan
|   |-- logout.php                     # Logout admin
|   |-- components/                    # Komponen PHP (sidebar, header, footer)
|   |-- assets/                        # Aset statis (CSS, JS, gambar, font)
|
|-- user/                              # Tampilan pengguna
|   |-- index.php                      # Beranda pengguna (hero, lapangan, kontak)
|   |-- userPemesanan.php              # Form pemesanan
|   |-- pembayaran.php                 # Halaman pembayaran Midtrans
|   |-- daftarPesanan.php              # Daftar riwayat pesanan
|   |-- logout.php                     # Logout pengguna
|   |-- assets/                        # Aset statis (CSS, gambar)
|
|-- vendor/                            # Direktori Composer (otomatis)
```

---

## Instalasi & Setup

### Prasyarat

- [Laragon](https://laragon.org/) atau stack server lokal lainnya (PHP 8.3+, MySQL 8.0, Apache)
- [Composer](https://getcomposer.org/) terinstall

### Langkah Instalasi

**1. Clone/Download proyek ke direktori server lokal**

```bash
# Jalankan di direktori web server (misal: C:\laragon\www\)
git clone <url-repositori> AplikasiKonsentrasi
```

**2. Install dependensi Composer**

```bash
cd AplikasiKonsentrasi
composer install
```

Ini akan menginstall `phpmailer/phpmailer` dan `midtrans/midtrans-php` ke direktori `vendor/`.

**3. Buat database**

Buka phpMyAdmin atau klien MySQL lainnya, lalu buat database baru bernama `lapanganbola`.

**4. Import database**

Import file `lapanganbola.sql` ke database `lapanganbola`. File ini berisi seluruh struktur tabel dan data awal (termasuk 6 lapangan sample).

**5. Konfigurasi koneksi database**

Buka file `controller/koneksi.php` dan pastikan konfigurasi sesuai:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lapanganbola";
```

**6. Konfigurasi email (untuk verifikasi registrasi)**

Buka file `controller/loginUser.php` dan isi kredensial SMTP (Mailtrap atau provider lainnya).

**7. Konfigurasi Midtrans Payment Gateway**

Buka file `controller/create_snap_token.php` dan `controller/midtrans_notification.php`, lalu isi:

```php
$serverKey = 'SB-Mid-server-xxxxx';
$clientKey = 'SB-Mid-client-xxxxx';
$isProduction = false; // Gunakan sandbox untuk testing
```

---

## Konfigurasi

| File | Fungsi |
|---|---|
| `controller/koneksi.php` | Koneksi ke database MySQL |
| `controller/loginUser.php` | Konfigurasi SMTP email (PHPMailer) |
| `controller/create_snap_token.php` | Server key & client key Midtrans |
| `controller/midtrans_notification.php` | Webhook handler notifikasi pembayaran |

---

## Akses Aplikasi

| Halaman | URL |
|---|---|
| **Login/Registrasi Pengguna** | `http://localhost/Reservasi-Lapangan-Futsal/index.php` |
| **Panel Admin** | `http://localhost/Reservasi-Lapangan-Futsal/admin/index.php` |

### Akun Default Admin

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `admin` |

> **Catatan:** Segera ubah password admin setelah pertama kali login untuk keamanan.

---

## Database

Aplikasi ini menggunakan 6 tabel utama:

| Tabel | Keterangan |
|---|---|
| `tb_admin` | Akun admin (id, username, password) |
| `tb_user` | Data pengguna terdaftar (id, nama, email, no. telepon, password) |
| `tb_lapangan` | Data lapangan futsal (id, nama, jenis, lokasi, harga/jam, status, foto) |
| `tb_pesanan` | Data pemesanan (id, user, lapangan, tanggal, jam, total, status) |
| `tb_pembayaran` | Data pembayaran (id, pesanan, user, status, total, metode, bukti, snap token) |
| `tb_ulasan` | Ulasan/feedback dari pengguna (id, nama, email, telepon, ulasan) |

---

## License

MIT License

Copyright (c) 2025 Futsal SK-13

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
