<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Start output buffering to catch any unexpected output
ob_start();

try {
    session_start();
    
    // Set JSON header
    header('Content-Type: application/json');
    
    // Check method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }
    
    // Check session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }
    
    // Check config file
    $configPath = __DIR__ . '/../Config/midtrans_config.php';
    if (!file_exists($configPath)) {
        throw new Exception('Config file not found at: ' . $configPath);
    }
    require_once $configPath;
    
    // Check Midtrans library - try multiple locations
    $midtransLocations = [
        __DIR__ . '/../vendor/midtrans/midtrans-php/Midtrans.php',
        __DIR__ . '/../vendor/midtrans/Midtrans.php',
        __DIR__ . '/../vendor/autoload.php'
    ];
    
    $midtransLoaded = false;
    foreach ($midtransLocations as $path) {
        if (file_exists($path)) {
            require_once $path;
            $midtransLoaded = true;
            break;
        }
    }
    
    if (!$midtransLoaded) {
        throw new Exception('Midtrans library not found. Install dengan: composer require midtrans/midtrans-php');
    }
    
    // Check if Midtrans class exists
    if (!class_exists('Midtrans\Snap')) {
        throw new Exception('Midtrans\Snap class not found. Library tidak terload dengan benar.');
    }
    
    // Check constants
    if (!defined('MIDTRANS_SERVER_KEY')) {
        throw new Exception('MIDTRANS_SERVER_KEY not defined in config');
    }
    if (!defined('MIDTRANS_IS_PRODUCTION')) {
        throw new Exception('MIDTRANS_IS_PRODUCTION not defined in config');
    }
    
    // Get input
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    
    if (!$input) {
        throw new Exception('No JSON input received. Raw input: ' . substr($rawInput, 0, 100));
    }
    
    // Validate input
    $months = isset($input['months']) ? (int)$input['months'] : 0;
    $price = isset($input['price']) ? (int)$input['price'] : 0;
    
    if ($months <= 0) {
        throw new Exception('Invalid months: ' . $months);
    }
    if ($price <= 0) {
        throw new Exception('Invalid price: ' . $price);
    }
    
    // Generate Order ID
    $order_id = 'SUB-' . $_SESSION['user_id'] . '-' . date('Ymd') . '-' . strtoupper(substr(md5(time()), 0, 6));
    
    // Configure Midtrans
    \Midtrans\Config::$serverKey = MIDTRANS_SERVER_KEY;
    \Midtrans\Config::$isProduction = MIDTRANS_IS_PRODUCTION;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
    
    // Prepare transaction
    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $price
        ],
        'item_details' => [
            [
                'id' => 'premium-subscription',
                'price' => $price,
                'quantity' => 1,
                'name' => "Premium Subscription - $months Bulan"
            ]
        ],
        'customer_details' => [
            'first_name' => isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'User',
            'email' => isset($_SESSION['email']) ? $_SESSION['email'] : 'user@example.com',
            'phone' => isset($_SESSION['phone']) ? $_SESSION['phone'] : '08123456789'
        ]
    ];
    
    // Get Snap Token
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    
    // Clear any unexpected output
    ob_clean();
    
    // Success response
    echo json_encode([
        'success' => true,
        'snap_token' => $snapToken,
        'order_id' => $order_id
    ]);
    
} catch (Exception $e) {
    // Clear any output
    ob_clean();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString())
    ]);
}

ob_end_flush();
?>
