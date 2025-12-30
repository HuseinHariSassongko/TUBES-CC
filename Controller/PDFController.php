<?php
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/PDFGenerator.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/User.php';

class PDFController {
    
    public static function downloadOrderPDF() {
        requireLogin();
        
        $order_id = $_GET['order_id'] ?? 0;
        
        // Get user info
        $user = User::getById($_SESSION['user_id']);
        
        // Check if user is Premium
        if ($user['tier_name'] != 'Premium') {
            $_SESSION['flash_message'] = 'Fitur cetak PDF hanya tersedia untuk member Premium! Upgrade sekarang.';
            $_SESSION['flash_type'] = 'warning';
            header('Location: index.php?page=orders');
            exit;
        }
        
        // Get order details
        $order = Order::getById($order_id);
        
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['flash_message'] = 'Order tidak ditemukan!';
            $_SESSION['flash_type'] = 'error';
            header('Location: index.php?page=orders');
            exit;
        }
        
        // Get order items
        $order_items = Order::getItems($order_id);
        
        // Generate PDF
        PDFGenerator::generateOrderPDF($order, $order_items);
    }
}
?>
