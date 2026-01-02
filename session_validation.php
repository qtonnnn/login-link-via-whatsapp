<?php
/**
 * Helper Validasi Session
 * Include file ini di halaman yang memerlukan autentikasi
 * Pastikan ini diload SEBELUM output apapun
 */

require_once __DIR__ . '/env.php';
Env::load(__DIR__ . '/.env');

function wajibLogin() {
    // Cek apakah session sudah dimulai
    if (session_status() === PHP_SESSION_NONE) {
        // Set parameter cookie yang aman sebelum start
        $sessionLifetime = (int) Env::get('SESSION_LIFETIME', 3600);
        session_set_cookie_params([
            'lifetime' => $sessionLifetime,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'] ?? '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
    
    // Cek apakah user sudah login
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        // Redirect ke halaman login
        $loginUrl = Env::get('APP_URL', 'https://appkamu.com') . '/login.php';
        header("Location: " . $loginUrl);
        exit;
    }
    
    // Cek timeout session (inaktivitas)
    $sessionLifetime = (int) Env::get('SESSION_LIFETIME', 3600);
    if (isset($_SESSION['login_time'])) {
        $elapsed = time() - $_SESSION['login_time'];
        if ($elapsed > $sessionLifetime) {
            // Session kedaluwarsa
            session_destroy();
            $loginUrl = Env::get('APP_URL', 'https://appkamu.com') . '/login.php?expired=1';
            header("Location: " . $loginUrl);
            exit;
        }
        // Update waktu login (refresh session)
        $_SESSION['login_time'] = time();
    }
    
    // Opsional: Validasi alamat IP (aktifkan jika diperlukan)
    // if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    //     session_destroy();
    //     die('Session tidak valid: Alamat IP berubah');
    // }
    
    // Opsional: Validasi user agent (aktifkan jika diperlukan)
    // if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    //     session_destroy();
    //     die('Session tidak valid: User agent berubah');
    // }
}

function getIdUserSaatIni() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

function keluar() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Hapus semua variabel session
    $_SESSION = [];
    
    // Hapus session
    session_destroy();
    
    // Hapus cookie session
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
    
    // Redirect ke halaman login
    $loginUrl = Env::get('APP_URL', 'https://appkamu.com') . '/login.php?logout=1';
    header("Location: " . $loginUrl);
    exit;
}

