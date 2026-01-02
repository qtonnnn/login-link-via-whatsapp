<?php
/**
 * Login Verification Page
 * Verifikasi token dan set session dengan security yang ketat
 * Hanya menggunakan nomor WhatsApp, tanpa password
 */

session_start();

// Load environment variables
require_once __DIR__ . '/env.php';
Env::load(__DIR__ . '/.env');

include 'koneksi.php';

// Get session lifetime dari environment
$sessionLifetime = (int) Env::get('SESSION_LIFETIME', 3600);

// Set session cookie parameters yang aman
session_set_cookie_params([
    'lifetime' => $sessionLifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']), // Hanya untuk HTTPS
    'httponly' => true, // Mencegah JavaScript access
    'samesite' => 'Strict' // Mencegah CSRF
]);

// ========== VALIDASI INPUT TOKEN (Point #2 dari Kritik) ==========
if (!isset($_GET['token']) || empty(trim($_GET['token']))) {
    die('Token tidak valid. Silakan coba login kembali.');
}

$token = trim($_GET['token']);

// Validasi format token (harus minimal 64 karakter hex karena menggunakan bin2hex(32))
if (strlen($token) < 64) {
    die('Token tidak valid. Silakan coba login kembali.');
}

// Validasi karakter token harus hex (a-f, 0-9)
if (!ctype_xdigit($token)) {
    die('Token tidak valid. Silakan coba login kembali.');
}

// ========== AMankan Query dengan Prepared Statements (Point #1 dari Kritik) ==========
$stmt = $db->prepare("SELECT lt.*, u.whatsapp FROM login_token lt 
                      JOIN user u ON lt.user_id = u.id 
                      WHERE lt.token = ? AND lt.used = 0 AND lt.expired_at >= NOW() 
                      LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Pesan generic untuk keamanan (Point #3 dari Kritik)
    die('Link login tidak valid atau sudah kadaluarsa. Silakan coba login kembali.');
}

$data = $result->fetch_assoc();

// ========== SECURITY: Regenerate Session ID setelah login sukses ==========
// Ini mencegah session fixation attack (Point #7 dari Kritik)
session_regenerate_id(true);

// Set session variables
$_SESSION['login'] = true;
$_SESSION['user_id'] = $data['user_id'];
$_SESSION['whatsapp'] = $data['whatsapp'];
$_SESSION['login_time'] = time();
$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

// Tandai token sudah dipakai dengan prepared statement
$stmt = $db->prepare("UPDATE login_token SET used = 1 WHERE id = ?");
$stmt->bind_param("i", $data['id']);
$stmt->execute();

// ========== LOGGING (Point #11 dari Kritik) ==========
error_log("User {$data['user_id']} (WhatsApp: {$data['whatsapp']}) logged in successfully at " . date('Y-m-d H:i:s'));

// Redirect ke dashboard
$dashboardUrl = Env::get('APP_URL', 'https://appkamu.com') . '/dashboard.php';
header("Location: " . $dashboardUrl);
exit;
?>

