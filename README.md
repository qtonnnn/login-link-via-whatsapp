# 🔐 Login Link via WhatsApp

Sistem autentikasi login tanpa password yang menggunakan link login yang dikirim melalui WhatsApp. Tidak perlu mengingat password, cukup masukkan nomor WhatsApp dan klik link yang dikirim!

![License](https://img.shields.io/badge/License-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.3%2B-777BB4.svg)

## ✨ Fitur

- 🔓 **Tanpa Password** - Tidak perlu mengingat atau mengetik password
- 📱 **Login via WhatsApp** - Link login dikirim langsung ke WhatsApp Anda
- ⚡ **Cepat & Mudah** - Hanya 2 klik untuk login
- 🔒 **Aman** - Token sekali pakai dengan batas waktu
- 🛡️ **Proteksi Session** - Validasi IP dan user agent
- 🇮🇩 **Full Bahasa Indonesia** - Interface dan dokumentasi dalam bahasa Indonesia

## 📋 Persyaratan

| Persyaratan | Minimum | Direkomendasikan |
|-------------|---------|------------------|
| PHP | 7.3+ | 8.0+ |
| MySQL/MariaDB | 5.7+ | 10.x+ |
| Ekstensi PHP | mysqli, json, openssl | mysqli, json, openssl, mbstring |

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/qtonnnn/login-link-via-whatsapp.git
cd login-link-via-whatsapp
```

### 2. Konfigurasi Database

Buat database baru dan import struktur tabel:

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE dblink;

# Import struktur
USE dblink;
SOURCE dblink.sql;
```

### 3. Konfigurasi Environment

Salin file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dengan konfigurasi Anda:

```env
# ===== Database Configuration =====
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=password_anda
DB_NAME=dblink

# ===== Fonnte WhatsApp API =====
# Dapatkan token di https://fonnte.com/
FONNTE_TOKEN=your_fonnte_api_token_here

# ===== Application Settings =====
APP_URL=https://domain-anda.com
APP_ENV=development
SESSION_LIFETIME=3600
```

### 4. Konfigurasi WhatsApp (Fonnte)

1. Daftar di [Fonnte](https://fonnte.com/)
2. Dapatkan API Token dari dashboard
3. Masukkan token di file `.env`

## 📖 Cara Penggunaan

### Langkah 1: Akses Halaman Login

Buka `login.php` di browser Anda.

```
https://domain-anda.com/login.php
```

### Langkah 2: Masukkan Nomor WhatsApp

Masukkan nomor WhatsApp yang sudah terdaftar di sistem.

### Langkah 3: Klik Kirim Link Login

Link login akan dikirim ke nomor WhatsApp Anda.

### Langkah 4: Klik Link Login

Buka WhatsApp dan klik link login yang dikirim. Anda akan otomatis masuk ke sistem!

## 📁 Struktur File

```
login-link-via-whatsapp/
├── 📄 login.php              # Halaman login utama
├── 📄 login_verify.php       # Verifikasi token & set session
├── 📄 send_login_link.php    # Endpoint API mengirim link
├── 📄 session_validation.php # Helper validasi session
├── 📄 koneksi.php            # Koneksi database
├── 📄 env.php                # Loader environment variables
├── 📄 dblink.sql             # Struktur database
├── 📄 .env.example           # Contoh konfigurasi
└── 📄 README.md              # Dokumentasi ini
```

## 🔌 API Reference

### Endpoint: Kirim Link Login

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

**Response Error:**
```json
{
    "success": false,
    "message": "Nomor WhatsApp tidak terdaftar"
}
```

## 🔒 Keamanan

Sistem ini mengimplementasikan berbagai fitur keamanan:

| Fitur | Deskripsi |
|-------|-----------|
| **Prepared Statements** | Mencegah SQL Injection |
| **Secure Token** | Menggunakan `random_bytes(32)` |
| **Session Security** | HttpOnly, Secure, SameSite=Strict |
| **Session Regeneration** | Mencegah session fixation |
| **Input Validation** | Validasi format input |
| **Generic Errors** | Tidak expose informasi sensitif |
| **Timeout Token** | Token expired dalam 5 menit |

### Rekomendasi Keamanan

1. **Selalu gunakan HTTPS** di production
2. **定期 rotate** `FONNTE_TOKEN` setiap bulan
3. **Monitor log** untuk aktivitas mencurigakan
4. **Atur `SESSION_LIFETIME`** sesuai kebutuhan (default: 1 jam)
5. **Validasi IP** untuk lingkungan dengan IP statis

## 🛠️ Troubleshooting

### Token Tidak Valid
- Pastikan token belum digunakan sebelumnya
- Pastikan token belum kedaluwarsa (5 menit)
- Buka URL dari browser yang sama

### WhatsApp Tidak Terkirim
- Cek `FONNTE_TOKEN` di `.env`
- Pastikan nomor WhatsApp sudah terdaftar
- Cek format nomor (62xx bukan 0xx)

### Database Connection Error
- Cek kredensial database di `.env`
- Pastikan MySQL service sedang berjalan
- Cek user database memiliki akses

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 🤝 Kontribusi

Kontribusi selalu diterima! Silakan:

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📧 Kontak

- **GitHub:** [@qtonnnn](https://github.com/qtonnnn)
- **Email:** qtonnnn@example.com

---

Dibuat dengan ❤️ untuk komunitas Indonesia

