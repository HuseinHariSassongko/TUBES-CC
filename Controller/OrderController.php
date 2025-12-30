<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class OrderController {
    
    // My Orders
    public static function myOrders() {
        requireLogin();
        
        $orders = Order::getByUserId($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/User/orders.php';
    }
    
    // Order Detail
    public static function detail() {
        requireLogin();
        
        $order_id = $_GET['id'] ?? 0;
        $order = Order::getById($order_id);
        
        // Check ownership
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            redirect('orders', 'Pesanan tidak ditemukan!', 'error');
            return;
        }
        
        $order_items = Order::getItems($order_id);
        
        include __DIR__ . '/../Views/User/order_detail.php';
    }
    
    // Cancel Order
    public static function cancel() {
        requireLogin();
        
        $order_id = $_GET['id'] ?? 0;
        $order = Order::getById($order_id);
        
        // Check ownership
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            redirect('orders', 'Pesanan tidak ditemukan!', 'error');
            return;
        }
        
        // Only pending orders can be cancelled
        if ($order['status'] != 'pending') {
            redirect('orders', 'Hanya pesanan pending yang bisa dibatalkan!', 'error');
            return;
        }
        
        $result = Order::updateStatus($order_id, 'cancelled');
        
        if ($result['success']) {
            redirect('orders', 'Pesanan berhasil dibatalkan!');
        } else {
            redirect('orders', 'Gagal membatalkan pesanan!', 'error');
        }
    }
}
?>
