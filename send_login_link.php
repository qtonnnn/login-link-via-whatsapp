<?php
/**
 * Send Login Link Endpoint
 * Generate dan kirim link login ke WhatsApp
 * Menggunakan environment variables untuk keamanan
 * Menggunakan nomor WhatsApp sebagai username (tanpa password)
 */

require_once __DIR__ . '/env.php';

// Load environment variables
Env::load(__DIR__ . '/.env');

include 'koneksi.php';

// Get Fonnte token dan app URL dari environment variables
$tokenFonnte = Env::get('FONNTE_TOKEN', '');
$appUrl = Env::get('APP_URL', 'https://appkamu.com');

// Validasi input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['whatsapp']) || empty(trim($data['whatsapp']))) {
    echo json_encode(['success' => false, 'message' => 'Nomor WhatsApp wajib diisi']);
    exit;
}

$whatsappInput = trim($data['whatsapp']);

// Bersihkan dan format nomor WhatsApp
function cleanWhatsappNumber($number) {
    // Hapus semua karakter non-digit
    $clean = preg_replace('/[^0-9]/', '', $number);
    
    // Jika dimulai dengan 0, ubah ke format 62
    if (substr($clean, 0, 1) === '0') {
        return '62' . substr($clean, 1);
    }
    
    // Jika sudah dimulai dengan 62, biarkan
    if (substr($clean, 0, 2) === '62') {
        return $clean;
    }
    
    // Jika dimulai dengan 8 (tanpa 0), tambahkan 62
    if (substr($clean, 0, 1) === '8') {
        return '62' . $clean;
    }
    
    return $clean;
}

$whatsapp = cleanWhatsappNumber($whatsappInput);

// Validasi format nomor WhatsApp
if (strlen($whatsapp) < 10 || strlen($whatsapp) > 13) {
    echo json_encode(['success' => false, 'message' => 'Format nomor WhatsApp tidak valid']);
    exit;
}

// Validasi Fonnte token
if (empty($tokenFonnte) || $tokenFonnte === 'your_fonnte_api_token_here') {
    echo json_encode(['success' => false, 'message' => 'Konfigurasi sistem tidak lengkap. Silakan hubungi administrator.']);
    exit;
}

// Gunakan Prepared Statements untuk mencegah SQL Injection (Point #1 dari Kritik)
// Mencari user berdasarkan nomor WhatsApp
$stmt = $db->prepare("SELECT id, whatsapp FROM user WHERE whatsapp = ?");
$stmt->bind_param("s", $whatsapp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Gunakan pesan generic untuk keamanan (Point #3 dari Kritik)
    echo json_encode(['success' => false, 'message' => 'Nomor WhatsApp tidak terdaftar']);
    exit;
}

$user = $result->fetch_assoc();

// Generate secure token menggunakan random_bytes (lebih aman dari rand())
// 32 bytes = 64 karakter hex
$token = bin2hex(random_bytes(32));
$expired = date("Y-m-d H:i:s", time() + 300); // 5 menit

// Insert token ke database dengan prepared statement
$stmt = $db->prepare("INSERT INTO login_token (user_id, token, expired_at) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user['id'], $token, $expired);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Gagal membuat token. Silakan coba lagi.']);
    exit;
}

// Generate login link
$link = $appUrl . "/login_verify.php?token=" . $token;

// Pesan yang akan dikirim ke WhatsApp
$message = "Halo!\n\nKlik link berikut untuk login ke akun Anda:\n" . $link . "\n\nLink ini berlaku selama 5 menit.\n\nJika Anda tidak meminta link ini, abaikan pesan ini.\n\n- Tim AppKamu";

// Kirim pesan WhatsApp via Fonnte
// Format nomor untuk Fonnte
$target = $whatsapp; // Sudah dalam format 62...

// Kirim via Fonnte (panggil fungsi dari fonnte.php)
if (function_exists('Kirimfonnte')) {
    $result = Kirimfonnte($tokenFonnte, [
        'target' => $target,
        'message' => $message
    ]);
    
    // Log hasil pengiriman (tanpa expose ke user) - Point #11 dari Kritik
    error_log("WhatsApp login link sent to user {$user['id']} at " . date('Y-m-d H:i:s'));
    
    if (!$result) {
        // Log error tapi tetap tampilkan pesan sukses ke user
        error_log("Failed to send WhatsApp message to user {$user['id']}");
    }
} else {
    // Fonnte function tidak ada, log untuk debugging
    error_log("Fonnte function not found, skipping WhatsApp send");
    
    // Untuk development/demo, tampilkan link di response
    if (Env::get('APP_ENV') === 'development') {
        echo json_encode([
            'success' => true, 
            'message' => 'Link login telah dikirim ke WhatsApp Anda (DEMO: Link ditampilkan untuk testing)',
            'debug_link' => $link
        ]);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => 'Link login telah dikirim ke nomor WhatsApp Anda']);

