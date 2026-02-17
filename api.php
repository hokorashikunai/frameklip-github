<?php
// api.php - API untuk handle pemesanan (v3.0 - 4 tables)
header('Content-Type: application/json');
require_once 'config.php';

// Hanya terima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Ambil data dari request
$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
$errors = [];

if (empty($input['service'])) {
    $errors[] = 'Layanan harus dipilih';
}

if (empty($input['package'])) {
    $errors[] = 'Paket harus dipilih';
}

if (empty($input['name'])) {
    $errors[] = 'Nama harus diisi';
}

if (empty($input['email']) || !validate_email($input['email'])) {
    $errors[] = 'Email tidak valid';
}

if (empty($input['phone']) || !validate_phone($input['phone'])) {
    $errors[] = 'Nomor telepon tidak valid';
}

// Jika ada error, return error
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validasi gagal',
        'errors' => $errors
    ]);
    exit;
}

// Sanitasi data
$service = sanitize_input($input['service']);
$package = sanitize_input($input['package']);
$name = sanitize_input($input['name']);
$email = sanitize_input($input['email']);
$phone = sanitize_input($input['phone']);
$notes = isset($input['notes']) ? sanitize_input($input['notes']) : '';

// Koneksi database
$conn = getDBConnection();

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed'
    ]);
    exit;
}

// Start transaction untuk insert ke 3 tabel
$conn->begin_transaction();

try {
    // 1. Insert ke table ORDERS
    $stmt = $conn->prepare("INSERT INTO orders (service, package, customer_name, customer_email, customer_phone, notes) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ssssss", $service, $package, $name, $email, $phone, $notes);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert order: ' . $stmt->error);
    }
    
    $order_id = $stmt->insert_id;
    $stmt->close();
    
    // 2. Calculate price
    $prices = [
        'Edit Reels / Video Pendek' => ['Regular' => 15000, 'Fast Track' => 25000],
        'Cinematic' => ['Regular' => 20000, 'Fast Track' => 30000],
        'Dokumenter' => ['Regular' => 50000, 'Fast Track' => 80000],
        'Preset' => ['Regular' => 20000, 'Fast Track' => 30000]
    ];
    
    $price = isset($prices[$service][$package]) ? $prices[$service][$package] : 0;
    
    // 3. Insert ke table PAYMENTS
    $stmt_payment = $conn->prepare("INSERT INTO payments (order_id, total_amount, payment_verified) VALUES (?, ?, 0)");
    
    if (!$stmt_payment) {
        throw new Exception('Prepare payment statement failed: ' . $conn->error);
    }
    
    $stmt_payment->bind_param("id", $order_id, $price);
    
    if (!$stmt_payment->execute()) {
        throw new Exception('Failed to insert payment: ' . $stmt_payment->error);
    }
    
    $stmt_payment->close();
    
    // 4. Insert ke table PRODUCTIONS
    $stmt_prod = $conn->prepare("INSERT INTO productions (order_id, status) VALUES (?, 'draft')");
    
    if (!$stmt_prod) {
        throw new Exception('Prepare production statement failed: ' . $conn->error);
    }
    
    $stmt_prod->bind_param("i", $order_id);
    
    if (!$stmt_prod->execute()) {
        throw new Exception('Failed to insert production: ' . $stmt_prod->error);
    }
    
    $stmt_prod->close();
    
    // Commit transaction - semua berhasil!
    $conn->commit();
    
    // Format data untuk response
    $formatted_price = 'Rp ' . number_format($price, 0, ',', '.');
    $estimate = ($package === 'Fast Track') ? '1-2 hari kerja' : '3-4 hari kerja';
    
    // Generate WhatsApp message WITH PRICE and ESTIMATE
    $wa_message = urlencode(
        "Halo FrameKlip!\n\n" .
        "Saya ingin memesan jasa editing video:\n\n" .
        "━━━━━━━━━━━━━━━━━━\n" .
        "Order ID: *#" . $order_id . "*\n" .
        "Layanan: *" . $service . "*\n" .
        "Paket: *" . $package . "*\n" .
        "Total Bayar: *" . $formatted_price . "*\n" .
        "Estimasi Selesai: *" . $estimate . "*\n" .
        "━━━━━━━━━━━━━━━━━━\n\n" .
        "Nama: " . $name . "\n" .
        "Email: " . $email . "\n" .
        "No. HP: " . $phone . "\n\n" .
        "Saya akan segera mengirimkan bukti transfer dan link Google Drive untuk file video.\n\n" .
        "Terima kasih! 🎬"
    );
    
    echo json_encode([
        'success' => true,
        'message' => 'Pesanan berhasil disimpan',
        'order_id' => $order_id,
        'wa_url' => 'https://wa.me/' . WA_NUMBER . '?text=' . $wa_message
    ]);
    
} catch (Exception $e) {
    // Rollback jika ada error
    $conn->rollback();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()
    ]);
}

$conn->close();
?>