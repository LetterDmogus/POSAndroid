# 🚀 API POS System Documentation

Dokumentasi ini berisi daftar lengkap endpoint API untuk sistem POS (Point of Sale) berbasis QR Code yang dibangun menggunakan **Laravel 13** dan **Sanctum Authentication**.

---

## 🛠️ Info Framework & Setup
- **Framework:** Laravel 13 (PHP 8.3+)
- **Authentication:** Laravel Sanctum (Bearer Token)
- **Database:** MySQL / SQLite
- **Base URL (Local):** `http://apipos.test/api/` (Herd) atau `http://10.0.2.2:8000/api/` (Android Emulator)

---

## 🔐 Authentication
Semua request (kecuali Login & Hello) **WAJIB** menyertakan Header:
`Authorization: Bearer <YOUR_TOKEN>`
`Accept: application/json`

### 1. Login
- **Endpoint:** `POST /api/login`
- **Request Body:**
  ```json
  {
    "email": "admin@pos.com",
    "password": "admin123"
  }
  ```
- **Response Sukses:**
  ```json
  {
    "success": true,
    "token": "1|abcdef12345...",
    "user": { "id": 1, "name": "Admin", "role": "admin" }
  }
  ```

### 2. Logout
- **Endpoint:** `POST /api/logout`
- **Auth Required:** Yes

---

## 📦 Produk (Barang)
Endpoint untuk mengelola stok dan mencari barang via QR Code.

### 1. List & Search Barang
- **Endpoint:** `GET /api/barangs`
- **Query Params (Opsional):**
  - `search`: Cari berdasarkan Nama atau SKU (Contoh: `?search=Kopi`)
  - `category_id`: Filter per kategori (Contoh: `?category_id=1`)
- **Response:** Menyertakan `count` dan `total_omzet` jika difilter.

### 2. Scan QR Code (by SKU)
- **Endpoint:** `GET /api/barangs/scan/{sku}`
- **Contoh:** `/api/barangs/scan/MNM-001`
- **Fungsi:** Mengambil data barang tunggal berdasarkan isi QR Code (SKU).

### 3. CRUD Barang (Standard)
- `POST /api/barangs` : Tambah barang (Body: `sku, nama_barang, harga_beli, harga_jual, category_id, stok, satuan`)
- `GET /api/barangs/{id}` : Detail barang.
- `PUT /api/barangs/{id}` : Update barang.
- `DELETE /api/barangs/{id}` : Hapus barang.

---

## 📂 Kategori
- **GET /api/categories** : List semua kategori + `barangs_count`.
- **POST /api/categories** : Tambah kategori (`nama_kategori`).
- **GET /api/categories/{id}** : Detail kategori + daftar barang di dalamnya.
- **PUT /api/categories/{id}** : Update kategori.
- **DELETE /api/categories/{id}** : Hapus kategori (hanya jika kosong).

---

## 🛒 Transaksi (Order)

### 1. Simpan Transaksi (Checkout)
- **Endpoint:** `POST /api/orders`
- **Request Body:**
  ```json
  {
    "bayar": 50000,
    "metode_pembayaran": "Tunai",
    "catatan": "Pesanan khusus",
    "items": [
      { "barang_id": 1, "qty": 2 },
      { "barang_id": 2, "qty": 1 }
    ]
  }
  ```
- **Logic:** Otomatis potong stok, hitung kembalian, dan generate nomor invoice.

### 2. Laporan Penjualan (Date Range)
- **Endpoint:** `GET /api/orders`
- **Query Params:**
  - `start_date`: Format `YYYY-MM-DD`
  - `end_date`: Format `YYYY-MM-DD`
- **Response:** Menyertakan `total_omzet` (Total pendapatan dalam rentang tanggal).

### 3. Cetak Struk (Invoice)
- **Endpoint:** `GET /api/invoice/{nomor_invoice}`
- **Contoh:** `/api/invoice/INV-20260413-A1B2C3`
- **Response:** Data rapi yang terbagi menjadi `header`, `items`, dan `summary`.

---

## 🧪 Akun Testing (Default Seeder)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@pos.com` | `admin123` |
| **Kasir** | `kasir@pos.com` | `kasir123` |

---

## 📱 Catatan untuk Android Studio
- **AndroidManifest.xml:** Tambahkan `INTERNET` permission dan `usesCleartextTraffic="true"`.
- **Base URL:** Gunakan `http://10.0.2.2:8000/api/` untuk Emulator.
- **Networking:** Sangat disarankan menggunakan library **Retrofit** dengan **GsonConverter**.
- **Security:** Simpan `token` hasil login di `EncryptedSharedPreferences`.
