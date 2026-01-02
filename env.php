<?php
/**
 * Loader Variabel Environment
 * Load variabel environment dari file .env
 * Support untuk PHP 7.3+
 */

class Env {
    /**
     * Load file .env
     */
    public static function load($path = null) {
        if ($path === null) {
            $path = dirname(__DIR__) . '/.env';
        }
        
        if (!file_exists($path)) {
            return false;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Lewati komentar
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                // Hapus tanda kutip jika ada
                if (preg_match('/^"(.*)"$/', $value, $matches)) {
                    $value = $matches[1];
                } elseif (preg_match("/^'(.*)'$/", $value, $matches)) {
                    $value = $matches[1];
                }
                
                // Set variabel environment
                putenv("$name=$value");
                $_ENV[$name] = $value;
            }
        }
        
        return true;
    }
    
    /**
     * Get nilai variabel environment
     */
    public static function get($key, $default = null) {
        // Pertama cek getenv (berfungsi untuk putenv)
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }
        
        // Kemudian cek $_ENV
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        
        // Return default
        return $default;
    }
    
    /**
     * Cek apakah variabel environment ada
     */
    public static function has($key) {
        return self::get($key) !== null;
    }
}

// Load file .env otomatis saat file ini di-include
Env::load();

