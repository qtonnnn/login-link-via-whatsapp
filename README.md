# Login Link via WhatsApp

Sistem autentikasi login tanpa password yang menggunakan link login yang dikirim melalui WhatsApp.

## Fitur

- 🔐 **Login Tanpa Password** - Tidak perlu mengingat password
- 📱 **Autentikasi via WhatsApp** - Link login dikirim ke nomor WhatsApp
- 🔒 **Keamanan Tinggi** - Token sekali pakai dengan batas waktu
- ⏱️ **Token Kedaluwarsa** - Link hanya berlaku 5 menit
- 🛡️ **Proteksi Session** - Validasi IP dan user agent

## Persyaratan

- PHP 7.3 atau lebih tinggi
- MySQL/MariaDB
- Ekstensi PHP: `mysqli`, `json`, `openssl`
- Akun Fonnte (untuk mengirim pesan WhatsApp)

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/qtonnnn/login-link-via-whatsapp.git
cd login-link-via-whatsapp
```

### 2. Konfigurasi Database

Buat database baru dan import file `dblink.sql`:

```sql
CREATE DATABASE dblink;
USE dblink;
-- Import tabel dari dblink.sql
```

### 3. Konfigurasi Environment

Salin file `.env.example` ke `.env` dan edit sesuai konfigurasi Anda:

```bash
cp .env.example .env
```

Edit file `.env` dengan konfigurasi yang benar:

```env
# Database Configuration
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=dblink

# Fonnte WhatsApp API
FONNTE_TOKEN=your_fonnte_api_token

# Application URL
APP_URL=https://yourdomain.com
SESSION_LIFETIME=3600
```

### 4. Struktur Database

Pastikan tabel berikut sudah dibuat:

```sql
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    whatsapp VARCHAR(20) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE login_token (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(64) NOT NULL,
    used TINYINT DEFAULT 0,
    expired_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
```

### 5. Install Dependencies

Tidak ada dependencies eksternal yang diperlukan. Hanya menggunakan library PHP standar.

## Penggunaan

### 1. Akses Halaman Login

Buka `login.php` di browser Anda.

### 2. Masukkan Nomor WhatsApp

Masukkan nomor WhatsApp yang sudah terdaftar.

### 3. Klik "Kirim Link Login ke WhatsApp"

Link login akan dikirim ke nomor WhatsApp Anda.

### 4. Klik Link Login

Buka WhatsApp dan klik link login yang dikirim. Anda akan otomatis login.

## Struktur File

```
├── login.php              # Halaman login
├── login_verify.php       # Verifikasi token dan set session
├── send_login_link.php    # Endpoint mengirim link login
├── session_validation.php # Helper untuk validasi session
├── koneksi.php            # Koneksi database
├── env.php                # Loader variabel environment
├── dblink.sql             # Struktur database
├── .env.example           # Contoh konfigurasi environment
└── README.md              # Dokumentasi
```

## Keamanan

### Implementasi Keamanan

1. **Prepared Statements** - Mencegah SQL Injection
2. **Token Aman** - Menggunakan `random_bytes(32)` untuk generate token
3. **Session Security** - HttpOnly, Secure, SameSite=Strict cookies
4. **Session Regeneration** - Mencegah session fixation attack
5. **Input Validation** - Validasi format nomor WhatsApp dan token
6. **Generic Error Messages** - Tidak expose informasi sensitif

### Rekomendasi Keamanan

- Gunakan HTTPS untuk production
-定期 rotate `FONNTE_TOKEN`
- Monitor log untuk aktivitas mencurigakan
- Set `SESSION_LIFETIME` sesuai kebutuhan (default: 1 jam)

## API Reference

### Send Login Link Endpoint

```http
POST /send_login_link.php
Content-Type: application/json

{
    "whatsapp": "6281234567890"
}
```

**Response Berhasil:**
```json
{
    "success": true,
    "message": "Link login telah dikirim ke nomor WhatsApp Anda"
}
```

**Response Gagal:**
```json
{
    "success": false,
    "message": "Nomor WhatsApp tidak terdaftar"
}
```

## Troubleshooting

### Token Tidak Valid

Pastikan:
- Token belum digunakan sebelumnya
- Token belum kedaluwarsa (5 menit)
- URL diakses dari browser yang sama

### WhatsApp Tidak Terkirim

Cek:
- `FONNTE_TOKEN` sudah benar di `.env`
- Nomor WhatsApp sudah terdaftar di database
- Format nomor WhatsApp benar (62xx而非0xx)

## Lisensi

MIT License

## Kontribusi

Pull requests are welcome! Untuk perubahan besar, buka issue terlebih dahulu.

## Credits

- [Fonnte](https://fonnte.com/) - WhatsApp API Provider

