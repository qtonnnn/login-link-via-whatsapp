<?php
/**
 * Database Connection
 * Menggunakan environment variables untuk keamanan
 */

require_once __DIR__ . '/env.php';

// Load environment variables dari .env file
Env::load(__DIR__ . '/.env');

// Get database credentials dari environment variables
$db_host = Env::get('DB_HOST', 'localhost');
$db_user = Env::get('DB_USER', 'root');
$db_password = Env::get('DB_PASSWORD', '');
$db_name = Env::get('DB_NAME', 'dblink');

// Create database connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
    // Log error untuk admin, jangan tampilkan ke user
    error_log("Database connection failed: " . mysqli_connect_error());
    
    // Tampilkan pesan error generik untuk user
    die("Maaf, terjadi kesalahan sistem. Silakan coba lagi nanti.");
}

// Set charset untuk mencegah character encoding issues
mysqli_set_charset($conn, "utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Alias untuk $conn agar konsisten dengan kode yang ada
$db = $conn;

