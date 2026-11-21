# QuickOrder POS — Panduan Setup Laravel

## Akun Login Default
| Nama         | Username | Password    | Role    |
|--------------|----------|-------------|---------|
| Administrator| admin    | admin123    | Admin   |
| Kasir Utama  | kasir    | kasir123    | Kasir   |
| Staf Dapur   | kitchen  | kitchen123  | Kitchen |

---

## Langkah Install

### 1. Buat project Laravel baru
```bash
composer create-project laravel/laravel quickorder
cd quickorder
```

### 2. Salin semua file dari zip ini ke dalam folder project
Timpa file yang ada (kecuali `.env`).

### 3. Setting database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quickorder_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat database
```sql
CREATE DATABASE quickorder_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan migration dan seeder
```bash
php artisan migrate --seed
```

### 6. Generate app key (jika belum ada)
```bash
php artisan key:generate
```

### 7. Jalankan server
```bash
php artisan serve
```

Akses di: **http://localhost:8000**

---

## Struktur Halaman

| URL             | Role    | Keterangan              |
|-----------------|---------|-------------------------|
| `/`             | Semua   | Halaman login           |
| `/admin/menu`   | Admin   | Kelola menu & stok      |
| `/admin/users`  | Admin   | Kelola akun staf        |
| `/admin/laporan`| Admin   | Laporan penjualan       |
| `/kasir`        | Kasir   | Sistem POS (transaksi)  |
| `/kitchen`      | Kitchen | Layar dapur (antrean)   |

---

## Catatan Teknis
- **Framework**: Laravel 11
- **PHP**: >= 8.2
- **Database**: MySQL / MariaDB
- **Auth**: Session-based, role-based middleware
- **Realtime**: Polling setiap 5 detik (tidak butuh WebSocket)
- **Frontend**: Pure CSS + Vanilla JS (tidak butuh npm/node)
