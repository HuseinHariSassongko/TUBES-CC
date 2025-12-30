<?php
// Format currency
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Generate unique order number
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
}

// Calculate discount for Premium users
function calculateDiscount($subtotal, $total_qty, $tier) {
    if ($tier != 'Premium') return 0;
    if ($total_qty <= 100) return 0;
    
    return $subtotal * 0.10; // 10% discount
}

// Check monthly order limit for Basic users
function checkOrderLimit($user_id) {
    require_once __DIR__ . '/../Config/database.php';
    
    $tier = getUserTier();
    if ($tier == 'Premium') return true; // Unlimited
    
    $sql = "SELECT COUNT(*) as count FROM orders 
            WHERE user_id = ? 
            AND MONTH(created_at) = MONTH(CURRENT_DATE())
            AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    
    $result = executeQuery($sql, [$user_id], 'i');
    $count = $result[0]['count'];
    
    return $count < 10; // Basic limit: 10 orders/month
}

// Get available stock for user (based on tier)
function getAvailableStock($product_id, $tier) {
    require_once __DIR__ . '/../Config/database.php';
    
    if ($tier == 'Premium') {
        $sql = "SELECT (stock_priority_kg + stock_kg) as available 
                FROM products WHERE product_id = ?";
    } else {
        $sql = "SELECT stock_kg as available 
                FROM products WHERE product_id = ?";
    }
    
    $result = executeQuery($sql, [$product_id], 'i');
    return $result[0]['available'] ?? 0;
}

// Sanitize input
function clean($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Redirect with message
function redirect($page, $message = '', $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: index.php?page=$page");
    exit;
}

// Show flash message
function showFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] ?? 'success';
        $message = $_SESSION['flash_message'];
        
        $alertClass = $type == 'success' ? 'alert-success' : 'alert-danger';
        
        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
    }
}
?>
