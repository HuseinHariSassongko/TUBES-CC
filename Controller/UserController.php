<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/functions.php';

class UserController {
    
    // Dashboard Overview
    public static function dashboard() {
        requireLogin();
        
        $user_id = $_SESSION['user_id'];
        
        // Get user stats
        $stats = [
            'total_orders' => self::getTotalOrders($user_id),
            'pending_orders' => self::getPendingOrders($user_id),
            'completed_orders' => self::getCompletedOrders($user_id),
            'total_spent' => self::getTotalSpent($user_id)
        ];
        
        // Recent orders
        $recent_orders = Order::getByUserId($user_id);
        $recent_orders = array_slice($recent_orders, 0, 5); // Ambil 5 terakhir
        
        // User info
        $user = User::getById($user_id);
        
        include __DIR__ . '/../Views/User/dashboard.php';
    }
    
    // Helper Methods
    private static function getTotalOrders($user_id) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ?";
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0]['total'] ?? 0;
    }
    
    private static function getPendingOrders($user_id) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND status = 'pending'";
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0]['total'] ?? 0;
    }
    
    private static function getCompletedOrders($user_id) {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND status IN ('completed', 'delivered')";
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0]['total'] ?? 0;
    }
    
    private static function getTotalSpent($user_id) {
        $sql = "SELECT SUM(total_amount) as total FROM orders WHERE user_id = ? AND status IN ('completed', 'delivered')";
        $result = executeQuery($sql, [$user_id], 'i');
        return $result[0]['total'] ?? 0;
    }
}
?>
