<?php
session_start();
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Config/midtrans_config.php';

// --- PERBAIKAN UTAMA: Ganti autoload.php dengan require manual ---
// Hapus baris ini:
// require_once __DIR__ . '/../vendor/autoload.php'; // Composer autoload

// Ganti dengan baris ini:
require_once __DIR__ . '/../vendor/midtrans/Midtrans.php'; // Impor library Midtrans v1

// Set response header
header('Content-Type: application/json');

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Validasi user login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $items = $input['items'] ?? [];
    $total = $input['total'] ?? 0;

    // Validasi input
    if (empty($name) || empty($email) || empty($phone) || empty($items) || $total <= 0) {
        throw new Exception('Data tidak lengkap');
    }

    // Generate Order ID
    $order_id = 'ORDER-' . $_SESSION['user_id'] . '-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));

    // Format item details untuk Midtrans
    $item_details = [];
    foreach ($items as $item) {
        $item_details[] = [
            'id' => $item['id'],
            'price' => (int)$item['price'],
            'quantity' => (int)$item['qty'],
            'name' => $item['name']
        ];
    }

    // Transaction details
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => (int)$total
    ];

    // Customer details
    $customer_details = [
        'first_name' => $name,
        'email' => $email,
        'phone' => $phone
    ];

    // Midtrans configuration - PASTIKAN CONFIG FILE SUDAH BENAR
    \Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
    \Midtrans\Config::$isProduction = MIDTRANS_IS_PRODUCTION;
    \Midtrans\Config::$isSanitized = getenv('MIDTRANS_IS_SANITIZED') === 'true';
    \Midtrans\Config::$is3ds = getenv('MIDTRANS_IS_3DS') === 'true';

    // Create Snap transaction
    $params = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details
    ];

    // --- PERBAIKAN UTAMA: Gunakan class Snap dari namespace Midtrans ---
    // Di versi lama, class Snap ada di namespace Midtrans
    $snapToken = \Midtrans\Snap::getSnapToken($params);

    // --- PENTING: Simpan transaksi ke database ---
    // Koneksi database sudah di-include di atas
    $stmt = $pdo->prepare("INSERT INTO transactions (order_id, user_id, total_amount, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->execute([$order_id, $_SESSION['user_id'], $total]);

    // Return success response
    echo json_encode([
        'success' => true,
        'token' => $snapToken,
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}