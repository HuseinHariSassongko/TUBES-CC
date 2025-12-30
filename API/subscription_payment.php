<?php
session_start();
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Config/midtrans_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $months = (int)($input['months'] ?? 0);
    $price = (int)($input['price'] ?? 0);
    
    if ($months <= 0 || $price <= 0) {
        throw new Exception('Data tidak valid');
    }
    
    // Generate Order ID
    $order_id = 'SUB-' . $_SESSION['user_id'] . '-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));
    
    // Transaction details
    $transaction_details = [
        'order_id' => $order_id,
        'gross_amount' => $price
    ];
    
    // Item details
    $item_details = [
        [
            'id' => 'premium-subscription',
            'price' => $price,
            'quantity' => 1,
            'name' => "Premium Subscription - $months Bulan"
        ]
    ];
    
    // Customer details
    $customer_details = [
        'first_name' => $_SESSION['full_name'],
        'email' => $_SESSION['email'],
        'phone' => $_SESSION['phone'] ?? '0000000000'
    ];
    
    // Midtrans configuration
    \Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
    \Midtrans\Config::$isProduction = MIDTRANS_IS_PRODUCTION;
    \Midtrans\Config::$isSanitized = getenv('MIDTRANS_IS_SANITIZED') === 'true';
    \Midtrans\Config::$is3ds = getenv('MIDTRANS_IS_3DS') === 'true';

    
    // Create Snap transaction
    $params = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details,
        'custom_field1' => 'subscription',
        'custom_field2' => $months
    ];
    
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    echo json_encode([
        'success' => true,
        'token' => $snapToken,  // ✅ BENAR
        'order_id' => $order_id
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
