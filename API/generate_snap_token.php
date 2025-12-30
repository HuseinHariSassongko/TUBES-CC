<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

session_start();
header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Load config
require_once __DIR__ . '/../Config/midtrans_config.php';
require_once __DIR__ . '/../vendor/midtrans/Midtrans.php';
require_once __DIR__ . '/../Config/database.php';

// Ambil JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validasi
$errors = [];
if (empty($input['customer_name']))  $errors[] = 'customer_name';
if (empty($input['customer_email'])) $errors[] = 'customer_email';
if (empty($input['customer_phone'])) $errors[] = 'customer_phone';
if (empty($input['gross_amount']))   $errors[] = 'gross_amount';
if (empty($input['items']))           $errors[] = 'items';

if ($errors) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Missing fields',
        'fields' => $errors
    ]);
    exit;
}

// Midtrans config
\Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
\Midtrans\Config::$isProduction = MIDTRANS_IS_PRODUCTION;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Order ID
$order_id = 'ORDER-' . time() . '-' . $_SESSION['user_id'];

// Item details
$item_details = [];
foreach ($input['items'] as $item) {
    $item_details[] = [
        'id' => $item['id'],
        'price' => (int)$item['price'],
        'quantity' => (int)$item['qty'],
        'name' => $item['name']
    ];
}

// Params Midtrans
$params = [
    'transaction_details' => [
        'order_id' => $order_id,
        'gross_amount' => (int)$input['gross_amount']
    ],
    'item_details' => $item_details,
    'customer_details' => [
        'first_name' => $input['customer_name'],
        'email' => $input['customer_email'],
        'phone' => $input['customer_phone']
    ]
];

// Generate token
$snapToken = \Midtrans\Snap::getSnapToken($params);

// Simpan DB (optional tapi bagus)
$stmt = $pdo->prepare("
    INSERT INTO transactions (order_id, user_id, total_amount, status, created_at)
    VALUES (?, ?, ?, 'pending', NOW())
");
$stmt->execute([$order_id, $_SESSION['user_id'], $input['gross_amount']]);

// Response
echo json_encode([
    'success' => true,
    'snap_token' => $snapToken,
    'order_id' => $order_id
]);
