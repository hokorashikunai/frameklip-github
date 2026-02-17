<?php
// config.php - Konfigurasi Database
// PENTING: Sesuaikan dengan setting database Anda

define('DB_HOST', 'localhost');        // Host database (biasanya localhost)
define('DB_USER', 'root');             // Username database
define('DB_PASS', '');                 // Password database (kosong untuk XAMPP default)
define('DB_NAME', 'frameklip_db');     // Nama database

// WhatsApp Number (tanpa +)
define('WA_NUMBER', '628123456789');   // GANTI dengan nomor WA Anda

// SeaBank Account Info
define('BANK_NAME', 'SeaBank');
define('BANK_ACCOUNT', '901234567890');
define('BANK_HOLDER', 'FrameKlip');

// Koneksi ke database
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return null;
    }
}

// Function untuk sanitasi input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function untuk validasi email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function untuk validasi phone
function validate_phone($phone) {
    // Hapus karakter non-digit
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Cek panjang minimal 10 digit
    return strlen($phone) >= 10;
}
?>
